#!/usr/bin/env node
/**
 * Rendered text-contrast auditor.
 *
 * Token tables say what the palette COULD do. This measures what is actually
 * PAINTED behind every piece of text: photos, gradients, translucent overlays,
 * marker stripes, cards. Zero dependencies (Node 22+, Chrome driven over a raw
 * CDP WebSocket, PNG decoded in-process).
 *
 * USAGE
 *   node tests/manual/contrast-audit.mjs [--base http://127.0.0.1:8899]
 *        [--pages /,/pharmacy-first] [--viewports 1440x900,390x844]
 *        [--json out.json] [--selftest]
 *
 *   --base <url>          site to audit (default http://127.0.0.1:8899)
 *   --pages a,b           only these paths. Default: crawl every internal page from "/"
 *                         (follows redirects, ignores assets, like crawl.mjs)
 *   --viewports WxH,..    default 1440x900,390x844. The device scale factor is always 1.
 *   --json <file>         also write the full machine-readable result
 *   --selftest            do NOT touch the site: render a seeded fixture in about:blank and
 *                         assert the verdicts (incl. mutation checks that prove the assertions
 *                         have teeth). Exit 1 if any expectation is wrong.
 *   --show-warns          also list WARN items (default: failures listed, warns counted)
 *   --ignore <selector>   skip text inside matching elements (e.g. a logo, which WCAG exempts)
 *   --save-bg <dir>       keep the text-free background captures (PNG chunks) for inspection
 *   --normal <n> --large <n>   thresholds (default 4.5 and 3.0); --aaa sets 7 and 4.5
 *   --jobs N              parallel Chrome instances on ports 9810, 9811, ... (default 1)
 *   --port N              first debugging port (default 9810)
 *   --no-reduced-motion   do not emulate prefers-reduced-motion (default: emulate it, so the
 *                         site's own "at rest" CSS applies and rails/counters stay still)
 *   --verbose             per-stage timings        --no-color   plain output
 *
 * EXIT CODE   0 no FAIL | 1 at least one FAIL (or a wrong self-test expectation)
 *             2 the audit itself could not complete (navigation/capture error, bad args)
 *
 * HOW IT WORKS (per page and viewport)
 *   1. Navigate; stabilise: animations/transitions off, hero rail filters off, parallax off,
 *      fixed header made absolute, reveal/clip-reveal/line-mask get .is-visible, lazy images
 *      forced eager, page scrolled through in 500px steps, network + fonts awaited, then the
 *      text signature is polled until two readings agree.
 *   2. ONE in-page pass lists every VISIBLE text item: each direct text node's Range rects
 *      (one per line) in page coordinates, computed paint colour (RGBA, any CSS colour
 *      syntax, normalised by the browser), font size/weight, effective alpha (colour alpha x
 *      ancestor opacities), clipped by overflow/clip-path/clip along the containing-block chain.
 *      Input/textarea placeholders (::placeholder colour and opacity), input values, select
 *      labels and SVG <text> are included. Anything not rendered is skipped and counted.
 *   3. Text is then made transparent (colour, text-fill, shadows, placeholders, SVG fill) and
 *      the whole document is captured in <= 6000px chunks. Everything painted UNDER the text is
 *      in those pixels with the text itself gone.
 *   4. Each text line rect is sampled on a grid (<= 200 pixels); the text colour is composited
 *      with its effective alpha over every sample and the WCAG 2.x ratio computed.
 *   5. LARGE = >= 24px, or >= 18.66px with weight >= 700 (3.0:1); otherwise 4.5:1.
 *      FAIL if the 10th-percentile ratio is below threshold; WARN if p10 passes but the single
 *      worst pixel does not. An item's verdict is its WORST LINE (each line is judged alone).
 *   6. The page is re-listed after the capture; if text moved, the page is reloaded and
 *      re-measured (up to 3 attempts) so a timer-driven change can never pair text from one
 *      state with a background from another.
 *
 * LIMITATIONS: see the end of the file.
 */
import { spawn } from 'node:child_process';
import { mkdirSync, rmSync, writeFileSync } from 'node:fs';
import net from 'node:net';
import zlib from 'node:zlib';

const CHROME = process.env.CHROME_BIN || '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const SAMPLE_TARGET = 200;     // pixels sampled per text line
const CHUNK_HEIGHT = 6000;     // screenshot chunk height (GPU limits)
const MAX_ATTEMPTS = 3;        // re-measure when the page changes during a capture
const LARGE_PX = 24;
const LARGE_BOLD_PX = 18.66;
const T0 = Date.now();

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
const now = () => Date.now();
const withTimeout = (promise, ms, label) => {
    let timer;
    return Promise.race([
        promise,
        new Promise((_, rej) => { timer = setTimeout(() => rej(new Error(`timed out after ${(ms / 1000).toFixed(0)}s: ${label}`)), ms); }),
    ]).finally(() => clearTimeout(timer));
};

/* ------------------------------------------------------------- WCAG maths */

const LIN = new Float64Array(256);
for (let i = 0; i < 256; i++) {
    const c = i / 255;
    LIN[i] = c <= 0.03928 ? c / 12.92 : ((c + 0.055) / 1.055) ** 2.4;
}
const luminance = (r, g, b) => 0.2126 * LIN[r] + 0.7152 * LIN[g] + 0.0722 * LIN[b];
const ratioOf = (a, b) => (a > b ? (a + 0.05) / (b + 0.05) : (b + 0.05) / (a + 0.05));
const hx = (n) => Math.max(0, Math.min(255, Math.round(n))).toString(16).padStart(2, '0');
const toHex = (r, g, b) => `#${hx(r)}${hx(g)}${hx(b)}`;
const parseHex = (h) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16));
const contrastHex = (a, b) => {
    const [r1, g1, b1] = parseHex(a), [r2, g2, b2] = parseHex(b);
    return ratioOf(luminance(r1, g1, b1), luminance(r2, g2, b2));
};
const isLarge = (px, weight) => px >= LARGE_PX || (px >= LARGE_BOLD_PX && weight >= 700);

/* ------------------------------------------------------------ PNG decoder */

/** 8-bit, non-interlaced PNG (colour types 0, 2, 4, 6): what Chrome's screenshots are. */
function decodePng(buf) {
    const sig = [0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a];
    for (let i = 0; i < 8; i++) if (buf[i] !== sig[i]) throw new Error('not a PNG');
    let p = 8, width = 0, height = 0, depth = 0, ctype = 0, interlace = 0;
    const idat = [];
    while (p + 8 <= buf.length) {
        const len = buf.readUInt32BE(p);
        const type = buf.toString('latin1', p + 4, p + 8);
        const start = p + 8;
        if (type === 'IHDR') {
            width = buf.readUInt32BE(start); height = buf.readUInt32BE(start + 4);
            depth = buf[start + 8]; ctype = buf[start + 9]; interlace = buf[start + 12];
        } else if (type === 'IDAT') idat.push(buf.subarray(start, start + len));
        else if (type === 'IEND') break;
        p = start + len + 4;
    }
    if (depth !== 8) throw new Error(`unsupported PNG bit depth ${depth}`);
    if (interlace !== 0) throw new Error('unsupported interlaced PNG');
    const channels = { 0: 1, 2: 3, 4: 2, 6: 4 }[ctype];
    if (!channels) throw new Error(`unsupported PNG colour type ${ctype}`);
    const raw = zlib.inflateSync(Buffer.concat(idat));
    const stride = width * channels;
    if (raw.length < (stride + 1) * height) throw new Error('truncated PNG data');
    const data = Buffer.allocUnsafe(stride * height);
    const bpp = channels;
    for (let y = 0; y < height; y++) {
        const f = raw[y * (stride + 1)];
        const src = y * (stride + 1) + 1, dst = y * stride, prev = dst - stride;
        if (f === 0) raw.copy(data, dst, src, src + stride);
        else if (f === 1) {
            for (let i = 0; i < stride; i++) data[dst + i] = (raw[src + i] + (i >= bpp ? data[dst + i - bpp] : 0)) & 255;
        } else if (f === 2) {
            if (y === 0) raw.copy(data, dst, src, src + stride);
            else for (let i = 0; i < stride; i++) data[dst + i] = (raw[src + i] + data[prev + i]) & 255;
        } else if (f === 3) {
            for (let i = 0; i < stride; i++) {
                const a = i >= bpp ? data[dst + i - bpp] : 0, b = y > 0 ? data[prev + i] : 0;
                data[dst + i] = (raw[src + i] + ((a + b) >> 1)) & 255;
            }
        } else if (f === 4) {
            for (let i = 0; i < stride; i++) {
                const a = i >= bpp ? data[dst + i - bpp] : 0;
                const b = y > 0 ? data[prev + i] : 0;
                const c = i >= bpp && y > 0 ? data[prev + i - bpp] : 0;
                const pa = Math.abs(b - c), pb = Math.abs(a - c), pc = Math.abs(a + b - 2 * c);
                data[dst + i] = (raw[src + i] + (pa <= pb && pa <= pc ? a : pb <= pc ? b : c)) & 255;
            }
        } else throw new Error(`bad PNG filter ${f}`);
    }
    return { width, height, channels, data };
}

/* -------------------------------------------------------------------- cli */

function die(msg, code = 2) {
    console.error(`contrast-audit: ${msg}\nSee the usage block at the top of tests/manual/contrast-audit.mjs`);
    process.exit(code);
}

function parseViewport(s) {
    const m = /^(\d{2,5})x(\d{2,5})$/.exec(String(s).trim());
    if (!m) die(`bad viewport "${s}" (expected WxH, e.g. 1440x900)`);
    return { w: +m[1], h: +m[2], key: `${m[1]}x${m[2]}` };
}

function parseArgs(argv) {
    const o = {
        base: 'http://127.0.0.1:8899', pages: null, viewports: '1440x900,390x844', json: null, selftest: false,
        showWarns: false, ignore: null, saveBg: null, normal: 4.5, large: 3.0, jobs: 1, port: 9810,
        reducedMotion: true, verbose: false, color: process.stdout.isTTY && !process.env.NO_COLOR, help: false,
    };
    const flags = new Set(['--selftest', '--show-warns', '--aaa', '--no-reduced-motion', '--verbose', '--no-color', '--help', '-h']);
    for (let i = 0; i < argv.length; i++) {
        const raw = argv[i];
        const eq = raw.startsWith('--') ? raw.indexOf('=') : -1;
        const a = eq > 0 ? raw.slice(0, eq) : raw;
        const inline = eq > 0 ? raw.slice(eq + 1) : undefined;
        const val = () => {
            if (inline !== undefined) return inline;
            if (i + 1 >= argv.length) die(`${a} needs a value`);
            return argv[++i];
        };
        if (!a.startsWith('-')) die(`unexpected argument "${a}"`);
        switch (a) {
            case '--base': o.base = val().replace(/\/+$/, ''); break;
            case '--pages': o.pages = val().split(',').map((s) => s.trim()).filter(Boolean)
                .map((p) => { try { const u = new URL(p, 'http://x'); return u.pathname + (u.search || ''); } catch { return p.startsWith('/') ? p : `/${p}`; } }); break;
            case '--viewports': o.viewports = val(); break;
            case '--json': o.json = val(); break;
            case '--ignore': o.ignore = val(); break;
            case '--save-bg': o.saveBg = val(); break;
            case '--normal': o.normal = parseFloat(val()); break;
            case '--large': o.large = parseFloat(val()); break;
            case '--aaa': o.normal = 7; o.large = 4.5; break;
            case '--jobs': o.jobs = Math.max(1, Math.min(6, parseInt(val(), 10) || 1)); break;
            case '--port': o.port = parseInt(val(), 10) || 9810; break;
            case '--selftest': o.selftest = true; break;
            case '--show-warns': o.showWarns = true; break;
            case '--no-reduced-motion': o.reducedMotion = false; break;
            case '--verbose': o.verbose = true; break;
            case '--no-color': o.color = false; break;
            case '--help': case '-h': o.help = true; break;
            default: die(`unknown option ${a}`);
        }
    }
    if (!(o.normal > 1) || !(o.large > 1)) die('--normal/--large must be numbers above 1');
    return o;
}

/* ---------------------------------------------------------------- CDP client */

class Cdp {
    constructor(ws) {
        this.ws = ws;
        this.id = 0;
        this.pending = new Map();
        this.listeners = new Map();
        this.closed = false;
        ws.onmessage = (e) => {
            let m;
            try { m = JSON.parse(e.data); } catch { return; }
            if (m.id !== undefined) {
                const p = this.pending.get(m.id);
                if (!p) return;
                this.pending.delete(m.id);
                clearTimeout(p.timer);
                if (m.error) p.rej(new Error(`${p.method}: ${m.error.message}`));
                else p.res(m.result ?? {});
            } else if (m.method) {
                for (const fn of this.listeners.get(m.method) ?? []) {
                    try { fn(m.params ?? {}); } catch { /* a listener must never break the socket */ }
                }
            }
        };
        const gone = () => {
            if (this.closed) return;
            this.closed = true;
            for (const [, p] of this.pending) { clearTimeout(p.timer); p.rej(new Error(`${p.method}: connection closed`)); }
            this.pending.clear();
        };
        ws.onclose = gone;
        ws.onerror = gone;
    }

    static async connect(url) {
        const ws = new WebSocket(url);
        await new Promise((res, rej) => { ws.onopen = res; ws.onerror = () => rej(new Error('websocket error')); });
        return new Cdp(ws);
    }

    send(method, params = {}, timeout = 30000) {
        if (this.closed) return Promise.reject(new Error(`${method}: connection closed`));
        return new Promise((res, rej) => {
            const id = ++this.id;
            const timer = setTimeout(() => {
                this.pending.delete(id);
                rej(new Error(`${method}: no answer after ${timeout / 1000}s`));
            }, timeout);
            this.pending.set(id, { res, rej, timer, method });
            try { this.ws.send(JSON.stringify({ id, method, params })); } catch (e) { clearTimeout(timer); this.pending.delete(id); rej(e); }
        });
    }

    on(method, fn) {
        if (!this.listeners.has(method)) this.listeners.set(method, new Set());
        this.listeners.get(method).add(fn);
        return () => this.listeners.get(method).delete(fn);
    }

    once(method, timeout = 30000) {
        return new Promise((res, rej) => {
            const off = this.on(method, (p) => { off(); clearTimeout(timer); res(p); });
            const timer = setTimeout(() => { off(); rej(new Error(`${method}: not fired within ${timeout / 1000}s`)); }, timeout);
        });
    }

    /** Runtime.evaluate returning a JSON value; page-side exceptions become Errors. */
    async eval(expression, timeout = 30000) {
        const r = await this.send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true }, timeout);
        if (r.exceptionDetails) {
            const d = r.exceptionDetails;
            throw new Error(`page script failed: ${d.exception?.description ?? d.text}`);
        }
        return r.result.value;
    }

    close() { try { this.ws.close(); } catch { /* already gone */ } }
}

/* ------------------------------------------------------------ Chrome launch */

const liveChromes = new Set();

const portFree = (port) => new Promise((res) => {
    const s = net.createServer();
    s.once('error', () => res(false));
    s.once('listening', () => s.close(() => res(true)));
    s.listen(port, '127.0.0.1');
});

/**
 * Start one headless Chrome and connect to its page target. Only processes spawned here
 * are ever killed, by handle, never by name: other Chromes and PHP servers belong to
 * other people.
 */
async function launchBrowser(startPort, tag) {
    let port = startPort;
    while (!(await portFree(port))) port += 1;    // never drive somebody else's DevTools port
    const dir = `/tmp/cdp-contrast-${process.pid}-${tag}`;
    rmSync(dir, { recursive: true, force: true });
    const child = spawn(CHROME, [
        '--headless=new', `--remote-debugging-port=${port}`, '--disable-gpu', '--hide-scrollbars', '--no-first-run',
        '--force-color-profile=srgb', '--no-default-browser-check', '--mute-audio',
        '--disable-background-timer-throttling', '--disable-renderer-backgrounding', '--disable-backgrounding-occluded-windows',
        `--user-data-dir=${dir}`, 'about:blank',
    ], { stdio: 'ignore' });
    const proc = { child, dir, port, exited: false };
    child.on('exit', () => { proc.exited = true; liveChromes.delete(proc); });
    child.on('error', () => { proc.exited = true; });
    liveChromes.add(proc);

    let target = null;
    const started = now();
    while (!target) {
        if (proc.exited) throw new Error('Chrome exited during start-up');
        if (now() - started > 20000) { await stopBrowser({ proc }); throw new Error('Chrome did not open its debugging port within 20s'); }
        try {
            const list = await (await fetch(`http://127.0.0.1:${port}/json/list`, { signal: AbortSignal.timeout(2000) })).json();
            target = list.find((t) => t.type === 'page') ?? null;
        } catch { /* not up yet */ }
        if (!target) await sleep(100);
    }
    const cdp = await Cdp.connect(target.webSocketDebuggerUrl);
    const b = { proc, cdp, port, inflight: new Set(), mainStatus: null };

    cdp.on('Page.javascriptDialogOpening', () => { cdp.send('Page.handleJavaScriptDialog', { accept: true }).catch(() => {}); });
    cdp.on('Network.requestWillBeSent', (p) => {
        // Same-origin and data-less only: Google Fonts and map tiles are external and may never
        // answer in a sandbox, so they must not hold the page "busy".
        if (!/^https?:/.test(p.request.url) || !p.request.url.startsWith(b.origin ?? '\0')) return;
        b.inflight.add(p.requestId);
    });
    const done = (p) => b.inflight.delete(p.requestId);
    cdp.on('Network.loadingFinished', done);
    cdp.on('Network.loadingFailed', done);
    cdp.on('Network.responseReceived', (p) => {
        if (p.type === 'Document' && b.mainStatus === null) b.mainStatus = p.response.status;
    });
    await cdp.send('Page.enable');
    await cdp.send('Runtime.enable');
    await cdp.send('Network.enable');
    return b;
}

async function stopBrowser(b) {
    if (!b) return;
    const proc = b.proc;
    try { b.cdp?.close(); } catch { /* gone */ }
    if (proc && !proc.exited) {
        try { proc.child.kill('SIGTERM'); } catch { /* gone */ }
        const until = now() + 2500;
        while (!proc.exited && now() < until) await sleep(50);
        if (!proc.exited) { try { proc.child.kill('SIGKILL'); } catch { /* gone */ } await sleep(150); }
    }
    if (proc) rmSync(proc.dir, { recursive: true, force: true });
}

const killAllSync = () => {
    for (const p of liveChromes) { try { p.child.kill('SIGKILL'); } catch { /* gone */ } rmSync(p.dir, { recursive: true, force: true }); }
};
process.on('exit', killAllSync);
for (const sig of ['SIGINT', 'SIGTERM']) process.on(sig, () => { killAllSync(); process.exit(130); });

/* ---------------------------------------------------------- page-side code */

/** Neutralises everything that makes two captures of the same page differ. */
const STABILISE_CSS = [
    '*,*::before,*::after{animation:none!important;transition:none!important}',
    '[data-carousel-item] img{filter:none!important;opacity:1!important;will-change:auto!important}',
    '[data-parallax]{transform:none!important}',
    'header[data-header]{position:absolute!important}',
    '#scroll-progress{visibility:hidden!important}',
].join('\n');

/** Takes the text OUT of the picture and leaves everything painted behind it. */
const HIDE_TEXT_CSS = [
    '*,*::before,*::after{color:transparent!important;-webkit-text-fill-color:transparent!important;text-shadow:none!important;caret-color:transparent!important;-webkit-text-stroke:0!important}',
    '::placeholder{color:transparent!important;-webkit-text-fill-color:transparent!important;opacity:1!important}',
    'svg text,svg tspan,svg textPath{fill:transparent!important;stroke:transparent!important}',
].join('\n');

/**
 * Runs INSIDE the page (serialised with toString, so it must not touch anything outside
 * its own scope). Returns a JSON string.
 *
 * opts: { sigOnly, geometryOnly, ignore, mutate }
 *   sigOnly       only the signatures (used to wait for the page to stop changing)
 *   geometryOnly  positions and text only: colours are ignored, because by then the text
 *                 has been made transparent on purpose
 */
function pageCollect(opts) {
    const SVG_NS = 'http://www.w3.org/2000/svg';
    const SKIP = new Set(['script', 'style', 'noscript', 'template', 'head', 'title', 'meta', 'link', 'base',
        'textarea', 'option', 'optgroup', 'select', 'datalist', 'iframe', 'object', 'canvas', 'desc']);
    const docEl = document.documentElement;
    const body = document.body || docEl;
    const sx = window.scrollX, sy = window.scrollY;
    const docW = Math.max(1, docEl.clientWidth || window.innerWidth);
    const docH = Math.max(1, Math.ceil(Math.max(docEl.scrollHeight, body.scrollHeight || 0)));
    const skipped = {};
    const unmeasured = [];
    const bump = (k) => { skipped[k] = (skipped[k] || 0) + 1; };
    const geometryOnly = !!opts.geometryOnly;

    const styleCache = new Map();
    const styleOf = (el) => {
        let s = styleCache.get(el);
        if (!s) { s = getComputedStyle(el); styleCache.set(el, s); }
        return s;
    };

    /* --- any CSS colour -> sRGB RGBA, via two canvas fills (handles oklab(), color-mix(), ...) */
    const cv = document.createElement('canvas');
    cv.width = 1; cv.height = 1;
    const g2 = cv.getContext('2d', { willReadFrequently: true });
    const colourCache = new Map();
    const paintOn = (css, under) => {
        g2.globalCompositeOperation = 'copy';
        g2.fillStyle = under;
        g2.fillRect(0, 0, 1, 1);
        g2.globalCompositeOperation = 'source-over';
        g2.fillStyle = css;
        g2.fillRect(0, 0, 1, 1);
        return g2.getImageData(0, 0, 1, 1).data;
    };
    const rgba = (css) => {
        if (colourCache.has(css)) return colourCache.get(css);
        let out = null;
        g2.fillStyle = '#010203';
        g2.fillStyle = css;
        const parsed = g2.fillStyle !== '#010203' || /^\s*(#010203|rgb\(\s*1\s*,\s*2\s*,\s*3\s*\))\s*$/i.test(css);
        if (parsed) {
            const k = paintOn(css, '#000000'), w = paintOn(css, '#ffffff');
            const a = Math.max(0, Math.min(1, 1 - ((w[0] - k[0]) + (w[1] - k[1]) + (w[2] - k[2])) / (3 * 255)));
            out = a < 0.002
                ? [0, 0, 0, 0]
                : [Math.min(255, Math.round(k[0] / a)), Math.min(255, Math.round(k[1] / a)), Math.min(255, Math.round(k[2] / a)), Math.round(a * 1000) / 1000];
        }
        colourCache.set(css, out);
        return out;
    };

    /* --- clip-path: inset() and legacy clip: rect(), as boxes in viewport coordinates */
    const lengthPx = (token, basis) => {
        if (/%$/.test(token)) return (parseFloat(token) / 100) * basis;
        const n = parseFloat(token);
        return Number.isFinite(n) ? n : 0;
    };
    const clipShapeBox = (el, s) => {
        const out = [];
        const cp = s.clipPath;
        if (cp && cp !== 'none') {
            const m = /^inset\((.*)\)$/.exec(cp.trim());
            if (m) {
                const r = el.getBoundingClientRect();
                const t = m[1].split(/\s+round\s+/)[0].trim().split(/\s+/);
                const v = t.length === 1 ? [t[0], t[0], t[0], t[0]] : t.length === 2 ? [t[0], t[1], t[0], t[1]] : t.length === 3 ? [t[0], t[1], t[2], t[1]] : t;
                const top = lengthPx(v[0], r.height), right = lengthPx(v[1], r.width), bottom = lengthPx(v[2], r.height), left = lengthPx(v[3], r.width);
                out.push({ l: r.left + left, t: r.top + top, r: r.right - right, b: r.bottom - bottom });
            }
        }
        if ((s.position === 'absolute' || s.position === 'fixed') && s.clip && s.clip !== 'auto') {
            const m = /^rect\((.*)\)$/.exec(s.clip.trim());
            if (m) {
                const r = el.getBoundingClientRect();
                const v = m[1].split(/[\s,]+/).filter(Boolean);
                if (v.length === 4) {
                    const num = (x, d) => (x === 'auto' ? d : parseFloat(x));
                    out.push({ l: r.left + num(v[3], 0), t: r.top + num(v[0], 0), r: r.left + num(v[1], r.width), b: r.top + num(v[2], r.height) });
                }
            }
        }
        return out;
    };

    /* --- per element: hidden? (display/content-visibility/opacity), opacity product, clip shapes */
    const chainCache = new Map();
    const chainOf = (el) => {
        let c = chainCache.get(el);
        if (c) return c;
        const s = styleOf(el);
        const up = el.parentElement ? chainOf(el.parentElement) : { hidden: null, opacity: 1, shapes: [] };
        c = { hidden: up.hidden, opacity: up.opacity, shapes: up.shapes };
        if (!c.hidden) {
            if (s.display === 'none') c.hidden = el.hasAttribute('hidden') ? 'hidden-attr' : 'display';
            else if (s.contentVisibility === 'hidden') c.hidden = 'content-visibility';
        }
        const o = parseFloat(s.opacity);
        if (o === 0) c.hidden = c.hidden || 'opacity';
        else if (o > 0 && o < 1) c.opacity = up.opacity * o;
        const shape = clipShapeBox(el, s);
        if (shape.length) c.shapes = up.shapes.concat(shape);
        chainCache.set(el, c);
        return c;
    };

    /* --- overflow clipping along the CONTAINING-BLOCK chain (not the DOM parent chain) */
    const establishesCB = (s, fixedOnly) => (!fixedOnly && s.position !== 'static')
        || s.transform !== 'none' || s.perspective !== 'none'
        || (s.filter && s.filter !== 'none') || (s.backdropFilter && s.backdropFilter !== 'none')
        || /transform|perspective|filter/.test(s.willChange) || /layout|paint|strict|content/.test(s.contain)
        || (s.containerType && s.containerType !== 'normal');
    const clipsOverflow = (s) => s.display !== 'inline' && s.display !== 'contents'
        && (s.overflowX !== 'visible' || s.overflowY !== 'visible');
    const paddingBox = (el, s) => {
        const r = el.getBoundingClientRect();
        const x = s.overflowX !== 'visible', y = s.overflowY !== 'visible';
        if (el.namespaceURI === SVG_NS) return { l: r.left, t: r.top, r: r.right, b: r.bottom, x, y };
        const ow = el.offsetWidth || r.width, oh = el.offsetHeight || r.height;
        const kx = ow > 0 ? r.width / ow : 1, ky = oh > 0 ? r.height / oh : 1;
        const l = r.left + el.clientLeft * kx, t = r.top + el.clientTop * ky;
        return { l, t, r: l + el.clientWidth * kx, b: t + el.clientHeight * ky, x, y };
    };
    const boxCache = new Map();
    const clipBoxes = (el) => {
        let list = boxCache.get(el);
        if (list) return list;
        list = [];
        let cur = el;
        for (let guard = 0; cur && cur !== body && cur !== docEl && guard < 300; guard++) {
            const s = styleOf(cur);
            if (clipsOverflow(s)) list.push(paddingBox(cur, s));
            let next = cur.parentElement;
            if (s.position === 'absolute') {
                while (next && next !== body && next !== docEl && !establishesCB(styleOf(next), false)) next = next.parentElement;
            } else if (s.position === 'fixed') {
                while (next && next !== body && next !== docEl && !establishesCB(styleOf(next), true)) next = next.parentElement;
            }
            cur = next;
        }
        boxCache.set(el, list);
        return list;
    };
    /** viewport-space rect -> clipped page-space [x, y, w, h], or null when nothing of it shows */
    const clipRect = (q, boxes, shapes) => {
        let l = q.left, t = q.top, r = q.right, b = q.bottom;
        for (const bx of boxes) {
            if (bx.x) { l = Math.max(l, bx.l); r = Math.min(r, bx.r); }
            if (bx.y) { t = Math.max(t, bx.t); b = Math.min(b, bx.b); }
        }
        for (const bx of shapes) { l = Math.max(l, bx.l); r = Math.min(r, bx.r); t = Math.max(t, bx.t); b = Math.min(b, bx.b); }
        l += sx; r += sx; t += sy; b += sy;
        l = Math.max(l, 0); t = Math.max(t, 0); r = Math.min(r, docW); b = Math.min(b, docH);
        return r - l >= 1.5 && b - t >= 2 ? [l, t, r - l, b - t] : null;
    };

    /* --- a short, human CSS path: tag#id.class chain, three levels */
    const part = (el, leaf) => {
        let s = el.localName;
        if (el.id && !/[\s:.]/.test(el.id)) s += `#${el.id.slice(0, 24)}`;
        const cls = (el.getAttribute('class') || '').split(/\s+/)
            .filter((c) => c && c.length <= 26 && !/[[\]:/!@]/.test(c)).slice(0, leaf ? 4 : 3);
        if (cls.length) s += `.${cls.join('.')}`;
        else {
            const data = Array.from(el.attributes).find((a) => a.name.startsWith('data-'));
            if (data) s += `[${data.name}]`;
        }
        return s;
    };
    const cssPath = (el) => {
        const out = [];
        for (let n = el, i = 0; n && n.nodeType === 1 && i < 3; n = n.parentElement, i++) {
            out.unshift(part(n, i === 0));
            if (n === body) break;
        }
        return out.join(' > ');
    };

    const items = [];      // measurable items (colour known)
    const geoItems = [];   // every visible item: what the signatures are built from
    const ignored = (el) => { try { return opts.ignore ? !!el.closest(opts.ignore) : false; } catch { return false; } };
    const fontOf = (s) => ({
        fontSize: parseFloat(s.fontSize) || 16,
        fontWeight: parseInt(s.fontWeight, 10) || (s.fontWeight === 'bold' ? 700 : 400),
    });

    /* --- 1. text nodes */
    const tw = document.createTreeWalker(body, NodeFilter.SHOW_TEXT);
    const range = document.createRange();
    let textNodes = 0;
    for (let node = tw.nextNode(); node; node = tw.nextNode()) {
        const raw = node.nodeValue;
        if (!/[^\s​-‍⁠﻿]/.test(raw)) continue;
        const el = node.parentElement;
        if (!el || SKIP.has(el.localName)) continue;
        textNodes++;
        if (ignored(el)) { bump('ignored'); continue; }
        const ch = chainOf(el);
        const s = styleOf(el);
        if (ch.hidden || s.visibility !== 'visible') { bump('hidden'); continue; }
        range.selectNodeContents(node);
        const qs = Array.from(range.getClientRects()).filter((q) => q.width > 0 && q.height > 0);
        if (!qs.length) { bump('hidden'); continue; }

        const boxes = opts.mutate === 'noClip' ? [] : clipBoxes(el);
        const rects = [];
        for (const q of qs) { const r = clipRect(q, boxes, ch.shapes); if (r) rects.push(r); }
        if (!rects.length) { bump('clipped'); continue; }

        const text = raw.replace(/\s+/g, ' ').trim();
        const base = { kind: el.namespaceURI === SVG_NS ? 'svg' : 'text', path: cssPath(el), text: text.slice(0, 60), rects };
        geoItems.push(base);
        if (geometryOnly) continue;

        if (!text.replace(/\p{Extended_Pictographic}️/gu, '').replace(/[\s​-‍️\p{Emoji_Modifier}\p{Emoji_Presentation}]/gu, '')) {
            unmeasured.push({ reason: 'emoji (painted in its own colours, not the CSS colour)', path: base.path, text: base.text });
            continue;
        }
        const isSvg = el.namespaceURI === SVG_NS;
        const paintCss = isSvg ? s.fill : (s.webkitTextFillColor || s.color);
        if (/^url\(/.test(paintCss)) {
            unmeasured.push({ reason: 'SVG paint server (gradient/pattern fill)', path: base.path, text: base.text });
            continue;
        }
        const col = paintCss === 'none' ? [0, 0, 0, 0] : rgba(paintCss);
        if (!col) { unmeasured.push({ reason: `unparsable colour "${paintCss}"`, path: base.path, text: base.text }); continue; }
        const clipText = s.webkitBackgroundClip === 'text' || s.backgroundClip === 'text';
        if (clipText && col[3] < 0.99) {
            unmeasured.push({ reason: 'gradient text (background-clip:text): paint is the background image', path: base.path, text: base.text });
            continue;
        }
        const fo = isSvg ? parseFloat(s.fillOpacity) : 1;
        const alpha = col[3] * ch.opacity * (Number.isFinite(fo) ? fo : 1);
        if (alpha < 0.004) { bump('transparent'); continue; }
        items.push({
            ...base, color: [col[0], col[1], col[2]], colorAlpha: col[3], opacity: ch.opacity, alpha,
            ...fontOf(s), disabled: !!el.closest(':disabled, [aria-disabled="true"]'),
        });
    }

    /* --- 2. form controls: placeholder, value, select label (not text nodes, so not seen above) */
    const measureW = (text, s) => {
        g2.font = `${s.fontStyle} ${s.fontWeight} ${s.fontSize} ${s.fontFamily}`;
        return g2.measureText(text).width + (parseFloat(s.letterSpacing) || 0) * text.length;
    };
    for (const el of body.querySelectorAll('input, textarea, select')) {
        const tag = el.localName;
        const type = (el.getAttribute('type') || 'text').toLowerCase();
        if (tag === 'input' && /^(hidden|checkbox|radio|range|color|file|image)$/.test(type)) continue;
        if (ignored(el)) { bump('ignored'); continue; }
        const ch = chainOf(el);
        const s = styleOf(el);
        if (ch.hidden || s.visibility !== 'visible') { bump('hidden'); continue; }
        const br = el.getBoundingClientRect();
        if (br.width < 2 || br.height < 2) { bump('hidden'); continue; }

        let kind = null, text = '', pseudo = null;
        if (tag === 'select') {
            if (!el.multiple) { const o = el.selectedOptions && el.selectedOptions[0]; text = o ? (o.label || o.textContent || '') : ''; kind = 'select'; }
        } else if (el.value !== '' && el.value != null) {
            text = type === 'password' ? '•'.repeat(el.value.length) : String(el.value);
            kind = 'value';
        } else if (el.placeholder) {
            text = el.placeholder; kind = 'placeholder'; pseudo = getComputedStyle(el, '::placeholder');
        }
        text = text.replace(/\s+/g, ' ').trim();
        if (!kind || !text) continue;

        const px = (v) => parseFloat(v) || 0;
        const cl = br.left + px(s.borderLeftWidth) + px(s.paddingLeft);
        const ct = br.top + px(s.borderTopWidth) + px(s.paddingTop);
        const cw = Math.max(1, br.width - px(s.borderLeftWidth) - px(s.borderRightWidth) - px(s.paddingLeft) - px(s.paddingRight));
        const chh = Math.max(1, br.height - px(s.borderTopWidth) - px(s.borderBottomWidth) - px(s.paddingTop) - px(s.paddingBottom));
        const fs = fontOf(s);
        const lh = Number.isFinite(parseFloat(s.lineHeight)) ? parseFloat(s.lineHeight) : fs.fontSize * 1.2;
        const tw2 = Math.min(cw, Math.max(2, measureW(text, pseudo || s)));
        const align = /^(submit|button|reset)$/.test(type) ? 'center' : s.textAlign;
        const x0 = align === 'center' ? cl + (cw - tw2) / 2 : (align === 'right' || align === 'end') ? cl + cw - tw2 : cl;
        let y0 = ct, hh = chh;
        if (tag === 'textarea') hh = Math.min(chh, Math.max(1, Math.ceil(measureW(text, s) / cw)) * lh);
        else if (chh > lh * 1.5) { y0 = ct + (chh - lh) / 2; hh = lh; }
        const rect = clipRect({ left: x0, top: y0, right: x0 + tw2, bottom: y0 + hh }, opts.mutate === 'noClip' ? [] : clipBoxes(el), ch.shapes);
        if (!rect) { bump('clipped'); continue; }
        const base = { kind, path: cssPath(el), text: text.slice(0, 60), rects: [rect] };
        geoItems.push(base);
        if (geometryOnly) continue;
        const src = pseudo || s;
        const col = rgba(src.webkitTextFillColor || src.color);
        if (!col) { unmeasured.push({ reason: 'unparsable colour', path: base.path, text: base.text }); continue; }
        const phOpacity = pseudo ? (parseFloat(pseudo.opacity) || 0) : 1;
        const alpha = col[3] * phOpacity * ch.opacity;
        if (alpha < 0.004) { bump('transparent'); continue; }
        items.push({
            ...base, color: [col[0], col[1], col[2]], colorAlpha: col[3], opacity: phOpacity * ch.opacity, alpha,
            ...fs, disabled: !!el.disabled,
        });
    }

    /* --- signatures */
    const fnv = (str) => {
        let h = 0x811c9dc5;
        for (let i = 0; i < str.length; i++) { h ^= str.charCodeAt(i); h = Math.imul(h, 0x01000193) >>> 0; }
        return h.toString(16);
    };
    const geo = geoItems.map((i) => `${i.kind}|${i.text}|${i.rects.map((r) => r.map(Math.round).join(',')).join(';')}`).join('\n');
    const sig = fnv(`${docW}x${docH}\n${geo}`);
    if (opts.sigOnly || geometryOnly) {
        const paint = opts.sigOnly ? fnv(items.map((i) => `${i.color}|${(i.alpha || 0).toFixed(2)}`).join('\n')) : '';
        return JSON.stringify({ n: geoItems.length, docW, docH, sig, paint });
    }

    /* --- 3. pseudo-element text cannot be located (no geometry API): count it honestly */
    const pseudoText = [];
    let pseudoCount = 0;
    for (const el of body.querySelectorAll('*')) {
        if (SKIP.has(el.localName) || el.namespaceURI === SVG_NS || !el.getClientRects().length) continue;
        for (const p of ['::before', '::after']) {
            const c = getComputedStyle(el, p).content;
            if (c && /^["'].+["']$/.test(c) && getComputedStyle(el, p).display !== 'none') {
                pseudoCount++;
                if (pseudoText.length < 5) pseudoText.push(`${cssPath(el)}${p} ${c.slice(0, 24)}`);
            }
        }
    }
    return JSON.stringify({
        n: items.length, docW, docH, sig, scrollX: sx, scrollY: sy, textNodes, items, skipped, unmeasured,
        pseudo: { count: pseudoCount, samples: pseudoText },
        hOverflow: docEl.scrollWidth > docW + 1,
    });
}

/** Runs once per navigation: eager images, all reveal classes, header/progress already via CSS. */
function pagePrepare(opts) {
    document.querySelectorAll('img[loading="lazy"]').forEach((i) => { i.loading = 'eager'; });
    if (opts.reveal !== false) document.querySelectorAll('.reveal, .clip-reveal, .line-mask').forEach((e) => e.classList.add('is-visible'));
    document.querySelectorAll('*').forEach((e) => {
        if (getComputedStyle(e).contentVisibility === 'auto') e.style.setProperty('content-visibility', 'visible', 'important');
    });
    return true;
}

const REVEAL_JS = `document.querySelectorAll('.reveal, .clip-reveal, .line-mask').forEach((e) => e.classList.add('is-visible')); 0`;
const callPage = (fn, arg) => `(${fn.toString()})(${JSON.stringify(arg ?? {})})`;

/* ------------------------------------------------------------ page driver */

async function setViewport(b, vp, o) {
    const { cdp } = b;
    await cdp.send('Emulation.setDeviceMetricsOverride', { width: vp.w, height: vp.h, deviceScaleFactor: 1, mobile: false, screenWidth: vp.w, screenHeight: vp.h });
    // A page with no background of its own must be white in the capture, as it is on screen.
    await cdp.send('Emulation.setDefaultBackgroundColorOverride', { color: { r: 255, g: 255, b: 255, a: 1 } });
    await cdp.send('Emulation.setEmulatedMedia', {
        features: [
            { name: 'prefers-reduced-motion', value: o.reducedMotion ? 'reduce' : 'no-preference' },
            { name: 'prefers-color-scheme', value: 'light' },
        ],
    });
}

async function navigate(b, url) {
    b.mainStatus = null;
    b.inflight.clear();
    const loaded = b.cdp.once('Page.loadEventFired', 30000).catch(() => null);
    const r = await b.cdp.send('Page.navigate', { url });
    if (r.errorText) throw new Error(`navigation to ${url} failed: ${r.errorText}`);
    await loaded;
}

async function waitQuiet(b, quietMs, maxMs) {
    const until = now() + maxMs;
    let since = null;
    while (now() < until) {
        if (b.inflight.size === 0) {
            since ??= now();
            if (now() - since >= quietMs) return true;
        } else since = null;
        await sleep(50);
    }
    return false;
}

const collect = async (b, opts) => JSON.parse(await b.cdp.eval(callPage(pageCollect, opts), 60000));

/**
 * Bring the page to its resting, fully-revealed state and wait until it stops changing.
 * Returns whether it settled.
 */
async function stabilise(b, o, mutate) {
    const { cdp } = b;
    const reveal = mutate !== 'noReveal';
    await cdp.eval(`(() => {
        let s = document.getElementById('__ca_stable');
        if (!s) { s = document.createElement('style'); s.id = '__ca_stable'; s.textContent = ${JSON.stringify(STABILISE_CSS)}; (document.head || document.documentElement).appendChild(s); }
        return 1; })()`);
    await cdp.eval(`(${pagePrepare.toString()})(${JSON.stringify({ reveal })})`);

    // Scroll through in ~500px steps: lazy images and observers fire, widgets that mount
    // on intersection (maps) exist before they are listed. The page may grow while we go.
    let height = await cdp.eval('document.documentElement.scrollHeight');
    for (let y = 0, steps = 0; y <= height && steps < 120; y += 500, steps++) {
        height = Math.max(height, await cdp.eval(`window.scrollTo({ top: ${y}, behavior: 'instant' }); ${reveal ? REVEAL_JS : ''}; document.documentElement.scrollHeight`));
        await sleep(40);
    }
    await waitQuiet(b, 350, 8000);
    // Web fonts: Google Fonts is external and may never answer, so this is bounded.
    await cdp.eval('Promise.race([document.fonts ? document.fonts.ready.then(() => 1) : 1, new Promise((r) => setTimeout(() => r(0), 4000))])', 10000);
    await cdp.eval(`(async () => {
        const t = Date.now();
        while (Date.now() - t < 5000) { if ([...document.images].every((i) => i.complete)) return 1; await new Promise((r) => setTimeout(r, 100)); }
        return 0; })()`, 10000);
    await cdp.eval(`window.scrollTo({ top: 0, behavior: 'instant' }); ${reveal ? REVEAL_JS : ''}; new Promise((r) => requestAnimationFrame(() => requestAnimationFrame(() => setTimeout(r, 60))))`, 10000);

    // Counters, font swaps and anything else still moving: wait for three equal readings.
    let last = null, agree = 0, stable = false;
    const deadline = now() + 6000;
    while (now() < deadline) {
        const r = JSON.parse(await cdp.eval(callPage(pageCollect, { sigOnly: true, ignore: o.ignore }), 20000));
        const key = `${r.sig}:${r.paint}`;
        if (key === last) { if (++agree >= 2) { stable = true; break; } } else { agree = 0; last = key; }
        if (reveal) await cdp.eval(REVEAL_JS);
        await sleep(120);
    }
    return stable;
}

/* ------------------------------------------------- sampling and measurement */

/** Decide, for every text line rect, which background pixels to read (<= SAMPLE_TARGET). */
function buildPlan(items, docW, docH, mutate) {
    const dy = mutate === 'shiftY' ? 14 : 0;
    const rects = [];
    let total = 0;
    for (let ii = 0; ii < items.length; ii++) {
        for (const [x, y, w, h] of items[ii].rects) {
            const n = Math.min(SAMPLE_TARGET, Math.max(1, Math.round(w * h)));
            const nx = Math.max(1, Math.min(Math.ceil(w), Math.round(Math.sqrt((n * w) / h))));
            const ny = Math.max(1, Math.min(Math.ceil(h), Math.round(n / nx)));
            rects.push({ ii, x, y, w, h, nx, ny, start: total, count: nx * ny });
            total += nx * ny;
        }
    }
    const xs = new Int32Array(total), ys = new Int32Array(total);
    for (const r of rects) {
        let k = r.start;
        for (let j = 0; j < r.ny; j++) {
            const py = Math.min(docH - 1, Math.max(0, Math.floor(r.y + ((j + 0.5) * r.h) / r.ny) + dy));
            for (let i = 0; i < r.nx; i++) {
                xs[k] = Math.min(docW - 1, Math.max(0, Math.floor(r.x + ((i + 0.5) * r.w) / r.nx)));
                ys[k] = py;
                k++;
            }
        }
    }
    return { rects, xs, ys, total, rgb: new Uint8Array(total * 3) };
}

/** Read every planned pixel that falls inside this chunk (page rows y0 .. y0+height-1). */
function sampleChunk(plan, img, y0) {
    const { width, height, channels, data } = img;
    for (let k = 0; k < plan.total; k++) {
        const y = plan.ys[k] - y0;
        if (y < 0 || y >= height) continue;
        const o = (y * width + plan.xs[k]) * channels;
        let r, g, b;
        if (channels >= 3) { r = data[o]; g = data[o + 1]; b = data[o + 2]; } else { r = g = b = data[o]; }
        if (channels === 4 || channels === 2) {      // composite any transparency over white
            const a = data[o + channels - 1] / 255;
            if (a < 1) { r = Math.round(r * a + 255 * (1 - a)); g = Math.round(g * a + 255 * (1 - a)); b = Math.round(b * a + 255 * (1 - a)); }
        }
        const t = k * 3;
        plan.rgb[t] = r; plan.rgb[t + 1] = g; plan.rgb[t + 2] = b;
    }
}

const median = (sorted) => sorted[Math.floor((sorted.length - 1) / 2)];
const r3 = (n) => Math.round(n * 1000) / 1000;

/** Samples -> verdicts. The ink is the text colour at its effective alpha over each sample. */
function finishPlan(plan, items, th, mutate) {
    const perItem = items.map(() => null);
    for (const r of plan.rects) {
        const it = items[r.ii];
        const a = mutate === 'ignoreAlpha' ? 1 : it.alpha;
        const [cr, cg, cb] = it.color;
        const n = r.count;
        const ratios = new Float64Array(n);
        const R = new Uint8Array(n), G = new Uint8Array(n), B = new Uint8Array(n);
        let lo = Infinity, hi = -Infinity, loIdx = 0, hiIdx = 0;
        for (let k = 0; k < n; k++) {
            const t = (r.start + k) * 3;
            const pr = plan.rgb[t], pg = plan.rgb[t + 1], pb = plan.rgb[t + 2];
            const lBg = luminance(pr, pg, pb);
            let ir = cr, ig = cg, ib = cb;
            if (a < 1) {
                ir = Math.round(a * cr + (1 - a) * pr); ig = Math.round(a * cg + (1 - a) * pg); ib = Math.round(a * cb + (1 - a) * pb);
            }
            ratios[k] = ratioOf(luminance(ir, ig, ib), lBg);
            R[k] = pr; G[k] = pg; B[k] = pb;
            if (lBg < lo) { lo = lBg; loIdx = k; }
            if (lBg > hi) { hi = lBg; hiIdx = k; }
        }
        const sorted = Float64Array.from(ratios).sort();
        const stat = {
            p10: sorted[Math.floor(0.1 * (n - 1))], min: sorted[0], median: median(sorted), n,
            bgMedian: toHex(median(Uint8Array.from(R).sort()), median(Uint8Array.from(G).sort()), median(Uint8Array.from(B).sort())),
            bgMin: toHex(R[loIdx], G[loIdx], B[loIdx]), bgMax: toHex(R[hiIdx], G[hiIdx], B[hiIdx]),
        };
        const cur = perItem[r.ii];
        if (!cur) perItem[r.ii] = { ...stat, samples: n, lines: 1, worstMin: stat.min };
        else {
            cur.samples += n; cur.lines += 1; cur.worstMin = Math.min(cur.worstMin, stat.min);
            if (stat.p10 < cur.p10) Object.assign(cur, { p10: stat.p10, min: stat.min, median: stat.median, bgMedian: stat.bgMedian, bgMin: stat.bgMin, bgMax: stat.bgMax });
        }
    }
    return items.map((it, i) => {
        const s = perItem[i];
        const large = isLarge(it.fontSize, it.fontWeight);
        const threshold = large ? th.large : th.normal;
        const min = Math.min(s.min, s.worstMin);
        let verdict = s.p10 < threshold ? 'FAIL' : min < threshold ? 'WARN' : 'PASS';
        let note = null;
        if (it.disabled && verdict === 'FAIL') { verdict = 'WARN'; note = 'disabled control (WCAG exempts inactive components)'; }
        return {
            kind: it.kind, path: it.path, text: it.text, at: [Math.round(it.rects[0][0]), Math.round(it.rects[0][1])],
            lines: s.lines, color: toHex(...it.color), alpha: r3(it.alpha), fontSize: r3(it.fontSize), fontWeight: it.fontWeight,
            large, threshold, samples: s.samples, p10: r3(s.p10), min: r3(min), median: r3(s.median),
            bgMedian: s.bgMedian, bgMin: s.bgMin, bgMax: s.bgMax, verdict, note,
        };
    });
}

/** Identical (kind, path, text) pairs collapse to one row carrying the worst reading and a count. */
function dedupe(items) {
    const map = new Map();
    for (const it of items) {
        const key = `${it.kind}\u0000${it.path}\u0000${it.text}`;
        const prev = map.get(key);
        if (!prev) { map.set(key, { ...it, count: 1 }); continue; }
        const count = prev.count + 1;
        if (severity(it) < severity(prev)) Object.assign(prev, it);
        prev.count = count;
    }
    return [...map.values()];
}
const severity = (it) => it.p10 / it.threshold + 1e-6 * (it.min / it.threshold);
const worstFirst = (a, b) => severity(a) - severity(b);

/* -------------------------------------------------------------- one audit */

async function captureChunks(b, docW, docH, onChunk) {
    for (let y0 = 0; y0 < docH; y0 += CHUNK_HEIGHT) {
        const h = Math.min(CHUNK_HEIGHT, docH - y0);
        let shot = null, err = null;
        for (let attempt = 0; attempt < 2 && !shot; attempt++) {
            try {
                shot = await b.cdp.send('Page.captureScreenshot', {
                    format: 'png', fromSurface: true, captureBeyondViewport: true,
                    clip: { x: 0, y: y0, width: docW, height: h, scale: 1 },
                }, 60000);
            } catch (e) { err = e; await sleep(300); }
        }
        if (!shot) throw err;
        const png = Buffer.from(shot.data, 'base64');
        const img = decodePng(png);
        if (img.width !== docW || img.height !== h) {
            throw new Error(`screenshot chunk is ${img.width}x${img.height}, expected ${docW}x${h} (device scale factor not 1?)`);
        }
        onChunk(img, y0, png);
    }
}

/**
 * Measure whatever document is currently loaded (site page or fixture). `load` re-creates
 * the document and is called once per attempt, so a page that changed mid-capture is
 * reloaded from scratch rather than patched.
 */
async function auditDocument(b, vp, o, load, { mutate = null, keepFrames = false, slug = 'page' } = {}) {
    const th = { normal: o.normal, large: o.large };
    let out = null;
    for (let attempt = 1; attempt <= MAX_ATTEMPTS; attempt++) {
        const T = {};
        let t = now();
        const lap = (k) => { T[k] = now() - t; t = now(); };
        await load();
        lap('load');
        const settled = await stabilise(b, o, mutate);
        lap('stabilise');
        const A = await collect(b, { ignore: o.ignore, mutate });
        lap('collect');
        const plan = buildPlan(A.items, A.docW, A.docH, mutate);
        if (mutate !== 'noHide') {
            await b.cdp.eval(`(() => { const s = document.createElement('style'); s.id = '__ca_hide'; s.textContent = ${JSON.stringify(HIDE_TEXT_CSS)}; (document.head || document.documentElement).appendChild(s); return new Promise((r) => requestAnimationFrame(() => requestAnimationFrame(() => setTimeout(r, 30)))); })()`, 10000);
        }
        const frames = [];
        await captureChunks(b, A.docW, A.docH, (img, y0, png) => {
            sampleChunk(plan, img, y0);
            if (keepFrames) frames.push({ img, y0 });
            if (o.saveBg) writeFileSync(`${o.saveBg}/${slug}__${vp.key}__y${String(y0).padStart(5, '0')}.png`, png);
        });
        lap('capture');
        const B = await collect(b, { geometryOnly: true, ignore: o.ignore, mutate });
        const unchanged = A.sig === B.sig;
        const measured = finishPlan(plan, A.items, th, mutate);
        lap('measure');
        out = {
            docW: A.docW, docH: A.docH, attempts: attempt, settled, unstable: !unchanged,
            raw: measured, rawItems: A.items, frames, th,
            textNodes: A.textNodes, skipped: A.skipped, unmeasured: A.unmeasured, pseudo: A.pseudo, hOverflow: A.hOverflow,
            timingsMs: T,
        };
        if (unchanged) break;
    }
    return out;
}

/** Turn the measured rows into the shape the report and the JSON use. */
function summarise(page, vp, status, run) {
    const items = dedupe(run.raw).sort(worstFirst);
    const count = (v) => items.filter((i) => i.verdict === v).length;
    return {
        page, viewport: vp.key, ok: true, status, docWidth: run.docW, docHeight: run.docH,
        attempts: run.attempts, unstable: run.unstable, settled: run.settled,
        counts: {
            items: run.raw.length, distinct: items.length, fail: count('FAIL'), warn: count('WARN'), pass: count('PASS'),
            failOccurrences: run.raw.filter((i) => i.verdict === 'FAIL').length,
            warnOccurrences: run.raw.filter((i) => i.verdict === 'WARN').length,
        },
        skipped: run.skipped, unmeasured: run.unmeasured, pseudoText: run.pseudo, horizontalOverflow: run.hOverflow,
        timingsMs: run.timingsMs, items,
    };
}

/* ------------------------------------------------------------ discovery */

/** Walk every internal page from "/": follow hrefs and redirects, ignore assets and non-HTML. */
async function crawl(base) {
    const origin = new URL(base).origin;
    const found = [];
    const skipped = [];
    const queue = ['/'];
    const seen = new Set(queue);
    const abs = (href, from) => {
        try {
            const u = new URL(href, origin + from);
            return u.origin === origin ? u : null;
        } catch { return null; }
    };
    while (queue.length) {
        const path = queue.shift();
        let res;
        try { res = await fetch(origin + path, { redirect: 'manual', signal: AbortSignal.timeout(20000) }); }
        catch (e) { skipped.push({ path, error: String(e.message ?? e) }); continue; }
        if (res.status >= 300 && res.status < 400) {
            const to = abs(res.headers.get('location') ?? '', path);
            if (to && !seen.has(to.pathname)) { seen.add(to.pathname); queue.push(to.pathname); }
            continue;
        }
        if (res.status !== 200) { skipped.push({ path, status: res.status }); continue; }
        if (!(res.headers.get('content-type') ?? '').includes('html')) continue;
        found.push(path);
        const html = await res.text();
        for (const m of html.matchAll(/\bhref="([^"]+)"/g)) {
            const raw = m[1].replace(/&amp;/g, '&');
            if (/^(mailto:|tel:|data:|javascript:|#)/i.test(raw)) continue;
            const u = abs(raw, path);
            if (!u) continue;
            if (/\.(png|jpe?g|webp|svg|gif|css|js|ico|woff2?|pdf|xml|txt|json|map|zip)$/i.test(u.pathname)) continue;
            if (!seen.has(u.pathname)) { seen.add(u.pathname); queue.push(u.pathname); }
        }
    }
    return { pages: found, skipped };
}

/* ----------------------------------------------------------- orchestration */

const slugOf = (path) => {
    const s = path.replace(/[?#].*$/, '').replace(/^\/+|\/+$/g, '').replace(/[^A-Za-z0-9._-]+/g, '-');
    return s === '' ? 'home' : s;
};

/**
 * Audit every (page, viewport) with `jobs` Chrome instances. Results come back in task
 * order. A browser that dies or hangs is replaced and the task is recorded as an error.
 */
async function runAudit(o, pages, viewports, log) {
    const tasks = [];
    for (const page of pages) for (const vp of viewports) tasks.push({ page, vp });
    const results = new Array(tasks.length);
    let next = 0, done = 0;
    const origin = new URL(o.base).origin;
    if (o.saveBg) mkdirSync(o.saveBg, { recursive: true });

    async function worker(index) {
        let b = null;
        const ensure = async () => {
            if (b && !b.cdp.closed && !b.proc.exited) return b;
            if (b) await stopBrowser(b);
            b = await launchBrowser(o.port + index, `w${index}`);
            b.origin = origin;
            return b;
        };
        try {
            for (;;) {
                const i = next++;
                if (i >= tasks.length) break;
                const { page, vp } = tasks[i];
                const t = now();
                try {
                    const br = await ensure();
                    await setViewport(br, vp, o);
                    const url = origin + page;
                    const run = await withTimeout(
                        auditDocument(br, vp, o, () => navigate(br, url), { slug: slugOf(page) }),
                        150000, `${page} @ ${vp.key}`,
                    );
                    results[i] = summarise(page, vp, br.mainStatus, run);
                    results[i].elapsedMs = now() - t;
                } catch (e) {
                    results[i] = { page, viewport: vp.key, ok: false, error: String(e.message ?? e), elapsedMs: now() - t };
                    if (b) { await stopBrowser(b); b = null; }     // a hung page may have wedged it
                }
                done++;
                log(progressLine(done, tasks.length, results[i]));
            }
        } finally {
            await stopBrowser(b);
        }
    }

    await Promise.all(Array.from({ length: Math.min(o.jobs, tasks.length) }, (_, i) => worker(i)));
    return results;
}

function progressLine(done, total, r) {
    const head = `[${String(done).padStart(String(total).length)}/${total}] ${r.page} @ ${r.viewport}`;
    if (!r.ok) return `${head}  ERROR ${r.error}`;
    const c = r.counts;
    const flags = [r.unstable ? 'UNSTABLE' : '', r.attempts > 1 ? `${r.attempts} attempts` : '', r.status && r.status !== 200 ? `HTTP ${r.status}` : ''].filter(Boolean).join(', ');
    return `${head}  ${c.items} items, ${c.fail} fail, ${c.warn} warn  (${(r.elapsedMs / 1000).toFixed(1)}s)${flags ? `  [${flags}]` : ''}`;
}

/* ------------------------------------------------------------------ report */

function makeStyler(color) {
    const c = (code) => (s) => (color ? `\x1b[${code}m${s}\x1b[0m` : String(s));
    return { bold: c('1'), red: c('31'), yellow: c('33'), green: c('32'), dim: c('90'), cyan: c('36') };
}

/** 4.50 would hide a 4.496 that fails: show the third decimal exactly when it matters. */
const fmtRatio = (r, thr) => {
    const two = r.toFixed(2);
    return r < thr && two === thr.toFixed(2) ? r.toFixed(3) : two;
};

function failureBlock(it, n, st) {
    const alpha = it.alpha < 0.999 ? ` @${Math.round(it.alpha * 100)}%` : '';
    const spread = it.bgMin !== it.bgMax ? `, varies ${it.bgMin}..${it.bgMax}` : '';
    const tag = it.kind === 'text' ? '' : ` [${it.kind}]`;
    const times = it.count > 1 ? ` x${it.count}` : '';
    const tone = it.verdict === 'FAIL' ? st.red : st.yellow;
    return [
        `  ${String(n).padStart(2)}. ${tone(`${it.verdict} ${fmtRatio(it.p10, it.threshold)}:1`)} < ${it.threshold.toFixed(1)}:1 (p10, worst pixel ${fmtRatio(it.min, it.threshold)}:1)${times}${tag}`,
        `      text ${it.color}${alpha} on bg ${it.bgMedian}${spread}   ${it.fontSize}px/${it.fontWeight}${it.large ? ' large' : ''}   at ${it.at[0]},${it.at[1]}${it.lines > 1 ? `  (${it.lines} lines)` : ''}`,
        `      ${st.dim(it.path)}`,
        `      "${it.text}"${it.note ? `  ${st.dim(`(${it.note})`)}` : ''}`,
    ].join('\n');
}

function renderReport(meta, results, o) {
    const st = makeStyler(o.color);
    const L = [];
    const ok = results.filter((r) => r.ok);
    L.push(st.bold(`Contrast audit  ${meta.base}   ${meta.pages} page(s) x ${meta.viewports.join(', ')}   ${new Date(meta.startedAt).toISOString().slice(0, 19)}Z`));
    L.push(st.dim(`rendered text vs painted background; normal text >= ${o.normal}:1, large (>=24px, or >=18.66px bold) >= ${o.large}:1; verdict on the 10th-percentile ratio of <= ${SAMPLE_TARGET} pixels per line`));
    L.push('');

    for (const r of results) {
        if (!r.ok) {
            L.push(st.red(st.bold(`== ${r.page} @ ${r.viewport}  ERROR: ${r.error}`)));
            L.push('');
            continue;
        }
        const c = r.counts;
        const notRendered = Object.entries(r.skipped).map(([k, v]) => `${k} ${v}`).join(', ');
        const headline = `== ${r.page} @ ${r.viewport}   ${c.items} text items, ${c.fail} FAIL, ${c.warn} WARN`;
        L.push(c.fail ? st.red(st.bold(headline)) : c.warn ? st.yellow(st.bold(headline)) : st.green(st.bold(headline)));
        const notes = [];
        if (r.status && r.status !== 200) notes.push(`HTTP ${r.status}`);
        if (r.unstable) notes.push('UNSTABLE: text moved during capture on every attempt, results may mix two states');
        else if (r.attempts > 1) notes.push(`re-measured ${r.attempts} times because the page changed during capture`);
        if (!r.settled) notes.push('page never stopped changing within 6s (measured anyway)');
        if (r.horizontalOverflow) notes.push('horizontal overflow: content wider than the viewport');
        if (notRendered) notes.push(`not rendered/measured: ${notRendered}`);
        if (r.unmeasured.length) notes.push(`${r.unmeasured.length} item(s) could not be measured (${[...new Set(r.unmeasured.map((u) => u.reason.split(' ')[0]))].join(', ')})`);
        if (r.pseudoText.count) notes.push(`${r.pseudoText.count} pseudo-element text run(s) have no geometry and are not audited`);
        for (const n of notes) L.push(st.dim(`   ${n}`));
        const listed = r.items.filter((i) => i.verdict === 'FAIL' || (o.showWarns && i.verdict === 'WARN'));
        listed.forEach((it, i) => L.push(failureBlock(it, i + 1, st)));
        if (!o.showWarns && c.warn) L.push(st.dim(`   (${c.warn} WARN not listed; --show-warns lists them)`));
        L.push('');
    }

    // summary table
    const rows = results.map((r) => (r.ok
        ? [r.page, r.viewport, String(r.counts.items), String(r.counts.fail), String(r.counts.warn), String(r.counts.pass),
            String(Object.values(r.skipped).reduce((a, b) => a + b, 0) + r.unmeasured.length), r.unstable ? 'UNSTABLE' : (r.status && r.status !== 200 ? `HTTP ${r.status}` : '')]
        : [r.page, r.viewport, '-', '-', '-', '-', '-', 'ERROR']));
    const head = ['page', 'viewport', 'items', 'fail', 'warn', 'pass', 'skipped', 'note'];
    const widths = head.map((h, i) => Math.max(h.length, ...rows.map((r) => r[i].length)));
    const fmt = (r) => r.map((cell, i) => (i < 2 || i === 7 ? cell.padEnd(widths[i]) : cell.padStart(widths[i]))).join('  ');
    L.push(st.bold('SUMMARY'));
    L.push(`  ${st.bold(fmt(head))}`);
    for (const r of rows) {
        const line = `  ${fmt(r)}`;
        L.push(r[3] !== '0' && r[3] !== '-' ? st.red(line) : r[7] === 'ERROR' ? st.red(line) : r[4] !== '0' ? st.yellow(line) : line);
    }
    const sum = (k) => ok.reduce((a, r) => a + r.counts[k], 0);
    L.push(`  ${st.bold(fmt(['TOTAL', `${ok.length}/${results.length} runs`, String(sum('items')), String(sum('fail')), String(sum('warn')), String(sum('pass')), '', '']))}`);

    // the same offender on many pages (footer, header) is one problem, not eighty
    if (ok.length > 2 && sum('fail') > 0) {
        const groups = new Map();
        for (const r of ok) for (const it of r.items) {
            if (it.verdict !== 'FAIL') continue;
            const key = `${it.path}\u0000${it.text}`;
            const g = groups.get(key) ?? { it, runs: 0, pages: new Set() };
            g.runs++; g.pages.add(r.page);
            if (severity(it) < severity(g.it)) g.it = it;
            groups.set(key, g);
        }
        const recurring = [...groups.values()].filter((g) => g.pages.size > 1).sort((a, b) => b.pages.size - a.pages.size || worstFirst(a.it, b.it));
        if (recurring.length) {
            L.push('');
            L.push(st.bold(`RECURRING FAILURES  (same element and text on more than one page; fix once)`));
            for (const g of recurring.slice(0, 25)) {
                L.push(`  ${String(g.pages.size).padStart(3)} pages  ${fmtRatio(g.it.p10, g.it.threshold)}:1 < ${g.it.threshold.toFixed(1)}  ${g.it.color} on ${g.it.bgMedian}  "${g.it.text}"`);
                L.push(`             ${st.dim(g.it.path)}`);
            }
            if (recurring.length > 25) L.push(st.dim(`  ... and ${recurring.length - 25} more (see --json)`));
        }
    }
    L.push('');
    const bad = results.filter((r) => !r.ok).length;
    const verdict = sum('fail') ? st.red(st.bold(`FAIL: ${sum('fail')} failing text item(s) across ${ok.filter((r) => r.counts.fail).length} page/viewport run(s)`))
        : bad ? st.red(st.bold(`INCOMPLETE: ${bad} run(s) errored; no contrast failures in the rest`))
            : st.green(st.bold(`PASS: no contrast failures in ${ok.length} run(s)`));
    L.push(verdict + st.dim(`   (${((now() - T0) / 1000).toFixed(1)}s)`));
    return L.join('\n');
}

function jsonResult(meta, results, o) {
    return {
        tool: 'contrast-audit', version: 1, ...meta,
        thresholds: { normal: o.normal, large: o.large, largeFontPx: LARGE_PX, largeBoldFontPx: LARGE_BOLD_PX, p10: true, samplesPerLine: SAMPLE_TARGET },
        reducedMotionEmulated: o.reducedMotion,
        totals: {
            runs: results.length, errors: results.filter((r) => !r.ok).length,
            items: results.reduce((a, r) => a + (r.ok ? r.counts.items : 0), 0),
            fail: results.reduce((a, r) => a + (r.ok ? r.counts.fail : 0), 0),
            warn: results.reduce((a, r) => a + (r.ok ? r.counts.warn : 0), 0),
        },
        results,
    };
}

/* ---------------------------------------------------------------- self-test */

const svgBg = (svg) => `url("data:image/svg+xml,${encodeURIComponent(svg)}")`;
const SVG_HALF = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 2 1' preserveAspectRatio='none'><rect width='1' height='1' fill='#ffffff'/><rect x='1' width='1' height='1' fill='#0b2415'/></svg>";
const SVG_LIGHT = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1' preserveAspectRatio='none'><rect width='1' height='1' fill='#f4fff4'/></svg>";
const SVG_NOISE = "<svg xmlns='http://www.w3.org/2000/svg' width='320' height='60'><filter id='n' x='0' y='0' width='100%' height='100%'><feTurbulence type='fractalNoise' baseFrequency='0.09' numOctaves='3' seed='7'/><feColorMatrix type='saturate' values='0'/></filter><rect width='320' height='60' filter='url(#n)'/></svg>";

/**
 * Every case: the HTML to render and what a correct auditor must say about it. Decoys are
 * deliberate: wherever a naive checker (CSS colours only, ignore alpha, ignore overlays,
 * count hidden text) would reach the opposite verdict, that is the case being tested.
 */
function fixtureCases() {
    const C = [];
    const row = (inner, style = '') => `<div class="row" style="${style}">${inner}</div>`;
    const add = (id, title, html, expect, rowStyle = '') => C.push({ id, title, html: rowStyle === null ? html : row(html, rowStyle), expect });
    const span = (label, style = '') => `<span style="${style}">${label}</span>`;
    const inp = (cls, ph, extra = '') => `<input class="${cls}" placeholder="${ph}" style="width:300px;box-sizing:border-box;padding:6px 8px;border:1px solid #ccc;background:#fff;color:#000;font:16px Arial;${extra}">`;
    const tall = 'height:68px;padding-top:12px';

    /* --- the seven required checks */
    add('S1', '(i) #88E788 text on white must FAIL (about 1.5:1)', span('S1 #88E788 on white', 'color:#88E788'),
        { label: 'S1 #88E788 on white', verdict: 'FAIL', ratio: [1.50, 1.54], threshold: 4.5, bg: '#ffffff', uniform: true });
    add('S2', '(ii) #153415 on #88E788 must PASS', span('S2 #153415 on #88E788', 'color:#153415'),
        { label: 'S2 #153415 on #88E788', verdict: 'PASS', ratio: [8.8, 9.3], bg: '#88e788', uniform: true }, 'background:#88E788');
    add('S3', '(iii) 16px #777777 on white must FAIL at 4.48:1 (not rounded up to 4.5)', span('S3 #777777 16px on white', 'color:#777777'),
        { label: 'S3 #777777 16px on white', verdict: 'FAIL', ratio: [4.47, 4.49], threshold: 4.5, large: false });
    add('S4', '(iv) the same colour at 24px must PASS as large text (3.0:1)', span('S4 #777777 24px on white', 'color:#777777;font-size:24px;line-height:30px'),
        { label: 'S4 #777777 24px on white', verdict: 'PASS', ratio: [4.47, 4.49], threshold: 3, large: true });
    add('S5', '(v) white over a #0b2415 to #88E788 gradient must FAIL or WARN at the light end',
        span('S5 white text over gradient dark to light', 'display:inline-block;color:#fff;background:linear-gradient(90deg,#0b2415,#88E788)'),
        { label: 'S5 white text over gradient', verdict: ['FAIL', 'WARN'], worst: [1.4, 1.8], bgMinNear: '#0b2415', bgMaxNear: '#88e788' });
    add('S6a', '(vi) dark text over an SVG data-URI image: CSS says white, the IMAGE is dark on one half',
        span('S6a dark text over svg half dark', `display:inline-block;color:#153415;background-color:#ffffff;background-image:${svgBg(SVG_HALF)};background-size:100% 100%`),
        { label: 'S6a dark text over svg', verdict: 'FAIL', worst: [1.1, 1.35], bgMinNear: '#0b2415', bgMaxNear: '#ffffff' });
    add('S6b', '(vi) the inverse decoy: CSS says dark, the IMAGE is light, so dark text PASSES',
        span('S6b dark text over light svg', `display:inline-block;color:#153415;background-color:#0b2415;background-image:${svgBg(SVG_LIGHT)};background-size:100% 100%`),
        { label: 'S6b dark text over light svg', verdict: 'PASS', bg: '#f4fff4', uniform: true });
    add('S7a', '(vii) placeholder #aaaaaa on white must FAIL (input colour is black: the PLACEHOLDER colour counts)',
        inp('ph-aaa', 'S7a placeholder #aaaaaa'), { label: 'S7a placeholder #aaaaaa', kind: 'placeholder', verdict: 'FAIL', ratio: [2.30, 2.36] }, tall);
    add('S7b', '(vii) placeholder #595959 on white must PASS (7.0:1)',
        inp('ph-595959', 'S7b placeholder #595959'), { label: 'S7b placeholder #595959', kind: 'placeholder', verdict: 'PASS', ratio: [6.9, 7.1] }, tall);
    add('S7c', '(vii) placeholder #595959 at opacity .5 is really #acacac: must FAIL',
        inp('ph-595959 ph-half', 'S7c placeholder #595959 half'), { label: 'S7c placeholder #595959 half', kind: 'placeholder', verdict: 'FAIL', ratio: [2.2, 2.35] }, tall);
    add('S7d', '(vii) placeholder #767676 on #f5f5f5 must FAIL (4.16:1) although it passes on white',
        `<textarea class="ph-767676" rows="1" placeholder="S7d placeholder #767676 on f5f5f5" style="width:300px;box-sizing:border-box;padding:6px 8px;border:1px solid #ccc;background:#f5f5f5;font:16px Arial"></textarea>`,
        { label: 'S7d placeholder #767676', kind: 'placeholder', verdict: 'FAIL', ratio: [4.1, 4.22], bg: '#f5f5f5', uniform: true }, tall);
    add('S7e', '(vii) placeholder #767676 on white must PASS (4.54:1)',
        inp('ph-767676', 'S7e placeholder #767676 on white'), { label: 'S7e placeholder #767676', kind: 'placeholder', verdict: 'PASS', ratio: [4.5, 4.6] }, tall);

    /* --- colour that a naive checker mis-reads */
    add('A1', 'translucent text rgba(0,0,0,.45) is really #8c8c8c: must FAIL at 16px', span('A1 rgba black 45 percent', 'color:rgba(0,0,0,.45)'),
        { label: 'A1 rgba black 45 percent', verdict: 'FAIL', ratio: [3.3, 3.42] });
    add('A2', 'the same translucent text at 24px passes as large (3.36 >= 3.0)', span('A2 rgba black 45 percent large', 'color:rgba(0,0,0,.45);font-size:24px;line-height:30px'),
        { label: 'A2 rgba black 45 percent large', verdict: 'PASS', ratio: [3.3, 3.42], threshold: 3 });
    add('A3', 'modern syntax rgb(0 0 0 / 55%) must be parsed: 4.74:1 PASS', span('A3 rgb slash 55 percent', 'color:rgb(0 0 0 / 55%)'),
        { label: 'A3 rgb slash 55 percent', verdict: 'PASS', ratio: [4.6, 4.85] });
    add('A4', 'color-mix(in oklab, #000 40%, transparent) must be parsed: 2.85:1 FAIL', span('A4 color-mix 40 percent', 'color:color-mix(in oklab,#000 40%,transparent)'),
        { label: 'A4 color-mix 40 percent', verdict: 'FAIL', ratio: [2.8, 2.9] });
    add('A5a', 'oklch(0.75 0 0) is about #aeaeae: FAIL', span('A5a oklch 0.75 grey', 'color:oklch(0.75 0 0)'),
        { label: 'A5a oklch 0.75 grey', verdict: 'FAIL', ratio: [2.15, 2.3] });
    add('A5b', 'oklch(0.45 0 0) is about #555555: PASS', span('A5b oklch 0.45 grey', 'color:oklch(0.45 0 0)'),
        { label: 'A5b oklch 0.45 grey', verdict: 'PASS', ratio: [7.2, 7.7] });
    add('A6', 'black text inside an ancestor at opacity .4 is really #999999: must FAIL',
        `<div style="opacity:.4">${span('A6 black under opacity point four', 'color:#000')}</div>`,
        { label: 'A6 black under opacity point four', verdict: 'FAIL', ratio: [2.8, 2.9] });

    /* --- things painted between the background and the text */
    add('A7a', 'white text over white + 60% black overlay is #666666: PASS (5.7:1)',
        `<div style="position:relative;display:inline-block;background:#fff;padding:0 10px"><div style="position:absolute;inset:0;background:rgba(0,0,0,.6)"></div>${span('A7a white over 60 percent overlay', 'position:relative;color:#fff')}</div>`,
        { label: 'A7a white over 60 percent overlay', verdict: 'PASS', ratio: [5.6, 5.9], bg: '#666666', uniform: true });
    add('A7b', 'white text over white + 20% black overlay is #cccccc: FAIL (1.6:1)',
        `<div style="position:relative;display:inline-block;background:#fff;padding:0 10px"><div style="position:absolute;inset:0;background:rgba(0,0,0,.2)"></div>${span('A7b white over 20 percent overlay', 'position:relative;color:#fff')}</div>`,
        { label: 'A7b white over 20 percent overlay', verdict: 'FAIL', ratio: [1.55, 1.66], bg: '#cccccc', uniform: true });
    add('A13', 'grey text on a photo-like noise image must FAIL',
        span('A13 grey text on noise texture', `display:inline-block;color:#808080;font-weight:700;padding:12px 10px;background-image:${svgBg(SVG_NOISE)}`),
        { label: 'A13 grey text on noise texture', verdict: 'FAIL', ratio: [1.0, 2.5] }, 'height:84px');

    /* --- the large-text boundary */
    add('A8a', '23px normal #777777 is NOT large: FAIL at 4.5', span('A8a 23px normal', 'color:#777;font-size:23px;line-height:28px'),
        { label: 'A8a 23px normal', verdict: 'FAIL', threshold: 4.5, large: false });
    add('A8b', '19px bold #777777 IS large (>= 18.66px bold): PASS at 3.0', span('A8b 19px bold', 'color:#777;font-size:19px;font-weight:700'),
        { label: 'A8b 19px bold', verdict: 'PASS', threshold: 3, large: true });
    add('A8c', '18px bold #777777 is NOT large: FAIL', span('A8c 18px bold', 'color:#777;font-size:18px;font-weight:700'),
        { label: 'A8c 18px bold', verdict: 'FAIL', threshold: 4.5, large: false });
    add('A8d', '19px weight 600 is NOT bold enough: FAIL', span('A8d 19px semibold', 'color:#777;font-size:19px;font-weight:600'),
        { label: 'A8d 19px semibold', verdict: 'FAIL', threshold: 4.5, large: false });
    add('A8e', '18.66px bold is exactly on the large boundary: PASS', span('A8e 18.66px bold', 'color:#777;font-size:18.66px;font-weight:700'),
        { label: 'A8e 18.66px bold', verdict: 'PASS', threshold: 3, large: true });

    /* --- the sampling policy: WARN vs FAIL, and one verdict per LINE */
    const stripe = (label, pct) => `<div style="display:inline-block;width:20ch;font:20px/28px monospace;color:#fff;background:linear-gradient(90deg,#0b2415 0,#0b2415 ${pct}%,#ffffff ${pct}%)">${label}</div>`;
    add('A9a', '1 of 20 characters sits on white (5% of the pixels): p10 passes, the worst pixel does not: WARN',
        stripe('A9a-WARN-stripe-1/20', 95), { label: 'A9a-WARN-stripe-1/20', verdict: 'WARN', worst: [1.0, 1.1] }, tall);
    add('A9b', '3 of 20 characters sit on white (15%): p10 fails: FAIL',
        stripe('A9b-FAIL-stripe-3/20', 85), { label: 'A9b-FAIL-stripe-3/20', verdict: 'FAIL' }, tall);
    const words = Array.from({ length: 12 }, (_, i) => `A10-L${String(i + 1).padStart(2, '0')}`).join(' ');
    add('A10', 'a 12-line paragraph whose LAST line sits on a black bar: the bar line fails even though 92% of pixels are fine',
        `<div style="position:relative;isolation:isolate;width:70px;font:14px/20px Arial;color:#000;background:#fff">${words}<div style="position:absolute;z-index:-1;left:0;right:0;bottom:0;height:20px;background:#000"></div></div>`,
        { label: 'A10-L01 A10-L02', verdict: 'FAIL', ratio: [1.0, 1.1] }, 'height:262px');

    /* --- text that is not visible must never be reported */
    add('A11a', 'first line of an overflow:hidden box is visible', `<div style="height:24px;overflow:hidden">${span('A11a visible first line', 'display:block')}${span('A11b clipped second line', 'display:block;color:#bbb')}</div>`,
        { label: 'A11a visible first line', verdict: 'PASS' });
    C.push({ id: 'A11b', title: 'second line of that box is clipped away: must be absent', html: '', expect: { label: 'A11b clipped second line', verdict: 'ABSENT' } });
    add('A11c', 'a scroll container shows its first paragraph only',
        `<div style="height:26px;overflow:auto;line-height:20px"><div>A11c inside scroller view</div><div style="margin-top:80px;color:#ddd">A11d far below inside scroller</div></div>`,
        { label: 'A11c inside scroller view', verdict: 'PASS' });
    C.push({ id: 'A11d', title: 'text scrolled out of a scroller is absent', html: '', expect: { label: 'A11d far below inside scroller', verdict: 'ABSENT' } });
    add('A11e', 'a long nowrap line is clipped to its 120px box: measured over the visible part only',
        `<div style="width:120px;overflow:hidden;white-space:nowrap;color:#000">A11e long line running far beyond the box edge so most of it is clipped</div>`,
        { label: 'A11e long line running', verdict: 'PASS', maxWidth: 121 });
    const W = 'color:#fff'; // white on the white row: every one of these would FAIL if it were counted
    add('A12', 'twelve ways of not being rendered (all white on white; any of them reported is a false FAIL)',
        [
            `<div style="display:none;${W}">A12a display none</div>`,
            `<div style="visibility:hidden;${W}">A12b visibility hidden</div>`,
            `<div style="opacity:0;${W}">A12c opacity zero</div>`,
            `<div hidden style="${W}">A12d hidden attribute</div>`,
            `<div style="font-size:0;${W}">A12e font size zero</div>`,
            `<div style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);${W}">A12f screen reader only</div>`,
            `<div style="text-indent:-9999px;white-space:nowrap;${W}">A12g negative indent</div>`,
            `<div style="position:absolute;left:-9999px;${W}">A12h far off canvas</div>`,
            `<div style="transform:scale(0);${W}">A12i scale zero</div>`,
            `<div style="clip-path:inset(100%);${W}">A12j clip path inset</div>`,
            `<div style="color:transparent">A12k transparent colour</div>`,
            `<details><summary style="color:#000">A20a closed details summary</summary><p style="${W}">A12m body of closed details</p></details>`,
        ].join(''),
        { label: 'A12', verdict: 'ABSENT' }, 'height:130px');
    for (const [id, text] of [['A12a', 'display none'], ['A12b', 'visibility hidden'], ['A12c', 'opacity zero'], ['A12d', 'hidden attribute'], ['A12e', 'font size zero'],
        ['A12f', 'screen reader only'], ['A12g', 'negative indent'], ['A12h', 'far off canvas'], ['A12i', 'scale zero'], ['A12j', 'clip path inset'],
        ['A12k', 'transparent colour'], ['A12m', 'body of closed details']]) {
        C.push({ id, title: `${text}: absent`, html: '', expect: { label: `${id} ${text}`, verdict: 'ABSENT' } });
    }
    C.splice(C.findIndex((c) => c.id === 'A12'), 1);       // the umbrella row is only the HTML carrier, re-added below
    C.push({ id: 'A20a', title: 'summary of a closed <details> is visible and dark: PASS', html: '', expect: { label: 'A20a closed details summary', verdict: 'PASS' } });

    /* --- things only a real render knows */
    add('A14a', 'a .reveal element is made visible by the stabiliser (opacity 0 + transform until .is-visible)',
        '<div class="reveal" style="color:#000">A14a reveal block</div>', { label: 'A14a reveal block', verdict: 'PASS' });
    add('A14b', 'a .line-mask span is revealed (translateY(105%) behind overflow hidden until .is-visible)',
        '<div class="line-mask" style="color:#000"><span>A14b line mask span</span></div>', { label: 'A14b line mask span', verdict: 'PASS' });
    add('A17a', 'an input VALUE counts: #bbbbbb on white FAIL',
        `<input value="A17a value bbb" style="width:300px;box-sizing:border-box;padding:6px 8px;border:1px solid #ccc;background:#fff;color:#bbbbbb;font:16px Arial">`,
        { label: 'A17a value bbb', kind: 'value', verdict: 'FAIL', ratio: [1.85, 2.0] }, tall);
    add('A17b', 'a select label counts: #bbbbbb on white FAIL',
        `<select style="width:300px;padding:6px 8px;border:1px solid #ccc;background:#fff;color:#bbbbbb;font:16px Arial"><option>A17b select label bbb</option></select>`,
        { label: 'A17b select label bbb', kind: 'select', verdict: 'FAIL', ratio: [1.85, 2.0] }, tall);
    add('A17c', 'a submit button value counts: white on #88E788 FAIL (1.52:1)',
        `<input type="submit" value="A17c button white on green" style="background:#88E788;color:#fff;border:0;padding:6px 10px;font:16px Arial">`,
        { label: 'A17c button white on green', kind: 'value', verdict: 'FAIL', ratio: [1.48, 1.56] }, tall);
    add('A18a', 'SVG <text> uses fill: #aaaaaa on white FAIL',
        '<svg width="320" height="28"><text x="4" y="20" font-size="16" font-family="Arial" fill="#aaaaaa">A18a svg text aaa</text></svg>',
        { label: 'A18a svg text aaa', kind: 'svg', verdict: 'FAIL', ratio: [2.28, 2.38] });
    add('A18b', 'SVG <text> fill #333333 on white PASS',
        '<svg width="320" height="28"><text x="4" y="20" font-size="16" font-family="Arial" fill="#333333">A18b svg text 333</text></svg>',
        { label: 'A18b svg text 333', kind: 'svg', verdict: 'PASS' });
    add('A19', 'gradient text (background-clip:text) has no flat colour: reported as UNMEASURED, never silently passed',
        span('A19 gradient clipped text', 'background:linear-gradient(90deg,#0b2415,#88e788);-webkit-background-clip:text;background-clip:text;color:transparent;font-weight:700'),
        { label: 'A19 gradient clipped text', verdict: 'UNMEASURED' });
    add('A21', 'a colour emoji is painted in its own colours: UNMEASURED', span('\u{1F600}', 'color:#fff'), { label: '\u{1F600}', verdict: 'UNMEASURED' });
    add('A23', 'a disabled control is WCAG-exempt: downgraded to WARN, never FAIL',
        `<button disabled style="color:#bbbbbb;background:#fff;border:1px solid #ccc;font:16px Arial">A23 disabled button grey</button>`,
        { label: 'A23 disabled button grey', verdict: 'WARN' });

    /* --- geometry: fixed elements and the 6000px screenshot seams */
    add('A15', 'a position:fixed bar (white on #0b2415) is captured at its scroll-0 position: PASS',
        '<div style="position:fixed;right:0;bottom:0;width:330px;height:32px;line-height:32px;padding-left:8px;box-sizing:border-box;background:#0b2415;color:#fff;font:14px/32px Arial">A15 fixed bar white on dark</div>',
        { label: 'A15 fixed bar white on dark', verdict: 'PASS', bg: '#0b2415', uniform: true }, null);
    const seam = (id, top, label) => add(id, `${label}: a 20px band under 18px text, exact rows, uniform PASS`,
        `<div class="abs" style="top:${top}px">${id} ${label}</div>`,
        { label: `${id} ${label}`, verdict: 'PASS', bg: '#0b2415', uniform: true }, null);
    seam('A16a', 5990, 'seam at 6000');
    seam('A16b', 11990, 'seam at 12000');
    seam('A16c', 13278, 'last rows of the page');
    return C;
}

function buildFixture() {
    const cases = fixtureCases();
    const labels = cases.filter((c) => c.expect.verdict !== 'ABSENT' || true).map((c) => c.expect.label);
    for (const a of labels) for (const b of labels) {
        if (a !== b && b.startsWith(a)) throw new Error(`fixture labels must not be prefixes of each other: "${a}" / "${b}"`);
    }
    const css = `
html,body{margin:0;background:#fff}
body{position:relative;width:800px;height:13300px;font:16px/24px Arial,Helvetica,sans-serif;color:#000}
.row{position:relative;box-sizing:border-box;height:56px;padding:16px 12px;background:#fff}
.abs{position:absolute;left:0;width:520px;height:20px;line-height:20px;padding-left:8px;box-sizing:border-box;background:#0b2415;color:#fff;font-size:16px}
.reveal{opacity:0;transform:translateY(24px);transition:opacity .7s,transform .7s}
.reveal.is-visible{opacity:1;transform:none}
.line-mask{display:block;overflow:hidden}
.line-mask>span{display:block;transform:translateY(105%);transition:transform .9s}
.line-mask.is-visible>span{transform:none}
.ph-aaa::placeholder{color:#aaaaaa}
.ph-595959::placeholder{color:#595959}
.ph-767676::placeholder{color:#767676}
.ph-half::placeholder{opacity:.5}`;
    const flow = cases.filter((c) => c.html && !/class="abs"/.test(c.html) && !/position:fixed/.test(c.html)).map((c) => c.html).join('\n');
    const abs = cases.filter((c) => /class="abs"|position:fixed/.test(c.html)).map((c) => c.html).join('\n');
    // the A12 carrier is one row holding every not-rendered variant
    const html = `<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=800"><title>contrast-audit fixture</title><style>${css}</style></head><body>\n${flow}\n${abs}\n</body></html>`;
    return { html, cases };
}

/** One expectation against one measured run. */
function judge(e, run) {
    const hit = run.raw.find((i) => i.text.startsWith(e.label) && (!e.kind || i.kind === e.kind));
    const unm = run.unmeasured.find((u) => u.text.startsWith(e.label));
    const near = (a, b, tol) => parseHex(a).every((v, k) => Math.abs(v - parseHex(b)[k]) <= tol);
    if (e.verdict === 'ABSENT') {
        if (hit) return { ok: false, got: `reported as ${hit.verdict} ${hit.p10}:1` };
        if (unm) return { ok: false, got: `listed as unmeasured (${unm.reason})` };
        return { ok: true, got: 'not reported' };
    }
    if (e.verdict === 'UNMEASURED') {
        if (hit) return { ok: false, got: `measured as ${hit.verdict} ${hit.p10}:1` };
        return unm ? { ok: true, got: `unmeasured: ${unm.reason.split(' (')[0]}` } : { ok: false, got: 'item missing entirely' };
    }
    if (!hit) return { ok: false, got: `item not found${unm ? ` (unmeasured: ${unm.reason})` : ''}` };
    const want = Array.isArray(e.verdict) ? e.verdict : [e.verdict];
    const bad = [];
    if (!want.includes(hit.verdict)) bad.push(`verdict ${hit.verdict}, want ${want.join(' or ')}`);
    if (e.ratio && !(hit.p10 >= e.ratio[0] && hit.p10 <= e.ratio[1])) bad.push(`p10 ${hit.p10}, want ${e.ratio[0]}..${e.ratio[1]}`);
    if (e.worst && !(hit.min >= e.worst[0] && hit.min <= e.worst[1])) bad.push(`worst ${hit.min}, want ${e.worst[0]}..${e.worst[1]}`);
    if (e.threshold !== undefined && hit.threshold !== e.threshold) bad.push(`threshold ${hit.threshold}, want ${e.threshold}`);
    if (e.large !== undefined && hit.large !== e.large) bad.push(`large=${hit.large}, want ${e.large}`);
    if (e.bg && hit.bgMedian !== e.bg) bad.push(`bg ${hit.bgMedian}, want ${e.bg}`);
    if (e.uniform && !(hit.bgMin === hit.bgMax && hit.bgMin === hit.bgMedian)) bad.push(`background not uniform (${hit.bgMin}..${hit.bgMax})`);
    if (e.bgMinNear && !near(hit.bgMin, e.bgMinNear, 14)) bad.push(`darkest background ${hit.bgMin}, want about ${e.bgMinNear}`);
    if (e.bgMaxNear && !near(hit.bgMax, e.bgMaxNear, 14)) bad.push(`lightest background ${hit.bgMax}, want about ${e.bgMaxNear}`);
    if (e.maxWidth && hit.rect[2] > e.maxWidth) bad.push(`measured rect ${hit.rect[2]}px wide, want <= ${e.maxWidth} (not clipped)`);
    const got = `${hit.verdict} ${hit.p10.toFixed(2)}:1 vs ${hit.threshold}:1  ${hit.color}${hit.alpha < 0.999 ? `@${Math.round(hit.alpha * 100)}%` : ''} on ${hit.bgMedian}`;
    return { ok: bad.length === 0, got: bad.length ? `${bad.join('; ')}  [${got}]` : got };
}

/** PNG decoder check: a synthetic image using each of the five filter types. */
function pngDecoderTest() {
    const w = 9, h = 10, bpp = 3, stride = w * bpp;
    const px = Buffer.alloc(stride * h);
    let seed = 12345;
    for (let i = 0; i < px.length; i++) { seed = (seed * 1103515245 + 12345) & 0x7fffffff; px[i] = (seed >> 8) & 255; }
    const rows = [];
    for (let y = 0; y < h; y++) {
        const f = y % 5;
        const out = Buffer.alloc(stride + 1);
        out[0] = f;
        for (let i = 0; i < stride; i++) {
            const x = px[y * stride + i];
            const a = i >= bpp ? px[y * stride + i - bpp] : 0, b = y > 0 ? px[(y - 1) * stride + i] : 0, c = i >= bpp && y > 0 ? px[(y - 1) * stride + i - bpp] : 0;
            const pa = Math.abs(b - c), pb = Math.abs(a - c), pc = Math.abs(a + b - 2 * c);
            const pred = f === 0 ? 0 : f === 1 ? a : f === 2 ? b : f === 3 ? (a + b) >> 1 : (pa <= pb && pa <= pc ? a : pb <= pc ? b : c);
            out[i + 1] = (x - pred) & 255;
        }
        rows.push(out);
    }
    const chunk = (type, data) => {
        const len = Buffer.alloc(4); len.writeUInt32BE(data.length);
        return Buffer.concat([len, Buffer.from(type, 'latin1'), data, Buffer.alloc(4)]);   // CRC is not checked by the decoder
    };
    const ihdr = Buffer.alloc(13);
    ihdr.writeUInt32BE(w, 0); ihdr.writeUInt32BE(h, 4); ihdr[8] = 8; ihdr[9] = 2;
    const png = Buffer.concat([Buffer.from([0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a]), chunk('IHDR', ihdr),
        chunk('IDAT', zlib.deflateSync(Buffer.concat(rows))), chunk('IEND', Buffer.alloc(0))]);
    const img = decodePng(png);
    return img.width === w && img.height === h && img.channels === 3 && Buffer.compare(img.data, px) === 0;
}

async function selfTest(o) {
    const st = makeStyler(o.color);
    let wrong = 0, total = 0;
    const line = (ok, id, title, got) => {
        total++;
        if (!ok) wrong++;
        console.log(`  ${ok ? st.green('PASS') : st.red('FAIL')}  ${id.padEnd(5)} ${title}${got ? `\n${' '.repeat(14)}${st.dim('-> ' + got)}` : ''}`);
    };
    console.log(st.bold('contrast-audit self-test: a seeded fixture rendered in about:blank, the site is never contacted'));
    console.log(st.dim('every assertion is one the auditor must get right; a wrong answer exits non-zero\n'));

    console.log(st.bold('maths and decoding (no browser)'));
    const near = (a, b, tol) => Math.abs(a - b) <= tol;
    line(contrastHex('#000000', '#ffffff') === 21, 'U1', 'black on white is exactly 21:1', `${contrastHex('#000000', '#ffffff')}`);
    line(near(contrastHex('#88e788', '#ffffff'), 1.518, 0.005), 'U2', '#88E788 on white is 1.52:1', contrastHex('#88e788', '#ffffff').toFixed(4));
    line(near(contrastHex('#777777', '#ffffff'), 4.478, 0.005), 'U3', '#777777 on white is 4.48:1, below 4.5', contrastHex('#777777', '#ffffff').toFixed(4));
    line(near(contrastHex('#767676', '#ffffff'), 4.542, 0.005), 'U4', '#767676 on white is 4.54:1, above 4.5', contrastHex('#767676', '#ffffff').toFixed(4));
    line(isLarge(24, 400) && !isLarge(23.99, 400) && isLarge(18.66, 700) && !isLarge(18.65, 700) && !isLarge(18.66, 600) && isLarge(30, 300),
        'U5', 'large-text rule: 24px, or 18.66px at weight 700');
    line(pngDecoderTest(), 'U6', 'PNG decoder reproduces an image that uses all five filter types (None, Sub, Up, Average, Paeth)');
    const dd = dedupe([
        { kind: 'text', path: 'a > b', text: 'Same', p10: 3, min: 2, threshold: 4.5, verdict: 'FAIL' },
        { kind: 'text', path: 'a > b', text: 'Same', p10: 2, min: 1, threshold: 4.5, verdict: 'FAIL' },
        { kind: 'text', path: 'a > c', text: 'Same', p10: 9, min: 9, threshold: 4.5, verdict: 'PASS' },
    ]);
    line(dd.length === 2 && dd[0].count === 2 && dd[0].p10 === 2, 'U7', 'identical (path, text) pairs collapse to one row, keeping the worst reading');

    console.log(`\n${st.bold('rendered fixture (800x600 viewport, 13300px tall, three screenshot chunks)')}`);
    const b = await launchBrowser(o.port, 'selftest');
    b.origin = 'about:blank';
    const t0 = now();
    try {
        const vp = { w: 800, h: 600, key: '800x600' };
        await setViewport(b, vp, { ...o, reducedMotion: true });
        const { html, cases } = buildFixture();
        const load = async () => {
            await navigate(b, 'about:blank');
            await b.cdp.eval(`document.open(); document.write(${JSON.stringify(html)}); document.close(); 1`);
        };
        const base = await auditDocument(b, vp, { ...o, ignore: null, saveBg: null }, load, { keepFrames: true });
        console.log(st.dim(`  fixture: ${base.raw.length} measured items, ${base.unmeasured.length} unmeasured, ${Object.values(base.skipped).reduce((a, c) => a + c, 0)} not rendered, ${base.frames.length} chunks, ${base.attempts} attempt(s), ${((now() - t0) / 1000).toFixed(1)}s`));
        line(base.attempts === 1 && !base.unstable, 'U8', 'a static page is judged stable on the first attempt (before/after text signatures agree)');
        // the ABSENT cases carried by the A12 row have no html of their own
        const judged = new Map();
        for (const c of cases) {
            const r = judge(c.expect, base);
            judged.set(c.id, r.ok);
            line(r.ok, c.id, c.title, r.got);
        }

        console.log(`\n${st.bold('mutation checks: break the auditor on purpose and require the assertions to notice')}`);
        const th = { normal: o.normal, large: o.large };
        const redCount = (run) => cases.filter((c) => !judge(c.expect, run).ok).length;
        const mutants = [
            ['ignoreAlpha', 'treat every text colour as opaque (ignore alpha and ancestor opacity)', () => remeasure(base, th, 'ignoreAlpha')],
            ['shiftY', 'sample the background 14px below the text (a geometry/stitching error)', () => remeasure(base, th, 'shiftY')],
            ['noHide', 'capture the background WITHOUT removing the text', () => auditDocument(b, vp, { ...o, ignore: null, saveBg: null }, load, { mutate: 'noHide' })],
            ['noClip', 'ignore overflow/clip-path clipping when listing text', () => auditDocument(b, vp, { ...o, ignore: null, saveBg: null }, load, { mutate: 'noClip' })],
            ['noReveal', 'skip the .is-visible reveal step of the stabiliser', () => auditDocument(b, vp, { ...o, ignore: null, saveBg: null }, load, { mutate: 'noReveal' })],
        ];
        for (const [name, what, run] of mutants) {
            const r = await run();
            const flipped = cases.filter((c) => judged.get(c.id) && !judge(c.expect, r).ok).map((c) => c.id);
            line(flipped.length > 0, `M:${name}`.slice(0, 11), `${what}`, flipped.length ? `caught: ${flipped.length} assertion(s) turned red (${flipped.slice(0, 8).join(', ')}${flipped.length > 8 ? ', ...' : ''})` : 'NOT CAUGHT: the assertions cannot see this defect');
            void redCount;
        }
    } finally {
        await stopBrowser(b);
    }
    console.log('');
    if (wrong) console.log(st.red(st.bold(`SELF-TEST FAILED: ${wrong} of ${total} expectation(s) wrong`)));
    else console.log(st.green(st.bold(`SELF-TEST PASSED: ${total} of ${total} expectations hold`)) + st.dim(`   (${((now() - T0) / 1000).toFixed(1)}s)`));
    return wrong ? 1 : 0;
}

/** Re-measure already-captured frames with a different policy (no second browser run). */
function remeasure(run, th, mutate) {
    const plan = buildPlan(run.rawItems, run.docW, run.docH, mutate);
    for (const f of run.frames) sampleChunk(plan, f.img, f.y0);
    return { ...run, raw: finishPlan(plan, run.rawItems, th, mutate) };
}
