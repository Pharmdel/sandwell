#!/usr/bin/env node
/**
 * Deterministic screenshotter for visual review (human or AI).
 *
 * Drives headless Chrome over a raw CDP WebSocket (zero dependencies, Node 22+) and
 * writes images that stay READABLE when a reviewer looks at them: nothing is a
 * 9000px-tall full-page PNG. Every page is cut into viewport-scale JPEG slices
 * (<= 1000px tall at 1440 wide, <= 1400px tall at 390 wide), plus a "top" shot of the
 * first viewport exactly as a visitor sees it, plus optional interactive STATE shots
 * (menu open, quiz verdicts, hover and focus styles, form errors...).
 *
 * USAGE
 *   node tests/manual/shots.mjs --out <dir> [--base http://127.0.0.1:8899]
 *        [--pages /,/pharmacy-first] [--viewports 1440x900,390x844]
 *        [--states] [--no-pages] [--only name1,name2]
 *
 *   --out <dir>              required. Output directory (created if missing).
 *   --base <url>             site to shoot. Default http://127.0.0.1:8899
 *   --pages /,/pharmacy-first  only these paths. Default: crawl every internal page from "/"
 *   --viewports 1440x900,390x844   page viewports (these are the defaults). DPR is always 1.
 *   --states                 also run the interactive state scenarios (see --list-states)
 *   --no-pages               skip the page shots (states only)
 *   --only a,b               run only these names. A name is a state (menu-open, bp-*, ...)
 *                            or a page slug (home, services-acne, ...); * wildcards allowed.
 *                            State names imply --states. Page slugs filter the page list;
 *                            when no page slug is given, no page shots are taken.
 *   --state-viewports 390x844  run EVERY selected state at these viewports instead of its own
 *                            default (menu-open and header-scrolled: 1440x900 + 390x844,
 *                            everything else: 1440x900).
 *   --jobs N                 parallel Chrome instances (default 3, debugging ports 9820+)
 *   --port N                 first debugging port (default 9820; the next free ports are used)
 *   --slice smart|fixed      smart (default) cuts between text lines / at section edges;
 *                            fixed cuts every max-height pixels
 *   --quality N              JPEG quality (default 82)
 *   --list-states            print the available state names and exit
 *   --strict                 exit 1 if any page or state failed (default: exit 0)
 *   --no-cleanup             keep the "QA Tool" enquiry row that form-success creates
 *   --debug                  verbose log, and save a screenshot of each failed state
 *   --help                   this text
 *
 * EXAMPLES
 *   node tests/manual/shots.mjs --out /tmp/shots --pages /,/pharmacy-first --states
 *   node tests/manual/shots.mjs --out /tmp/shots                      # whole site, 2 viewports
 *   node tests/manual/shots.mjs --out /tmp/s --no-pages --only menu-open,quiz-*   # some states
 *   node tests/manual/shots.mjs --out /tmp/s --only home,services-acne            # two pages
 *
 * OUTPUT (all inside --out)
 *   <slug>__<WxH>__top.jpg     first viewport as a visitor sees it (fixed header, scrollY 0)
 *   <slug>__<WxH>__s01.jpg ... consecutive, non-overlapping vertical slices, top to bottom
 *   state__<name>__<WxH>.jpg   one viewport-sized shot per interactive state (a few small
 *                              widgets also write state__<name>__<WxH>__zoom.jpg, a 2x crop)
 *   index.json   { generated, base, chrome, seconds, pages:[{page, slug, viewport, ok, status,
 *                  docHeight, files:[top, s01, ...], top, slices:[{file,y,height}], overflowX,
 *                  errors, brokenImages, warnings}],
 *                  states:[{name, viewport, path, ok, file, extraFiles, error, notes,
 *                  warnings, desc}], skipped:[...] }
 *   index.md     the same, written to be read
 *   slug = path with "/" turned into "-" ("home" for "/").
 *
 * HOW IT STAYS DETERMINISTIC
 *   - DPR 1. Animations and transitions are disabled (including ::before/::after) before
 *     first paint; parallax, the hero-rail blur, smooth scrolling and the text caret are
 *     neutralised; scrollTo / scrollIntoView always jump instantly.
 *   - Long-period timers (hero rail and testimonial auto-rotation) never start, so every
 *     page rests on item 1.
 *   - reveal / clip-reveal / line-mask get .is-visible, the page is scrolled through in 500px
 *     steps so lazy images and observers fire, lazy images are forced eager, fonts, images
 *     and map tiles are awaited, [data-count] counters are driven to their final value.
 *   - Slices are Page.captureScreenshot(captureBeyondViewport + clip) with the fixed header
 *     made absolute, so the header appears once at the top of s01. smart slicing cuts in the
 *     gaps between text lines (preferring section edges) so no line of text is sliced.
 *   - Hover is a real Input.dispatchMouseEvent; the pointer is parked at (1,1) otherwise.
 *     Focus is real keyboard Tab presses. Clicks are real mouse presses.
 *
 * KNOWN NON-DETERMINISM (cannot be pinned from the browser)
 *   - /conditions/* "Other ... conditions" cards are shuffled server-side on every request.
 *   - Leaflet map tiles come from tile.openstreetmap.org (needs internet access).
 *   - Web fonts come from Google Fonts; without internet the fallback fonts are used (the
 *     page entry then carries a warning).
 *
 * SIDE EFFECT
 *   The form-success state submits the acne enquiry form as "QA Tool" (one row in the sqlite
 *   table service_enquiries). The run deletes those rows again when it ends (php artisan
 *   tinker, run from the project root) unless --no-cleanup is given. Nothing else is written
 *   to the app: the weight-loss booking form is filled but never submitted.
 *
 * Only the Chrome processes this script spawned are ever killed (by handle), never by name.
 */
import { spawn, execFileSync } from 'node:child_process';
import { mkdirSync, writeFileSync, readdirSync, readFileSync, rmSync } from 'node:fs';
import net from 'node:net';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const SELF = fileURLToPath(import.meta.url);
const ROOT = resolve(dirname(SELF), '../..');
const CHROME = process.env.CHROME_BIN || '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
const pad2 = (n) => String(n).padStart(2, '0');
const t0 = Date.now();
const secs = (since = t0) => ((Date.now() - since) / 1000).toFixed(1);

/* ------------------------------------------------------------------ cli */

function die(msg) {
    console.error(`shots.mjs: ${msg}\nRun with --help for usage.`);
    process.exit(2);
}

function parseViewport(s) {
    const m = /^(\d{2,5})x(\d{2,5})$/.exec(String(s).trim());
    if (!m) die(`bad viewport "${s}" (expected WxH, e.g. 1440x900)`);
    const w = +m[1], h = +m[2];
    return { w, h, key: `${w}x${h}`, maxSlice: w >= 768 ? 1000 : 1400 };
}

function parseArgs(argv) {
    const o = {
        out: null, base: 'http://127.0.0.1:8899', pages: null, viewports: '1440x900,390x844',
        states: false, noPages: false, only: null, stateViewports: null, jobs: 3, port: 9820,
        slice: 'smart', quality: 82, listStates: false, strict: false, cleanup: true, debug: false, help: false,
    };
    const flags = new Set(['--states', '--no-pages', '--list-states', '--strict', '--no-cleanup', '--debug', '--help', '-h']);
    for (let i = 0; i < argv.length; i++) {
        const eq = argv[i].startsWith('--') ? argv[i].indexOf('=') : -1;
        const a = eq > 0 ? argv[i].slice(0, eq) : argv[i];
        const inline = eq > 0 ? argv[i].slice(eq + 1) : undefined;
        const val = () => {
            if (inline !== undefined) return inline;
            if (i + 1 >= argv.length) die(`${a} needs a value`);
            return argv[++i];
        };
        if (!flags.has(a) && !a.startsWith('--')) die(`unexpected argument "${a}"`);
        switch (a) {
            case '--out': o.out = val(); break;
            case '--base': o.base = val().replace(/\/+$/, ''); break;
            case '--pages': o.pages = val().split(',').map((s) => s.trim()).filter(Boolean).map((p) => (p.startsWith('/') ? p : `/${p}`)); break;
            case '--viewports': o.viewports = val(); break;
            case '--state-viewports': o.stateViewports = val(); break;
            case '--only': o.only = val().split(',').map((s) => s.trim()).filter(Boolean); break;
            case '--jobs': o.jobs = Math.max(1, Math.min(8, parseInt(val(), 10) || 1)); break;
            case '--port': o.port = parseInt(val(), 10) || 9820; break;
            case '--slice': o.slice = val(); if (!['smart', 'fixed'].includes(o.slice)) die('--slice must be smart or fixed'); break;
            case '--quality': o.quality = Math.max(30, Math.min(100, parseInt(val(), 10) || 82)); break;
            case '--states': o.states = true; break;
            case '--no-pages': o.noPages = true; break;
            case '--list-states': o.listStates = true; break;
            case '--strict': o.strict = true; break;
            case '--no-cleanup': o.cleanup = false; break;
            case '--debug': o.debug = true; break;
            case '--help': case '-h': o.help = true; break;
            default: die(`unknown option ${a}`);
        }
    }
    return o;
}

const CFG = parseArgs(process.argv.slice(2));
const log = (...a) => console.log(...a);
const dbg = (...a) => CFG.debug && console.log('\x1b[90m  ·', ...a, '\x1b[0m');

/* ------------------------------------------------------------ small utils */

const slugOf = (path) => {
    const s = path.replace(/[?#].*$/, '').replace(/^\/+|\/+$/g, '').replace(/[^A-Za-z0-9._-]+/g, '-');
    return s === '' ? 'home' : s;
};

/** name matcher with * wildcards, case-insensitive */
const globToRe = (g) => new RegExp(`^${g.replace(/[.+^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*')}$`, 'i');

/** Width/height from a JPEG's SOF marker, so every saved file is verified without Pillow. */
function jpegSize(buf) {
    if (buf.length < 4 || buf[0] !== 0xff || buf[1] !== 0xd8) return null;
    let i = 2;
    while (i + 9 < buf.length) {
        if (buf[i] !== 0xff) { i++; continue; }
        const marker = buf[i + 1];
        if (marker === 0xff) { i++; continue; }
        if (marker >= 0xc0 && marker <= 0xcf && ![0xc4, 0xc8, 0xcc].includes(marker)) {
            return { h: buf.readUInt16BE(i + 5), w: buf.readUInt16BE(i + 7) };
        }
        i += 2 + buf.readUInt16BE(i + 2);
    }
    return null;
}

const withTimeout = (promise, ms, label) => {
    let timer;
    return Promise.race([
        promise,
        new Promise((_, rej) => { timer = setTimeout(() => rej(new Error(`timed out after ${ms / 1000}s: ${label}`)), ms); }),
    ]).finally(() => clearTimeout(timer));
};

/** A failed interaction (selector missing, widget did not respond): deterministic, so never retried. */
class QaError extends Error {}

/* ------------------------------------------------------- page discovery */

/**
 * Walk every internal page from "/", the same way crawl.mjs does: follow href
 * attributes, skip assets and mailto/tel, follow redirects to wherever they land.
 */
async function crawl(base) {
    const found = new Set();
    const skipped = [];
    const queue = ['/'];
    const seen = new Set(queue);
    const abs = (href, from) => {
        try {
            const u = new URL(href, base + from);
            return u.origin === new URL(base).origin ? u : null;
        } catch { return null; }
    };
    while (queue.length) {
        const path = queue.shift();
        let res;
        try {
            res = await fetch(base + path, { redirect: 'manual', signal: AbortSignal.timeout(20000) });
        } catch (e) { skipped.push({ path, error: String(e.message ?? e) }); continue; }
        if (res.status >= 300 && res.status < 400) {
            const to = abs(res.headers.get('location') ?? '', path);
            if (to && !seen.has(to.pathname)) { seen.add(to.pathname); queue.push(to.pathname); }
            continue;
        }
        if (res.status !== 200) { skipped.push({ path, status: res.status }); continue; }
        if (!(res.headers.get('content-type') ?? '').includes('html')) continue;
        found.add(path);
        const html = await res.text();
        for (const m of html.matchAll(/\bhref="([^"]+)"/g)) {
            const raw = m[1].replace(/&amp;/g, '&');
            if (/^(mailto:|tel:|data:|javascript:)/i.test(raw)) continue;
            const u = abs(raw, path);
            if (!u) continue;
            if (/\.(png|jpe?g|webp|svg|css|js|ico|woff2?|pdf|xml|txt|json|map)$/i.test(u.pathname)) continue;
            if (!seen.has(u.pathname)) { seen.add(u.pathname); queue.push(u.pathname); }
        }
    }
    const list = [...found].sort((a, b) => (a === '/' ? -1 : b === '/' ? 1 : a.localeCompare(b)));
    return { list, skipped };
}

/* ------------------------------------------------------------- CDP client */

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
                for (const fn of this.listeners.get(m.method) ?? []) { try { fn(m.params ?? {}); } catch { /* a listener must never break the socket */ } }
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

    close() { this.closed = true; try { this.ws.close(); } catch { /* already gone */ } }
}

/* ---------------------------------------------------------- Chrome launch */

const liveChromes = new Set();
const reservedPorts = new Set();

const portFree = (port) => new Promise((res) => {
    const s = net.createServer();
    s.once('error', () => res(false));
    s.once('listening', () => s.close(() => res(true)));
    s.listen(port, '127.0.0.1');
});

/**
 * Another DevTools server may already own a port (a leftover run, another tool), and
 * driving it by mistake would be worse than any failure. Ports are claimed before the
 * first await so two concurrent launches can never pick the same one.
 */
async function reservePort(start) {
    let p = start;
    for (let i = 0; i < 300; i++, p++) {
        if (reservedPorts.has(p)) continue;
        reservedPorts.add(p);
        if (await portFree(p)) return p;
    }
    throw new Error(`no free debugging port from ${start}`);
}

async function launchChrome(startPort, tag) {
    const port = await reservePort(startPort);
    const dir = `/tmp/cdp-shots-${process.pid}-${tag}`;
    rmSync(dir, { recursive: true, force: true });
    const child = spawn(CHROME, [
        '--headless=new', `--remote-debugging-port=${port}`, '--disable-gpu', '--hide-scrollbars', '--no-first-run',
        '--no-default-browser-check', '--mute-audio', '--force-color-profile=srgb', '--font-render-hinting=none',
        '--disable-background-timer-throttling', '--disable-renderer-backgrounding', '--disable-backgrounding-occluded-windows',
        '--disable-extensions', '--disable-component-update', '--disable-sync', '--metrics-recording-only',
        '--use-mock-keychain', '--password-store=basic', '--lang=en-GB', '--window-size=1440,900',
        `--user-data-dir=${dir}`, 'about:blank',
    ], { stdio: 'ignore' });
    const proc = { child, dir, port, exited: false };
    child.on('exit', () => { proc.exited = true; liveChromes.delete(proc); });
    child.on('error', () => { proc.exited = true; });
    liveChromes.add(proc);

    let target = null;
    const started = Date.now();
    while (!target) {
        if (proc.exited) throw new Error('Chrome exited during start-up');
        if (Date.now() - started > 20000) { await stopChrome(proc, true); throw new Error('Chrome did not open its debugging port within 20s'); }
        try {
            const list = await (await fetch(`http://127.0.0.1:${port}/json/list`, { signal: AbortSignal.timeout(2000) })).json();
            target = list.find((t) => t.type === 'page') ?? null;
        } catch { /* not up yet */ }
        if (!target) await sleep(150);
    }
    let version = '';
    try { version = (await (await fetch(`http://127.0.0.1:${port}/json/version`, { signal: AbortSignal.timeout(2000) })).json()).Browser ?? ''; } catch { /* cosmetic */ }
    return { proc, wsUrl: target.webSocketDebuggerUrl, version };
}

/** Kill only the process this script spawned. SIGTERM first, SIGKILL if it lingers. */
async function stopChrome(proc, hard = false) {
    if (!proc) return;
    if (!proc.exited) {
        try { proc.child.kill(hard ? 'SIGKILL' : 'SIGTERM'); } catch { /* gone */ }
        const until = Date.now() + 2500;
        while (!proc.exited && Date.now() < until) await sleep(50);
        if (!proc.exited) { try { proc.child.kill('SIGKILL'); } catch { /* gone */ } await sleep(200); }
    }
    rmSync(proc.dir, { recursive: true, force: true });
}

function killAllSync() {
    for (const p of liveChromes) { try { p.child.kill('SIGKILL'); } catch { /* gone */ } rmSync(p.dir, { recursive: true, force: true }); }
}

/* ----------------------------------------------------- in-page library */

/** Applied before first paint on every page. The first three lines are the documented gotchas. */
const FREEZE_CSS = [
    '*, *::before, *::after { animation: none !important; transition: none !important; caret-color: transparent !important; }',
    '[data-carousel-item] img { filter: none !important; opacity: 1 !important; will-change: auto !important; }',
    '[data-parallax] { transform: none !important; }',
    'html, body { scroll-behavior: auto !important; }',
].join('\n');

/** Added only while the page is being sliced: the fixed header appears once, at the top of s01. */
const SLICE_CSS = 'header[data-header] { position: absolute !important; } #scroll-progress { display: none !important; }';

/**
 * Everything that has to run inside the page. It is stringified and installed with
 * Page.addScriptToEvaluateOnNewDocument (so it is in place before first paint), then
 * called through window.__qa.* from the Node side. It must stay self-contained.
 */
function pageLib(cfg) {
    if (window.__qa) return;
    const qa = (window.__qa = {});
    const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
    qa.sleep = sleep;
    qa.raf2 = () => new Promise((r) => requestAnimationFrame(() => requestAnimationFrame(() => r(true))));

    /* 1. motion is frozen before the first paint */
    const putCss = (id, css) => {
        const host = document.head || document.documentElement;
        if (!host) return false;
        let s = document.getElementById(id);
        if (!s) { s = document.createElement('style'); s.id = id; host.appendChild(s); }
        s.textContent = css;
        return true;
    };
    qa.css = putCss;
    if (!putCss('__qa_freeze', cfg.css)) {
        const mo = new MutationObserver(() => { if (putCss('__qa_freeze', cfg.css)) mo.disconnect(); });
        mo.observe(document, { childList: true, subtree: true });
    }

    /* 2. scrolling always jumps (an explicit behavior:"smooth" would otherwise animate) */
    const instant = (orig) => function (a, ...rest) {
        if (a && typeof a === 'object') a = Object.assign({}, a, { behavior: 'instant' });
        return orig.call(this, a, ...rest);
    };
    for (const n of ['scrollIntoView', 'scrollTo', 'scroll', 'scrollBy']) {
        if (typeof Element.prototype[n] === 'function') Element.prototype[n] = instant(Element.prototype[n]);
    }
    for (const n of ['scrollTo', 'scroll', 'scrollBy']) {
        if (typeof window[n] === 'function') window[n] = instant(window[n]);
    }

    /* 3. long-period timers never start: the hero rail (3.6s) and testimonials (5s) stay on item 1 */
    const realSetInterval = window.setInterval.bind(window);
    window.setInterval = (fn, ms, ...args) => (typeof ms === 'number' && ms >= 2000 ? 0 : realSetInterval(fn, ms, ...args));

    /* 4. finding things */
    qa.isVisible = (el) => {
        if (!el || !el.getBoundingClientRect) return false;
        const r = el.getBoundingClientRect();
        if (r.width < 1 || r.height < 1) return false;
        const cs = getComputedStyle(el);
        if (cs.visibility === 'hidden' || cs.display === 'none' || parseFloat(cs.opacity) < 0.01) return false;
        return !el.closest('[hidden]');
    };
    qa.list = (sel, o) => {
        o = o || {};
        const root = o.within ? document.querySelector(o.within) : document;
        if (!root) return [];
        let list;
        try { list = [...root.querySelectorAll(sel)]; } catch (e) { throw new Error('bad selector: ' + sel); }
        if (o.text) { const re = new RegExp(o.text, 'i'); list = list.filter((e) => re.test(e.textContent || '')); }
        if (o.visible) list = list.filter(qa.isVisible);
        return list;
    };
    qa.pick = (sel, o) => {
        o = o || {};
        let el = qa.list(sel, o)[o.nth || 0] || null;
        if (el && o.closest) el = el.closest(o.closest);
        return el;
    };
    qa.exists = (sel, o) => !!qa.pick(sel, o);
    qa.focusEl = (sel, o) => {
        const el = qa.pick(sel, o);
        if (!el) return false;
        el.focus({ preventScroll: true });
        return document.activeElement === el;
    };
    qa.active = () => {
        const a = document.activeElement;
        return a && a !== document.body ? { desc: qa.describe(a), visible: a.matches(':focus-visible'), text: (a.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 60) } : null;
    };
    qa.count = (sel, o) => qa.list(sel, o).length;
    qa.text = (sel, o) => {
        const el = qa.pick(sel, o);
        return el ? (el.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 400) : null;
    };
    qa.describe = (el) => {
        if (!el) return null;
        const cls = typeof el.className === 'string' ? el.className.trim().split(/\s+/).slice(0, 3).join('.') : '';
        return el.tagName.toLowerCase() + (cls ? '.' + cls : '');
    };

    /* 5. putting things in front of the pointer / in the frame (fixed header is 80px + border) */
    const HEAD = 88;
    qa.target = (sel, o) => {
        o = o || {};
        const el = qa.pick(sel, o);
        if (!el) return null;
        const H = innerHeight;
        let r = el.getBoundingClientRect();
        if (!o.noScroll && (r.top < HEAD + 4 || r.bottom > H - 4)) {
            const y = scrollY + r.top - HEAD - Math.max(12, (H - HEAD - r.height) / 2);
            window.scrollTo({ top: Math.max(0, Math.round(y)), behavior: 'instant' });
            r = el.getBoundingClientRect();
        }
        const x = Math.round(r.left + r.width / 2);
        const y = Math.round(r.top + r.height / 2);
        const hit = document.elementFromPoint(x, y);
        const lab = el.closest && el.closest('label');
        const ok = !!hit && (hit === el || el.contains(hit) || !!(lab && lab.contains(hit)));
        return { x, y, w: Math.round(r.width), h: Math.round(r.height), hit: ok, hitDesc: qa.describe(hit), tag: el.tagName.toLowerCase() };
    };
    qa.union = (sels, o) => {
        let top = Infinity, bottom = -Infinity, left = Infinity, right = -Infinity, n = 0;
        for (const s of Array.isArray(sels) ? sels : [sels]) {
            const spec = typeof s === 'string' ? { s } : s;
            const el = qa.pick(spec.s, Object.assign({}, o, spec));
            if (!el) continue;
            const r = el.getBoundingClientRect();
            top = Math.min(top, r.top + scrollY); bottom = Math.max(bottom, r.bottom + scrollY);
            left = Math.min(left, r.left + scrollX); right = Math.max(right, r.right + scrollX);
            n++;
        }
        return n ? { x: left, y: top, w: right - left, h: bottom - top, n } : null;
    };
    qa.frame = (sels, o) => {
        o = o || {};
        const u = qa.union(sels, o);
        if (!u) return null;
        const area = innerHeight - HEAD - 16;
        const y = (o.align || 'center') === 'center' && u.h <= area
            ? u.y - HEAD - (area - u.h) / 2
            : u.y - HEAD - (o.pad === undefined ? 12 : o.pad);
        window.scrollTo({ top: Math.max(0, Math.round(y)), behavior: 'instant' });
        return { top: Math.round(u.y), height: Math.round(u.h), scrollY: Math.round(scrollY) };
    };
    qa.rect = (sels, o) => qa.union(sels, o);

    /* 6. settle a freshly loaded page: reveal everything, load everything, wait for everything */
    qa.stabilise = async (o) => {
        o = o || {};
        const started = performance.now();
        const left = () => (o.budget || 30000) - (performance.now() - started);
        const until = async (cond, ms) => {
            const end = performance.now() + Math.max(0, ms);
            for (;;) {
                let ok = false;
                try { ok = cond(); } catch (e) { ok = false; }
                if (ok) return true;
                if (performance.now() >= end) return false;
                await sleep(60);
            }
        };
        const docH = () => Math.max(document.documentElement.scrollHeight, document.body ? document.body.scrollHeight : 0);
        const mark = () => document.querySelectorAll('.reveal, .clip-reveal, .line-mask').forEach((e) => e.classList.add('is-visible'));
        const eager = () => document.querySelectorAll('img[loading="lazy"], iframe[loading="lazy"]').forEach((e) => { e.loading = 'eager'; });
        const report = {};

        mark(); eager();
        if (o.deep) {
            for (let y = 0, g = 0; g < 800 && y <= docH() && left() > 0; g++, y += 500) {
                window.scrollTo({ top: y, behavior: 'instant' });
                mark(); eager();
                await sleep(o.stepMs || 70);
            }
            window.scrollTo({ top: docH(), behavior: 'instant' });
            mark(); eager();
            await sleep(150);
        }
        report.imagesSettled = await until(() => [...document.images].every((i) => i.complete), Math.min(12000, left()));
        await Promise.race([
            Promise.all([...document.images].filter((i) => i.complete && i.naturalWidth > 0).map((i) => (i.decode ? i.decode().catch(() => {}) : null))),
            sleep(4000),
        ]);
        if (document.fonts && document.fonts.ready) await Promise.race([document.fonts.ready, sleep(5000)]);
        if (document.querySelector('.leaflet-tile-pane')) {
            report.tiles = await until(() => {
                const t = [...document.querySelectorAll('.leaflet-tile')];
                return t.length > 0 && t.every((x) => x.classList.contains('leaflet-tile-loaded'));
            }, Math.min(9000, left()));
            await sleep(120);
        }
        // counters count up from 0 when they scroll into view: visit each, wait for the last digit
        const counters = [...document.querySelectorAll('[data-count]')];
        if (counters.length) {
            const fmt = (el) => { const v = el.dataset.count || ''; return parseFloat(v).toFixed((v.split('.')[1] || '').length); };
            for (const el of counters) { el.scrollIntoView({ block: 'center' }); await sleep(140); }
            report.counters = await until(() => counters.every((el) => el.textContent.trim() === fmt(el)), Math.min(3500, left()));
            counters.forEach((el) => { el.textContent = fmt(el); });
        }
        mark();
        window.scrollTo({ top: 0, behavior: 'instant' });
        await qa.raf2();
        await sleep(80);
        window.scrollTo({ top: 0, behavior: 'instant' });
        await qa.raf2();
        return report;
    };

    qa.stats = () => {
        const de = document.documentElement;
        const W = innerWidth;
        const docH = Math.ceil(Math.max(de.scrollHeight, document.body ? document.body.scrollHeight : 0));
        const broken = [...document.images]
            .filter((i) => i.complete && i.naturalWidth === 0 && (i.currentSrc || i.src))
            .filter((i) => getComputedStyle(i).display !== 'none')
            .map((i) => (i.currentSrc || i.src).replace(location.origin, ''));
        const over = [];
        if (de.scrollWidth > W) {
            for (const el of document.body.querySelectorAll('*')) {
                const r = el.getBoundingClientRect();
                if (r.width < 1 || r.right <= W + 1) continue;
                let clipped = false;
                for (let a = el.parentElement; a && a !== de; a = a.parentElement) {
                    if (getComputedStyle(a).overflowX !== 'visible' && a.getBoundingClientRect().right <= W + 1) { clipped = true; break; }
                }
                if (clipped || getComputedStyle(el).position === 'fixed') continue;
                over.push(qa.describe(el) + ' right=' + Math.round(r.right));
                if (over.length >= 6) break;
            }
        }
        const nav = (performance.getEntriesByType('navigation') || [])[0] || {};
        const h1 = document.querySelector('h1');
        return {
            docH, scrollW: de.scrollWidth, innerW: W, innerH: innerHeight,
            overflowX: Math.max(0, de.scrollWidth - W), overflowers: over, broken: broken.slice(0, 12),
            title: document.title,
            h1: h1 ? h1.textContent.replace(/\s+/g, ' ').trim().slice(0, 120) : null,
            status: nav.responseStatus || 0,
            sheetsFailed: [...document.querySelectorAll('link[rel="stylesheet"]')].filter((l) => !l.sheet).map((l) => l.href),
            fonts: [...new Set([...document.fonts].filter((f) => f.status === 'loaded').map((f) => f.family.replace(/"/g, '')))],
        };
    };

    /**
     * Where to cut the page into slices no taller than maxH. "smart" cuts in the gaps
     * between text lines (and form controls, small images), preferring section edges, so no
     * line of text is ever sliced in half; if a stretch has no gap it cuts there anyway.
     */
    qa.plan = (maxH, mode) => {
        const de = document.documentElement;
        const docH = Math.ceil(Math.max(de.scrollHeight, document.body ? document.body.scrollHeight : 0));
        const cuts = [0];
        if (mode === 'fixed' || docH <= maxH) {
            for (let y = maxH; y < docH; y += maxH) cuts.push(y);
            return { docH, cuts, mode: 'fixed', forced: 0 };
        }
        const sy = window.scrollY;
        const memo = new Map();
        const clipOf = (el) => {
            if (!el || el === de) return null;
            if (memo.has(el)) return memo.get(el);
            let c = clipOf(el.parentElement);
            if (el !== document.body && getComputedStyle(el).overflowY !== 'visible') {
                const r = el.getBoundingClientRect();
                c = c ? { t: Math.max(c.t, r.top), b: Math.min(c.b, r.bottom) } : { t: r.top, b: r.bottom };
            }
            memo.set(el, c);
            return c;
        };
        const iv = [];
        const add = (top, bottom, owner) => {
            const c = clipOf(owner);
            let t = top, b = bottom;
            if (c) { t = Math.max(t, c.t); b = Math.min(b, c.b); }
            if (b - t >= 1) iv.push([t + sy, b + sy]);
        };
        const skip = /^(SCRIPT|STYLE|NOSCRIPT|TEMPLATE|HEAD|TITLE)$/;
        const range = document.createRange();
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        for (let n = walker.nextNode(); n; n = walker.nextNode()) {
            if (!n.nodeValue.trim()) continue;
            const p = n.parentElement;
            if (!p || skip.test(p.tagName)) continue;
            const cs = getComputedStyle(p);
            if (cs.visibility === 'hidden' || cs.display === 'none') continue;
            range.selectNodeContents(n);
            for (const r of range.getClientRects()) if (r.width > 1 && r.height > 1) add(r.top, r.bottom, p);
        }
        document.querySelectorAll('input, select, textarea, button, video, canvas, iframe, img, svg').forEach((el) => {
            if (el.matches('input[type="hidden"]')) return;
            const cs = getComputedStyle(el);
            if (cs.visibility === 'hidden' || cs.display === 'none') return;
            const r = el.getBoundingClientRect();
            if (r.width < 2 || r.height < 2) return;
            // a photo behind the content is decoration; cutting through it costs nothing
            if (/^(IMG|VIDEO|SVG)$/i.test(el.tagName) && (cs.position === 'absolute' || r.height > 520)) return;
            add(r.top, r.bottom, el);
        });
        iv.sort((a, b) => a[0] - b[0]);
        const merged = [];
        for (const [s, e] of iv) {
            const last = merged[merged.length - 1];
            if (last && s <= last[1] + 0.5) last[1] = Math.max(last[1], e); else merged.push([s, e]);
        }
        const gaps = [];
        let prev = 0;
        for (const [s, e] of merged) { if (s > prev) gaps.push([prev, s]); prev = Math.max(prev, e); }
        if (prev < docH) gaps.push([prev, docH]);
        const edges = [];
        document.querySelectorAll('main > *, body > footer').forEach((el) => {
            const r = el.getBoundingClientRect();
            if (r.height > 0) edges.push(r.top + sy, r.bottom + sy);
        });

        let y = 0, forced = 0;
        for (let guard = 0; docH - y > maxH && guard < 300; guard++) {
            const rem = docH - y;
            const n = Math.ceil(rem / maxH);
            const target = rem / n;                       // balanced slices rather than 1000, 1000, 12
            const ideal = y + target;
            const lo = y + Math.max(100, target * 0.6);
            const hi = Math.min(y + maxH, y + target * 1.25);
            let best = null;
            for (const [g0, g1] of gaps) {
                const a = Math.max(g0, lo), b = Math.min(g1, hi);
                if (a > b) continue;
                let c = Math.min(b, Math.max(a, (g0 + g1) / 2));
                let edge = null;
                for (const e of edges) {
                    if (e < a || e > b) continue;
                    if (edge === null || Math.abs(e - ideal) < Math.abs(edge - ideal)) edge = e;
                }
                if (edge !== null) c = edge;
                const score = Math.abs(c - ideal) - (edge !== null ? 160 : 0) - Math.min(g1 - g0, 60) * 1.2;
                if (!best || score < best.score) best = { c, score };
            }
            let cut = best ? Math.round(best.c) : Math.round(y + Math.min(maxH, target));
            if (!best) forced++;
            if (cut <= y + 50) cut = Math.round(y + Math.min(maxH, target));
            cuts.push(cut);
            y = cut;
        }
        return { docH, cuts, mode: 'smart', forced };
    };
}

const PAGE_LIB_SOURCE = `(${pageLib.toString()})(${JSON.stringify({ css: FREEZE_CSS })});`;

/* ------------------------------------------------------------ tab driver */

let OUT = null; // absolute output directory, set in main()

function saveJpeg(name, buf, expect, warnings) {
    writeFileSync(join(OUT, name), buf);
    const sz = jpegSize(buf);
    if (!sz) warnings.push(`${name}: not a readable JPEG`);
    else if (expect && (sz.w !== expect.w || sz.h !== expect.h)) warnings.push(`${name}: expected ${expect.w}x${expect.h}, got ${sz.w}x${sz.h}`);
    return sz;
}

const KEYS = {
    Tab: { key: 'Tab', code: 'Tab', vk: 9 },
    Enter: { key: 'Enter', code: 'Enter', vk: 13 },
    Escape: { key: 'Escape', code: 'Escape', vk: 27 },
    ArrowDown: { key: 'ArrowDown', code: 'ArrowDown', vk: 40 },
};

/** One browser tab, driven through the in-page library plus real input events. */
class Tab {
    constructor(cdp) {
        this.cdp = cdp;
        this.vp = null;
        this.errors = [];
        this.notes = [];
        this.warnings = [];
        cdp.on('Runtime.exceptionThrown', (p) => {
            const d = p.exceptionDetails ?? {};
            this.errors.push(`exception: ${String(d.exception?.description ?? d.text ?? '').split('\n')[0]}`.slice(0, 240));
        });
        cdp.on('Runtime.consoleAPICalled', (p) => {
            if (p.type === 'error') this.errors.push(`console.error: ${(p.args ?? []).map((a) => a.value ?? a.description ?? '').join(' ')}`.slice(0, 240));
        });
        cdp.on('Log.entryAdded', (p) => {
            const e = p.entry ?? {};
            if (e.level === 'error') this.errors.push(`${e.source}: ${e.text}${e.url ? ` ${String(e.url).replace(CFG.base, '')}` : ''}`.slice(0, 240));
        });
    }

    static async create(wsUrl) {
        const cdp = await Cdp.connect(wsUrl);
        const tab = new Tab(cdp);
        await Promise.all([cdp.send('Page.enable'), cdp.send('Runtime.enable'), cdp.send('Log.enable')]);
        // headless pages are not "focused" by default, which would suppress :focus-visible rings
        await cdp.send('Emulation.setFocusEmulationEnabled', { enabled: true });
        await cdp.send('Page.addScriptToEvaluateOnNewDocument', { source: PAGE_LIB_SOURCE });
        return tab;
    }

    sleep(ms) { return sleep(ms); }
    note(m) { this.notes.push(m); dbg(`note: ${m}`); }
    warn(m) { this.warnings.push(m); dbg(`warning: ${m}`); }

    /** Evaluate an expression in the page (awaiting promises); result comes back JSON-serialised. */
    async eval(expr, timeout = 30000) {
        const wrapped = `(async () => { try { return JSON.stringify(await (${expr})); } catch (e) { return JSON.stringify({ __qaError: String((e && e.message) || e) }); } })()`;
        const r = await this.cdp.send('Runtime.evaluate', { expression: wrapped, awaitPromise: true, returnByValue: true }, timeout);
        if (r.exceptionDetails) throw new Error(`eval failed: ${r.exceptionDetails.exception?.description ?? r.exceptionDetails.text}`);
        const raw = r.result?.value;
        const v = raw === undefined ? null : JSON.parse(raw);
        if (v && typeof v === 'object' && !Array.isArray(v) && '__qaError' in v) throw new Error(v.__qaError);
        return v;
    }

    /** Call window.__qa.<fn>(...args) */
    q(fn, ...args) {
        return this.eval(`window.__qa.${fn}(${args.map((a) => (a === undefined ? 'null' : JSON.stringify(a))).join(', ')})`);
    }

    async setViewport(vp) {
        this.vp = vp;
        await this.cdp.send('Emulation.setDeviceMetricsOverride', { width: vp.w, height: vp.h, deviceScaleFactor: 1, mobile: false, screenWidth: vp.w, screenHeight: vp.h });
    }

    async goto(path) {
        this.errors = [];
        this.notes = [];
        this.warnings = [];
        const loaded = this.cdp.once('Page.loadEventFired', 45000).then(() => true, () => false);
        const nav = await this.cdp.send('Page.navigate', { url: CFG.base + path }, 45000);
        if (nav.errorText) throw new Error(`navigation to ${path} failed: ${nav.errorText}`);
        if (!(await loaded)) this.warn(`load event did not fire within 45s for ${path}`);
        await this.park();
    }

    stabilise(deep) { return this.eval(`window.__qa.stabilise(${JSON.stringify({ deep: !!deep })})`, 90000); }
    async settle() { await this.eval('window.__qa.raf2()'); await sleep(40); }

    /* ---- pointer and keyboard (real input events) */
    mouse(type, x, y, extra = {}) {
        return this.cdp.send('Input.dispatchMouseEvent', { type, x, y, button: 'none', buttons: 0, pointerType: 'mouse', ...extra });
    }
    /** Park the pointer in the corner so a stray :hover never leaks from one shot into the next. */
    park() { return this.mouse('mouseMoved', 1, 1); }

    async press(name) {
        const k = KEYS[name] ?? { key: name, code: name, vk: name.length === 1 ? name.toUpperCase().charCodeAt(0) : 0 };
        const base = { key: k.key, code: k.code, windowsVirtualKeyCode: k.vk, nativeVirtualKeyCode: k.vk };
        await this.cdp.send('Input.dispatchKeyEvent', { type: 'rawKeyDown', ...base });
        await this.cdp.send('Input.dispatchKeyEvent', { type: 'keyUp', ...base });
        await sleep(60);
    }

    /* ---- finding things */
    async waitFor(sel, o = {}, timeout = 4000) {
        const end = Date.now() + timeout;
        for (;;) {
            if (await this.q('exists', sel, o)) return true;
            if (Date.now() >= end) return false;
            await sleep(100);
        }
    }
    async must(sel, o = {}, msg) {
        if (!(await this.waitFor(sel, o, o.timeout ?? 4000))) throw new QaError(msg || `selector not found: ${sel}`);
    }
    exists(sel, o = {}) { return this.q('exists', sel, o); }
    count(sel, o = {}) { return this.q('count', sel, o); }
    text(sel, o = {}) { return this.q('text', sel, o); }

    /** Wait for the element, scroll it clear of the header, report where its centre is. */
    async target(sel, o = {}) {
        const opts = { visible: true, ...o };
        if (!(await this.waitFor(sel, opts, o.timeout ?? 4000))) {
            throw new QaError(`selector not found${opts.visible ? ' (or not visible)' : ''}: ${sel}${o.nth ? ` [nth=${o.nth}]` : ''}${o.text ? ` text~/${o.text}/` : ''}`);
        }
        const info = await this.q('target', sel, opts);
        if (!info) throw new QaError(`selector vanished: ${sel}`);
        return info;
    }

    async click(sel, o = {}) {
        const info = await this.target(sel, o);
        if (info.hit) {
            await this.mouse('mouseMoved', info.x, info.y);
            await this.mouse('mousePressed', info.x, info.y, { button: 'left', buttons: 1, clickCount: 1 });
            await this.mouse('mouseReleased', info.x, info.y, { button: 'left', buttons: 0, clickCount: 1 });
        } else {
            this.warn(`click ${sel}: its centre is covered by ${info.hitDesc}; used element.click() instead`);
            await this.eval(`window.__qa.pick(${JSON.stringify(sel)}, ${JSON.stringify({ visible: true, ...o })}).click()`);
        }
        await sleep(o.wait ?? 300); // widgets render their HTML from JS after a click
        return info;
    }

    async hover(sel, o = {}) {
        const info = await this.target(sel, o);
        // approach from beside the target so Chrome sees the pointer enter it
        await this.mouse('mouseMoved', Math.max(1, info.x - 8), Math.max(1, info.y - 8));
        await this.mouse('mouseMoved', info.x, info.y);
        await sleep(o.wait ?? 220);
        if (!info.hit) this.warn(`hover ${sel}: its centre is covered by ${info.hitDesc}`);
        return info;
    }

    /** Click into a field, replace its value by typing (a real insertText, so input events fire). */
    async type(sel, text, o = {}) {
        await this.click(sel, { ...o, wait: 40 });
        await this.q('focusEl', sel, { visible: true, ...o });
        await this.eval('(() => { const a = document.activeElement; if (a && "value" in a) a.value = ""; return true; })()');
        await this.cdp.send('Input.insertText', { text });
        await sleep(o.wait ?? 250);
    }

    /** <select> popups cannot be driven headlessly, so set the value and fire the events a user would. */
    async setValue(sel, value, o = {}) {
        await this.target(sel, o);
        const got = await this.eval(`(() => { const el = window.__qa.pick(${JSON.stringify(sel)}, ${JSON.stringify({ visible: true, ...o })});
            el.value = ${JSON.stringify(value)};
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
            return el.value; })()`);
        if (got !== value) this.warn(`setValue ${sel}: wanted "${value}", the field holds "${got}"`);
        await sleep(150);
    }

    /** Soft assertion: the shot is still taken, but the index carries a warning. */
    async expectText(sel, re, label, o = {}) {
        const txt = await this.text(sel, { visible: false, ...o });
        if (txt === null || !re.test(txt)) this.warn(`expected ${label} in ${sel}${txt === null ? ' (element missing)' : `, saw "${txt.slice(0, 90)}"`}`);
        return txt;
    }

    /* ---- framing and capture */
    async frame(sel, o = {}) {
        const r = await this.q('frame', sel, { visible: false, ...o });
        if (!r) throw new QaError(`cannot frame, selector not found: ${JSON.stringify(sel)}`);
        await this.settle();
        return r;
    }
    async scrollTo(y) {
        await this.eval(`window.scrollTo({ top: ${Math.round(y)}, behavior: 'instant' })`);
        await this.settle();
    }
    async zoomClip(sels, o = {}) {
        const r = await this.q('rect', sels, { visible: true, ...o });
        if (!r) return null;
        const pad = o.pad ?? 20;
        const x = Math.max(0, Math.floor(r.x - pad));
        const y = Math.max(0, Math.floor(r.y - pad));
        const width = Math.max(40, Math.min(Math.ceil(r.w + pad * 2), o.maxW ?? 760, this.vp.w - x));
        const height = Math.max(40, Math.min(Math.ceil(r.h + pad * 2), o.maxH ?? 460));
        return { x, y, width, height, scale: 2 };
    }
    /** Viewport screenshot, or (document-coordinate) clip, as JPEG bytes. */
    async shot({ clip = null } = {}) {
        const params = { format: 'jpeg', quality: CFG.quality, fromSurface: true };
        if (clip) {
            params.clip = { x: clip.x, y: clip.y, width: clip.width, height: clip.height, scale: clip.scale ?? 1 };
            params.captureBeyondViewport = true;
        }
        const r = await this.cdp.send('Page.captureScreenshot', params, 60000);
        return Buffer.from(r.data, 'base64');
    }
}

/* ------------------------------------------------------------ worker pool */

/** One Chrome process plus its tab. A worker restarts its browser if the browser dies or hangs. */
class Worker {
    constructor(id) {
        this.id = id;
        this.proc = null;
        this.tab = null;
        this.version = '';
        this.launches = 0;
    }
    get alive() { return !!this.proc && !this.proc.exited && !!this.tab && !this.tab.cdp.closed; }
    async start() {
        const { proc, wsUrl, version } = await launchChrome(CFG.port + this.id, `w${this.id}-${this.launches++}`);
        this.proc = proc;
        this.version = version;
        try { this.tab = await Tab.create(wsUrl); } catch (e) { await stopChrome(proc, true); this.proc = null; throw e; }
        dbg(`worker ${this.id}: ${version} on port ${proc.port}`);
    }
    async stop() {
        try { this.tab?.cdp.close(); } catch { /* gone */ }
        this.tab = null;
        if (this.proc) { await stopChrome(this.proc); this.proc = null; }
    }
    async restart() { await this.stop(); await this.start(); }
    async ensure() { if (!this.alive) await this.restart(); }
}

const INFRA_RE = /connection closed|no answer|timed out|not fired|websocket|Target closed|Session closed|navigation to .* failed|Execution context|Inspected target|Chrome exited/i;

/**
 * Run fn(tab) on a worker with a hard time limit. Infrastructure failures (a dead or hung
 * browser, a failed navigation) restart the browser and retry once; QaError and
 * everything else is deterministic, so it is reported straight away.
 */
async function withRetry(worker, label, limitMs, fn, tries = 2) {
    let last;
    for (let i = 0; i < tries; i++) {
        try {
            await worker.ensure();
            return await withTimeout(fn(worker.tab), limitMs, label);
        } catch (e) {
            last = e;
            const infra = !(e instanceof QaError) && (INFRA_RE.test(String(e.message)) || !worker.alive);
            if (!infra) break;
            dbg(`${label}: ${e.message}; restarting browser (attempt ${i + 1}/${tries})`);
            try { await worker.restart(); } catch (err) { last = err; break; }
            await sleep(500);
        }
    }
    throw last;
}

async function runPool(workers, jobs, handler) {
    let next = 0;
    await Promise.all(workers.map(async (w) => {
        for (;;) {
            const i = next++;
            if (i >= jobs.length) return;
            try { await handler(w, jobs[i], i); } catch (e) { log(`  ! unexpected: ${e?.stack ?? e}`); }
        }
    }));
}

/* ------------------------------------------------------------- page shots */

/**
 * Stabilise one page at one viewport and write top + slices. Everything the reviewer
 * needs to know about the page's health goes into the returned record.
 */
async function pageShots(tab, path, vp) {
    const slug = slugOf(path);
    const rec = {
        page: path, slug, viewport: vp.key, ok: false, status: 0, title: null, h1: null, docHeight: 0,
        files: [], top: null, slices: [], overflowX: 0, overflowers: [], errors: [], brokenImages: [], warnings: [],
    };
    const started = Date.now();
    await tab.setViewport(vp);

    let st = null;
    for (let attempt = 1; attempt <= 3; attempt++) {
        await tab.goto(path);
        await tab.stabilise(true);
        st = await tab.q('stats');
        const flaky = st.status >= 500 || st.sheetsFailed.length > 0;
        if (!flaky || attempt === 3) break;
        rec.warnings.push(`attempt ${attempt}: ${st.status >= 500 ? `HTTP ${st.status}` : `stylesheet failed to load (${st.sheetsFailed[0]})`}; reloading in 3s`);
        await sleep(3000);
    }
    rec.status = st.status;
    rec.title = st.title;
    rec.h1 = st.h1;
    rec.overflowX = st.overflowX;
    rec.overflowers = st.overflowers;
    rec.brokenImages = st.broken;
    if (st.status >= 400) rec.warnings.push(`HTTP ${st.status}`);
    if (st.sheetsFailed.length) rec.warnings.push(`stylesheet did not load: ${st.sheetsFailed.join(', ')}`);
    if (!st.fonts.some((f) => /geist/i.test(f))) rec.warnings.push('web fonts did not load (Google Fonts unreachable?): shots use fallback fonts');

    // stale slices from an earlier run of this page would otherwise sit beside the new ones
    for (const f of readdirSync(OUT)) {
        if (f.startsWith(`${slug}__${vp.key}__s`) && /__s\d+\.jpg$/.test(f)) rmSync(join(OUT, f), { force: true });
    }

    // 1. the first viewport exactly as a visitor sees it: fixed header, scrollY 0
    await tab.eval("window.scrollTo({ top: 0, behavior: 'instant' })");
    await tab.settle();
    rec.top = `${slug}__${vp.key}__top.jpg`;
    saveJpeg(rec.top, await tab.shot(), { w: vp.w, h: vp.h }, rec.warnings);

    // 2. the whole page, in readable slices, header pinned to the top of the first one
    await tab.q('css', '__qa_slice', SLICE_CSS);
    await tab.settle();
    const plan = await tab.q('plan', vp.maxSlice, CFG.slice);
    rec.docHeight = plan.docH;
    rec.slicing = { mode: plan.mode, forced: plan.forced };
    const cuts = [...plan.cuts, plan.docH];
    const width = vp.w;
    for (let i = 0; i < cuts.length - 1; i++) {
        const y = cuts[i];
        const height = cuts[i + 1] - y;
        if (height < 1) continue;
        const file = `${slug}__${vp.key}__s${String(i + 1).padStart(2, '0')}.jpg`;
        const buf = await tab.shot({ clip: { x: 0, y, width, height, scale: 1 } });
        saveJpeg(file, buf, { w: width, h: height }, rec.warnings);
        rec.slices.push({ file, y, height });
    }
    const after = await tab.q('stats');
    if (after.docH !== plan.docH) rec.warnings.push(`document height changed while slicing (${plan.docH} -> ${after.docH}); slices may not line up`);

    rec.files = [rec.top, ...rec.slices.map((s) => s.file)];
    rec.errors = [...new Set(tab.errors)].slice(0, 12);
    rec.ok = st.status > 0 ? st.status < 400 : true;
    rec.seconds = +((Date.now() - started) / 1000).toFixed(1);
    return rec;
}

/* ------------------------------------------------------- state scenarios */

const DESK = '1440x900';
const MOB = '390x844';
const SCENARIOS = [];
const scenario = (def) => SCENARIOS.push({ vps: [DESK], desc: {}, ...def });
const DONE = Symbol('scenario-complete');

/** Set just before the enquiry form is submitted, so the run knows the sqlite row needs removing. */
let enquirySubmitted = false;

/**
 * Run one scenario at one viewport on a fresh page load and return one result per state
 * name it was asked for. snap(name, opts) takes a state shot at the current moment:
 *   hover:true  keep the pointer where it is (default: park it in the corner first)
 *   clip:{x,y,width,height}  document-coordinate region instead of the viewport
 *   zoom:{sel, opts}         also write a 2x crop of that element (__zoom.jpg)
 */
async function runScenario(tab, sc, vp, names) {
    const results = [];
    const produced = new Set();
    const began = Date.now();
    await tab.setViewport(vp);

    const snap = async (name, o = {}) => {
        if (!names.includes(name) || produced.has(name)) return;
        if (!o.hover) await tab.park();
        await tab.settle();
        const file = `state__${name}__${vp.key}.jpg`;
        const warnings = [];
        let expect = { w: vp.w, h: vp.h };
        let buf;
        if (o.clip) {
            const k = o.clip.scale ?? 1;
            expect = { w: Math.round(o.clip.width * k), h: Math.round(o.clip.height * k) };
            buf = await tab.shot({ clip: o.clip });
        } else {
            buf = await tab.shot();
        }
        saveJpeg(file, buf, expect, warnings);
        const extra = [];
        if (o.zoom) {
            try {
                const clip = o.zoom.width !== undefined ? o.zoom : await tab.zoomClip(o.zoom.sel, o.zoom.opts ?? {});
                if (clip) {
                    const zf = `state__${name}__${vp.key}__zoom.jpg`;
                    saveJpeg(zf, await tab.shot({ clip }), null, warnings);
                    extra.push(zf);
                }
            } catch (e) { warnings.push(`zoom crop failed: ${e.message}`); }
        }
        results.push({
            name, viewport: vp.key, path: sc.path, ok: true, file, extraFiles: extra, clip: o.clip ?? null,
            desc: sc.desc[name] ?? '', notes: tab.notes.splice(0), warnings: [...tab.warnings.splice(0), ...warnings],
            seconds: +((Date.now() - began) / 1000).toFixed(1),
        });
        produced.add(name);
        if (names.every((n) => produced.has(n))) throw DONE;
    };

    try {
        await tab.goto(sc.path);
        const st = await tab.q('stats');
        if (st.status >= 400) throw new QaError(`HTTP ${st.status} for ${sc.path}`);
        await tab.stabilise(false);
        await sc.run(tab, snap, { vp });
    } catch (e) {
        if (e !== DONE) {
            const msg = e instanceof Error ? e.message : String(e);
            // a dead or hung browser is retried by the caller; a missing widget is a finding
            if (!(e instanceof QaError) && INFRA_RE.test(msg)) throw e;
            for (const n of names) {
                if (produced.has(n)) continue;
                results.push({
                    name: n, viewport: vp.key, path: sc.path, ok: false, file: null, extraFiles: [], error: msg,
                    desc: sc.desc[n] ?? '', notes: [...tab.notes], warnings: [...tab.warnings],
                    seconds: +((Date.now() - began) / 1000).toFixed(1),
                });
            }
            if (CFG.debug) {
                try { saveJpeg(`debug__${names[0]}__${vp.key}.jpg`, await tab.shot(), null, []); } catch { /* best effort */ }
            }
        }
    }
    return results;
}

/* --- header, menu, hero ---------------------------------------------------- */

scenario({
    id: 'menu', path: '/', vps: [DESK, MOB], names: ['menu-open', 'menu-link-hover'],
    desc: {
        'menu-open': 'Full-screen site menu, opened with the header Menu button',
        'menu-link-hover': 'Hover on the first menu link (desktop only)',
    },
    async run(t, snap) {
        await t.click('[data-menu-open]');
        await t.sleep(900);
        await t.must('#site-menu.is-open', { visible: true }, 'the menu did not open after clicking [data-menu-open] (#site-menu.is-open)');
        await snap('menu-open');
        if (t.vp.w >= 768) {
            await t.hover('#site-menu ul a', { visible: true });
            await snap('menu-link-hover', { hover: true, zoom: { sel: '#site-menu ul a', opts: { visible: true, pad: 28 } } });
        }
    },
});

scenario({
    id: 'header-scrolled', path: '/', vps: [DESK, MOB], names: ['header-scrolled'],
    desc: { 'header-scrolled': 'Header after scrolling 700px (solid ground and shadow instead of the transparent overlay)' },
    async run(t, snap) {
        await t.scrollTo(700);
        await t.must('[data-header].is-scrolled', {}, 'header never got .is-scrolled after scrolling 700px');
        await snap('header-scrolled');
    },
});

scenario({
    id: 'btn-focus', path: '/', names: ['btn-focus'],
    desc: { 'btn-focus': 'Header Call link with keyboard focus (reached with real Tab presses); the header strip, plus a 2x crop' },
    async run(t, snap) {
        const CALL = '[data-header] a[href^="tel:"]';
        let presses = 0;
        let on = false;
        while (presses < 16 && !on) {
            await t.press('Tab');
            presses += 1;
            on = await t.eval(`!!document.activeElement && document.activeElement.matches(${JSON.stringify(CALL)})`);
        }
        if (!on) throw new QaError(`the header Call link never received keyboard focus within ${presses} Tab presses`);
        await t.sleep(200);
        const act = await t.q('active');
        t.note(`Call link focused after ${presses} Tab presses; :focus-visible = ${act?.visible}`);
        if (!act?.visible) t.warn('the Call link has focus but does not match :focus-visible (no keyboard focus ring)');
        const head = await t.q('rect', '[data-header]', {});
        const clip = { x: 0, y: 0, width: t.vp.w, height: Math.ceil(head.h) + 24 };
        await snap('btn-focus', { clip, zoom: { sel: CALL, opts: { visible: true, pad: 24 } } });
    },
});

/** Mark the hero's primary call-to-action so it can be hovered by a stable selector. */
const HERO_PICK = (pred) => `(() => {
    const hero = document.querySelector('[data-hero]');
    if (!hero) return null;
    const items = [...hero.querySelectorAll('a, button')].filter((el) => window.__qa.isVisible(el) && !el.closest('[data-carousel]') && !el.matches('[data-rail-prev], [data-rail-next]'));
    const el = (${pred})(items);
    if (!el) return { found: false, labels: items.map((i) => i.textContent.trim().replace(/\\s+/g, ' ').slice(0, 40)) };
    el.setAttribute('data-qa-pick', '1');
    return { found: true, label: el.textContent.trim().replace(/\\s+/g, ' ').slice(0, 60), cls: window.__qa.describe(el), primary: el.classList.contains('bg-brand-primary') };
})()`;

scenario({
    id: 'buttons', path: '/', names: ['btn-hover-primary', 'btn-hover-ghost', 'header-call-hover'],
    desc: {
        'btn-hover-primary': "Hover on the hero's primary call-to-action (a 2x crop alongside)",
        'btn-hover-ghost': "Hover on the hero's translucent ghost button",
        'header-call-hover': 'Hover on the header Call button (which is the filled primary button in the first viewport)',
    },
    async run(t, snap) {
        const first = await t.eval(HERO_PICK('(items) => items.find((el) => el.classList.contains("bg-brand-primary")) || items[0]'));
        if (!first?.found) throw new QaError(`no button inside [data-hero]; saw: ${JSON.stringify(first?.labels ?? [])}`);
        if (!first.primary) t.note(`no bg-brand-primary button exists in the hero (it would vanish into the green hero); hovered the hero's first CTA instead: "${first.label}" (${first.cls})`);
        await t.frame('[data-qa-pick]', { align: 'center', visible: true });
        await t.hover('[data-qa-pick]', { visible: true });
        await snap('btn-hover-primary', { hover: true, zoom: { sel: '[data-qa-pick]', opts: { visible: true, pad: 26 } } });

        await t.eval("document.querySelectorAll('[data-qa-pick]').forEach((e) => e.removeAttribute('data-qa-pick'))");
        const ghost = await t.eval(HERO_PICK('(items) => items.find((el) => /backdrop-blur/.test(el.className))'));
        if (!ghost?.found) throw new QaError(`no ghost (backdrop-blur) button inside [data-hero]; saw: ${JSON.stringify(ghost?.labels ?? [])}`);
        await t.frame('[data-qa-pick]', { align: 'center', visible: true });
        await t.hover('[data-qa-pick]', { visible: true });
        await snap('btn-hover-ghost', { hover: true, zoom: { sel: '[data-qa-pick]', opts: { visible: true, pad: 26 } } });

        await t.scrollTo(0);
        await t.hover('[data-header] a[href^="tel:"]', { visible: true });
        await snap('header-call-hover', { hover: true, zoom: { sel: '[data-header] a[href^="tel:"]', opts: { visible: true, pad: 26 } } });
    },
});

scenario({
    id: 'home-rail', path: '/', names: ['home-rail-hover'],
    desc: { 'home-rail-hover': 'Pointer over the first service card in the hero rail (image zoom, arrow nudge, rail brought into focus)' },
    async run(t, snap) {
        await t.frame('[data-carousel-item]', { align: 'center', visible: true, nth: 0 });
        await t.hover('[data-carousel-item]', { visible: true, nth: 0 });
        await snap('home-rail-hover', { hover: true });
    },
});

/* --- home page widgets ----------------------------------------------------- */

scenario({
    id: 'home-faq', path: '/', names: ['home-faq-open'],
    desc: { 'home-faq-open': 'First FAQ opened inside the scrolling FAQ list' },
    async run(t, snap) {
        await t.frame('#faqs', { align: 'center' });
        await t.click('#faqs details > summary', { visible: true, nth: 0 });
        await t.sleep(400);
        await t.must('#faqs details[open]', {}, 'the first FAQ <details> did not open');
        await t.frame('#faqs', { align: 'center' });
        await snap('home-faq-open');
    },
});

scenario({
    id: 'home-testimonials', path: '/', names: ['home-testimonial-hover'],
    desc: { 'home-testimonial-hover': 'Pointer over the third reviewer tab, which swaps the featured review' },
    async run(t, snap) {
        await t.frame('[data-testimonials]', { align: 'center' });
        const n = await t.count('[data-testimonial-tab]');
        if (!n) throw new QaError('no [data-testimonial-tab] inside [data-testimonials]');
        const idx = Math.min(2, n - 1);
        await t.hover('[data-testimonial-tab]', { visible: true, nth: idx });
        await t.sleep(250);
        const active = await t.eval("[...document.querySelectorAll('[data-testimonial-tab]')].findIndex((b) => b.classList.contains('is-active'))");
        if (active !== idx) t.warn(`hovering tab ${idx} should activate it, but tab ${active} is active`);
        await snap('home-testimonial-hover', { hover: true });
    },
});

scenario({
    id: 'home-branch-map', path: '/', names: ['branch-map-hover', 'branch-map-pin-hover'],
    desc: {
        'branch-map-hover': 'Pointer over a branch entry: its pin lights up on the map and its tooltip opens',
        'branch-map-pin-hover': 'Pointer over a map pin: the matching branch entry highlights',
    },
    async run(t, snap) {
        const grid = [{ s: '[data-branch]', nth: 0 }, { s: '[data-branch]', nth: 5 }, '#branch-map'];
        await t.must('#branch-map .leaflet-tile-pane', {}, 'the Leaflet map never initialised (#branch-map .leaflet-tile-pane)');
        await t.frame(grid, { align: 'center' });
        await t.hover('[data-branch]', { visible: true, nth: 0 });
        await t.sleep(300);
        if (!(await t.exists('[data-branch].is-active'))) t.warn('hovering a branch entry did not give it .is-active');
        if (!(await t.exists('.branch-pin.is-active'))) t.warn('hovering a branch entry did not highlight a pin (.branch-pin.is-active)');
        await snap('branch-map-hover', { hover: true });

        await t.hover('.leaflet-marker-icon.branch-pin', { visible: true, nth: 1 });
        await t.sleep(300);
        if (!(await t.exists('[data-branch].is-active'))) t.warn('hovering a map pin did not highlight its branch entry');
        await snap('branch-map-pin-hover', { hover: true });
    },
});

/* --- service-page checkers ------------------------------------------------- */

scenario({
    id: 'bp', path: '/services/hypertension', names: ['bp-result-qualify', 'bp-result-decline'],
    desc: {
        'bp-result-qualify': 'Blood pressure check: aged 40+, no medication, no check in 5 years, so "you qualify"',
        'bp-result-decline': 'Blood pressure check: under 40, so the not-this-time verdict with the paid options',
    },
    async run(t, snap) {
        const C = '[data-bp-check]';
        const answer = (step, value) => t.click(`${C} [data-bp-step="${step}"] [data-bp-option="${value}"]`, { visible: true });
        await t.frame(C, { align: 'center' });
        for (const [s, v] of [['age', 'yes'], ['meds', 'no'], ['last5', 'no']]) await answer(s, v);
        await t.must(`${C} [data-bp-result] b`, {}, 'the blood pressure verdict did not render');
        await t.expectText(`${C} [data-bp-result]`, /qualify/i, 'a "you qualify" verdict');
        await t.frame(C, { align: 'center' });
        await snap('bp-result-qualify');

        await t.click(`${C} [data-bp-restart]`, { visible: true });
        for (const [s, v] of [['age', 'no'], ['meds', 'no'], ['last5', 'no']]) await answer(s, v);
        await t.expectText(`${C} [data-bp-result]`, /40 and over/i, 'the under-40 explanation');
        await t.frame(C, { align: 'center' });
        await snap('bp-result-decline');
    },
});

scenario({
    id: 'mds', path: '/services/mds-trays', names: ['mds-result-priority'],
    desc: { 'mds-result-priority': 'MDS blister packs: 7+ items, carer, weekly issue, often misses doses, priority route' },
    async run(t, snap) {
        const C = '[data-mds-check]';
        await t.frame(C, { align: 'center' });
        for (const [s, v] of [['items', '7+'], ['who', 'carer'], ['issue', 'weekly'], ['miss', 'often'], ['route', 'priority']]) {
            await t.click(`${C} [data-mds-step="${s}"] [data-mds-option="${v}"]`, { visible: true });
        }
        await t.must(`${C} [data-mds-result] b`, {}, 'the MDS verdict did not render');
        await t.expectText(`${C} [data-mds-result]`, /priority/i, 'a priority-route verdict');
        await t.frame(C, { align: 'center' });
        await snap('mds-result-priority');
    },
});

scenario({
    id: 'flu', path: '/services/flu-covid-vaccinations', names: ['flu-result-75plus', 'flu-result-30'],
    desc: {
        'flu-result-75plus': 'Flu and Covid eligibility: age 80, so free flu, free Covid and "have both in one visit"',
        'flu-result-30': 'Flu and Covid eligibility: healthy 30-year-old, so the private flu jab and no NHS Covid',
    },
    async run(t, snap) {
        const C = '[data-flu-check]';
        await t.frame(C, { align: 'top' });
        await t.type(`${C} [data-flu-age]`, '80');
        await t.click(`${C} [data-flu-run]`, { visible: true });
        await t.must(`${C} [data-flu-result] b`, {}, 'the flu verdict did not render');
        await t.expectText(`${C} [data-flu-result]`, /Covid-19 vaccination — you qualify/i, 'the free Covid verdict');
        await t.frame(C, { align: 'top' });
        await snap('flu-result-75plus');

        await t.type(`${C} [data-flu-age]`, '30');
        await t.click(`${C} [data-flu-run]`, { visible: true });
        await t.expectText(`${C} [data-flu-result]`, /Private flu jab/i, 'the private flu verdict');
        await t.frame(C, { align: 'top' });
        await snap('flu-result-30');
    },
});

scenario({
    id: 'postcode', path: '/repeat-prescriptions', names: ['postcode-result-yes', 'postcode-result-no'],
    desc: {
        'postcode-result-yes': 'Delivery check with B70 7RW: "Yes, we deliver to West Bromwich"',
        'postcode-result-no': 'Delivery check with SW1A 1AA: "We may still reach you" with the phone number',
    },
    async run(t, snap) {
        const C = '[data-postcode-check]';
        await t.frame(C, { align: 'center' });
        await t.type(`${C} [data-postcode-input]`, 'B70 7RW');
        await t.click(`${C} [data-postcode-run]`, { visible: true });
        await t.must(`${C} [data-postcode-result] b`, {}, 'the delivery verdict did not render');
        await t.expectText(`${C} [data-postcode-result]`, /^Yes/i, 'a "Yes" verdict');
        await t.frame(C, { align: 'center' });
        await snap('postcode-result-yes');

        await t.type(`${C} [data-postcode-input]`, 'SW1A 1AA');
        await t.click(`${C} [data-postcode-run]`, { visible: true });
        await t.expectText(`${C} [data-postcode-result]`, /0121 500 5756/, 'the call-us fallback');
        await t.frame(C, { align: 'center' });
        await snap('postcode-result-no');
    },
});

/* --- symptom quiz (/conditions/*) ------------------------------------------ */

const QUIZ = '#symptom-test';
const QUIZ_Q = `${QUIZ} [data-questions]`;
const QUIZ_CARD = [`${QUIZ} [data-start-wrap]`, { closest: '.rounded-3xl' }];

const quizKind = (t) => t.eval(`(() => {
    const v = document.querySelector('${QUIZ} [data-verdict]');
    if (v && !v.hidden && v.textContent.trim()) return 'verdict';
    const q = document.querySelector('${QUIZ_Q}');
    if (!q || q.hidden) return 'none';
    if (q.querySelector('[data-group="sym"]')) return 'sym';
    if (q.querySelector('[data-group="flags"]')) return 'flags';
    if (q.querySelector('[data-dur]')) return 'dur';
    if (q.querySelector('[data-field="age"]')) return 'about';
    if (q.querySelector('[data-path]')) return 'pathway';
    return 'unknown';
})()`);

/** Choose the first option on this step (without moving on). */
async function quizFill(t, kind, opts) {
    switch (kind) {
        case 'sym': await t.click(`${QUIZ_Q} [data-check]`, { visible: true, nth: 0 }); break;
        case 'dur': await t.click(`${QUIZ_Q} [data-dur]`, { visible: true, nth: 0 }); break;
        case 'about':
            await t.type(`${QUIZ_Q} [data-field="age"]`, '34');
            if (await t.exists(`${QUIZ_Q} [data-field="sex"]`, { visible: true })) await t.setValue(`${QUIZ_Q} [data-field="sex"]`, 'F');
            break;
        case 'flags': if (opts.flags === 'first') await t.click(`${QUIZ_Q} [data-check]`, { visible: true, nth: 0 }); break;
        case 'pathway': break; // the answer is the click that moves on
        default: throw new QaError(`unexpected quiz step "${kind}"`);
    }
}
async function quizAdvance(t, kind) {
    if (kind === 'pathway') await t.click(`${QUIZ_Q} [data-path]`, { visible: true, nth: 0 });
    else await t.click(`${QUIZ_Q} [data-next]`, { visible: true });
}
async function quizWalk(t, opts, onStep) {
    for (let i = 0; i < 14; i++) {
        const kind = await quizKind(t);
        if (kind === 'verdict') return;
        await quizFill(t, kind, opts);
        if (onStep) await onStep(kind, i);
        await quizAdvance(t, kind);
        await t.sleep(150);
    }
    throw new QaError('the symptom quiz did not reach a verdict within 14 steps');
}

scenario({
    id: 'quiz', path: '/conditions/sinusitis',
    names: ['quiz-step1', 'quiz-step2', 'quiz-verdict', 'quiz-callback-form', 'quiz-verdict-urgent'],
    desc: {
        'quiz-step1': 'Symptom quiz step 1 (symptoms) with the first symptom ticked',
        'quiz-step2': 'Symptom quiz step 2 with the first option chosen',
        'quiz-verdict': 'Quiz verdict after taking the first option at every step and ticking no safety flag: "you appear to be eligible"',
        'quiz-callback-form': 'The "leave your details" callback form that appears under an eligible verdict',
        'quiz-verdict-urgent': 'Quiz verdict when the first safety flag is ticked: "needs urgent attention"',
    },
    async run(t, snap) {
        await t.frame(QUIZ_CARD, { align: 'top' });
        await t.click(`${QUIZ} [data-start]`, { visible: true });
        await t.must(`${QUIZ_Q} [data-check]`, { visible: true }, 'the quiz did not start after [data-start]');
        const show = async (name) => { await t.frame(QUIZ_CARD, { align: 'top' }); await snap(name); };
        await quizWalk(t, { flags: 'none' }, async (kind, i) => {
            if (i === 0) await show('quiz-step1');
            if (i === 1) await show('quiz-step2');
        });
        await t.expectText(`${QUIZ} [data-verdict]`, /eligible/i, 'an "eligible" verdict');
        await show('quiz-verdict');
        if (await t.exists('[data-callback]', { visible: true })) {
            await t.frame('[data-callback]', { align: 'top', visible: true });
            await snap('quiz-callback-form');
        } else {
            t.warn('the callback form is not visible under the verdict');
        }
        await t.click(`${QUIZ} [data-restart]`, { visible: true });
        await quizWalk(t, { flags: 'first' });
        await t.expectText(`${QUIZ} [data-verdict]`, /urgent/i, 'an "urgent" verdict');
        await show('quiz-verdict-urgent');
    },
});

/* --- weight loss clinic (modal flows; the booking form is filled, never sent) */

const WL = '#wl-checker';
async function wlOpen(t) {
    await t.click('[data-modal-open="wl-checker"]', { visible: true });
    await t.must(`${WL}.is-open`, {}, 'the suitability checker did not open (#wl-checker.is-open)');
    await t.sleep(300);
}
const wlNext = (t) => t.click(`${WL} [data-next]`, { visible: true });
const wlStep = (t, key) => t.must(`${WL} [data-step="${key}"]`, { visible: true }, `the checker never reached its "${key}" step`);

scenario({
    id: 'weightloss', path: '/weight-loss-clinic',
    names: ['weightloss-step1', 'weightloss-step2', 'weightloss-result', 'weightloss-booking'],
    desc: {
        'weightloss-step1': 'Suitability checker, step 1 (choose a treatment) with the first treatment selected',
        'weightloss-step2': 'Suitability checker, step 2 (BMI) with 34 entered',
        'weightloss-result': 'Suitability checker result: the recommendation for BMI 34 and the chosen treatment',
        'weightloss-booking': 'Booking-call form in the checker, filled with "QA Tool" and NOT submitted',
    },
    async run(t, snap) {
        await wlOpen(t);
        await t.click(`${WL} [data-q="choice"]`, { visible: true, nth: 0 });
        await snap('weightloss-step1');
        await wlNext(t);
        await wlStep(t, 'bmi');
        await t.type(`${WL} [name="bmi"]`, '34');
        await snap('weightloss-step2');
        await wlNext(t);
        await wlStep(t, 'result');
        await t.must(`${WL} [data-rec] h3`, {}, 'no recommendation rendered in [data-rec]');
        await snap('weightloss-result');
        await wlNext(t); // "Book your free call": opens the booking form, sends nothing
        await t.must(`${WL} [data-booking]`, { visible: true }, 'the booking form did not appear');
        await t.type(`${WL} [data-booking] [name="name"]`, 'QA Tool');
        await t.type(`${WL} [data-booking] [name="phone"]`, '07700 900123');
        await snap('weightloss-booking');
    },
});

scenario({
    id: 'weightloss-recommend', path: '/weight-loss-clinic', names: ['weightloss-bmi-helper', 'weightloss-rec-result'],
    desc: {
        'weightloss-bmi-helper': 'BMI step with the "I don\'t know my BMI" helper open: 170cm and 98kg worked out to a BMI',
        'weightloss-rec-result': 'Result of the "recommend me one" route: either form, 100-150 budget, as fast as safely possible',
    },
    async run(t, snap) {
        await wlOpen(t);
        await t.click(`${WL} [data-q="choice"][data-v="rec"]`, { visible: true });
        await wlNext(t);
        await wlStep(t, 'bmi');
        await t.click(`${WL} [data-toggle-hw]`, { visible: true });
        await t.type(`${WL} [name="height_cm"]`, '170');
        await t.type(`${WL} [name="weight_kg"]`, '98');
        await t.expectText(`${WL} [data-bmi-out]`, /BMI is/i, 'a computed BMI');
        await snap('weightloss-bmi-helper');
        await wlNext(t);
        for (const [step, q, v] of [['form', 'form', 'either'], ['budget', 'budget', '150'], ['pace', 'pace', 'fast']]) {
            await wlStep(t, step);
            await t.click(`${WL} [data-q="${q}"][data-v="${v}"]`, { visible: true });
            await wlNext(t);
        }
        await wlStep(t, 'result');
        await t.must(`${WL} [data-rec] h3`, {}, 'no recommendation rendered in [data-rec]');
        await snap('weightloss-rec-result');
    },
});

scenario({
    id: 'weightloss-lowbmi', path: '/weight-loss-clinic', names: ['weightloss-lowbmi'],
    desc: { 'weightloss-lowbmi': 'Checker result when BMI is under 30: "let\'s talk it through" instead of a treatment' },
    async run(t, snap) {
        await wlOpen(t);
        await t.click(`${WL} [data-q="choice"]`, { visible: true, nth: 0 });
        await wlNext(t);
        await wlStep(t, 'bmi');
        await t.type(`${WL} [name="bmi"]`, '24');
        await wlNext(t);
        await wlStep(t, 'result');
        await t.expectText(`${WL} [data-rec]`, /talk it through/i, 'the under-30 BMI message');
        await snap('weightloss-lowbmi');
    },
});

scenario({
    id: 'weightloss-modals', path: '/weight-loss-clinic',
    names: ['weightloss-switch-modal', 'weightloss-waitlist-modal', 'weightloss-info-modal'],
    desc: {
        'weightloss-switch-modal': 'Switch-provider modal',
        'weightloss-waitlist-modal': 'Foundayo waiting-list modal',
        'weightloss-info-modal': 'First treatment "More about..." information modal',
    },
    async run(t, snap) {
        const opens = [
            ['wl-switch', '[data-modal-open="wl-switch"]', 'weightloss-switch-modal'],
            ['wl-waitlist', '[data-modal-open="wl-waitlist"]', 'weightloss-waitlist-modal'],
            ['info', '[data-modal-open^="info-"]', 'weightloss-info-modal'],
        ];
        for (const [id, trigger, name] of opens) {
            await t.click(trigger, { visible: true });
            await t.must('.wl-modal.is-open', {}, `no modal opened from ${trigger}`);
            await t.sleep(300);
            if (id !== 'info') await t.must(`#${id}.is-open`, {}, `#${id} did not open`);
            await snap(name);
            await t.press('Escape');
            await t.sleep(450);
        }
    },
});

/* --- forms and search ------------------------------------------------------ */

scenario({
    id: 'enquiry-form', path: '/services/acne', names: ['form-error', 'form-success'],
    desc: {
        'form-error': 'Enquiry form submitted empty: the server\'s inline field errors (native required-field bubbles bypassed)',
        'form-success': 'Enquiry form submitted with valid details as "QA Tool": the confirmation (writes one service_enquiries row, removed afterwards)',
    },
    async run(t, snap) {
        const F = 'form[data-service-form]';
        await t.must(F, {}, 'no form[data-service-form] on the page');
        // the fields are `required`, so a real empty submit is stopped by the browser's own bubble;
        // novalidate lets the request reach the server so the inline error rendering can be seen
        await t.eval(`document.querySelector(${JSON.stringify(F)}).noValidate = true`);
        t.note('form.noValidate set so an empty submit reaches the server and the inline errors render');
        await t.frame(F, { align: 'top' });
        await t.click(`${F} button[type="submit"]`, { visible: true });
        if (!(await t.waitFor(`${F} [data-error-for]:not([hidden])`, {}, 6000))) throw new QaError('submitting the empty form showed no inline errors ([data-error-for]:not([hidden]))');
        await t.sleep(300);
        await t.frame(F, { align: 'top' });
        await snap('form-error');

        // fill every field with something valid, then submit for real
        const filled = await t.eval(`(() => {
            const f = document.querySelector(${JSON.stringify(F)});
            const set = (el, v) => { el.value = v; el.dispatchEvent(new Event('input', { bubbles: true })); el.dispatchEvent(new Event('change', { bubbles: true })); };
            const out = [];
            for (const el of f.querySelectorAll('input, select, textarea')) {
                if (el.name === 'website' || el.type === 'hidden' || el.type === 'submit' || el.name === '_token') continue;
                if (el.tagName === 'SELECT') { const o = [...el.options].find((x) => x.value); if (o) set(el, o.value); }
                else if (el.name === 'name') set(el, 'QA Tool');
                else if (el.type === 'tel') set(el, '07700 900123');
                else if (el.type === 'email') set(el, 'qa-tool@example.com');
                else if (el.type === 'date') set(el, '1990-01-01');
                else if (el.tagName === 'TEXTAREA') set(el, 'Automated screenshot run. Please ignore.');
                else set(el, 'QA');
                out.push(el.name);
            }
            return out;
        })()`);
        t.note(`filled: ${filled.join(', ')}`);
        enquirySubmitted = true; // from here a row may exist, whatever happens next
        await t.click(`${F} button[type="submit"]`, { visible: true });
        if (!(await t.waitFor(`${F} [data-form-done]:not([hidden])`, {}, 8000))) {
            const err = await t.eval(`[...document.querySelectorAll(${JSON.stringify(F)} + ' [data-error-for]:not([hidden])')].map((e) => e.textContent.trim()).join(' | ')`);
            throw new QaError(`the confirmation never appeared after a valid submit${err ? `; inline errors: ${err}` : ''}`);
        }
        await t.sleep(300);
        await t.frame(`${F} [data-form-done]`, { align: 'center', visible: true });
        await snap('form-success');
    },
});

scenario({
    id: 'conditions-search', path: '/pharmacy-first', names: ['conditions-search'],
    desc: { 'conditions-search': 'Symptom search "burning wee" typed into #condition-search, and the filtered A-Z list' },
    async run(t, snap) {
        await t.type('#condition-search', 'burning wee');
        await t.sleep(300);
        const n = await t.eval("document.querySelectorAll('#a-z li[data-condition]:not([hidden])').length");
        const total = await t.eval("document.querySelectorAll('#a-z li[data-condition]').length");
        t.note(`${n} of ${total} A-Z entries match "burning wee"`);
        if (!n) t.warn('no A-Z entry matches "burning wee"');
        await t.frame('#a-z', { align: 'center' });
        await snap('conditions-search');
    },
});

/* ------------------------------------------------------- enquiry cleanup */

const PHP = process.env.PHP_BIN || 'php';

/** Remove the rows form-success created. Synchronous so it can also run from a signal handler. */
function cleanupEnquiries() {
    const code = "App\\Models\\ServiceEnquiry::where('name','QA Tool')->delete();";
    try {
        const out = execFileSync(PHP, ['artisan', 'tinker', '--execute', code], {
            cwd: ROOT, encoding: 'utf8', timeout: 120000, stdio: ['ignore', 'pipe', 'pipe'],
        });
        return { ok: true, output: out.replace(/\x1b\[[0-9;]*m/g, '').trim().slice(-200) };
    } catch (e) {
        return { ok: false, error: String(e.stderr || e.message).trim().slice(0, 300) };
    }
}

/* ---------------------------------------------------------------- reports */

const mdCell = (s) => String(s ?? '').replace(/\|/g, '\\|').replace(/\s*\n\s*/g, ' ');
const mdLink = (f) => (f ? `[${f}](${encodeURI(f)})` : '');

function writeReports(meta, pages, states) {
    const index = {
        generated: new Date().toISOString(),
        base: CFG.base,
        chrome: meta.chrome,
        workers: meta.workers,
        seconds: +secs(),
        slice: CFG.slice,
        quality: CFG.quality,
        pageViewports: meta.pageViewports,
        pages,
        states,
        skipped: meta.skipped,
        cleanup: meta.cleanup,
    };
    writeFileSync(join(OUT, 'index.json'), `${JSON.stringify(index, null, 2)}\n`);

    const L = [];
    const pagesOk = pages.filter((p) => p.ok).length;
    const statesOk = states.filter((s) => s.ok).length;
    L.push(`# Screenshots of ${CFG.base}`, '');
    L.push(`Generated ${index.generated} with ${meta.chrome || 'Chrome'}, ${meta.workers} worker(s), ${index.seconds}s. DPR 1, JPEG quality ${CFG.quality}.`, '');
    L.push(`- Pages: ${pagesOk}/${pages.length} shot cleanly (${meta.pageViewports.join(', ')})`);
    L.push(`- States: ${statesOk}/${states.length} captured`);
    L.push('- Slices run top to bottom and do not overlap; the header is drawn once, at the top of s01. `top` is the first viewport as a visitor sees it (fixed header).', '');

    const problems = [];
    for (const p of pages) {
        const tag = `\`${p.page}\` @ ${p.viewport}`;
        if (!p.ok) problems.push(`${tag}: ${p.error ?? `HTTP ${p.status}`}`);
        for (const w of p.warnings ?? []) problems.push(`${tag}: ${w}`);
        for (const e of p.errors ?? []) problems.push(`${tag}: browser: ${e}`);
        for (const b of p.brokenImages ?? []) problems.push(`${tag}: broken image ${b}`);
        if (p.overflowX > 0) problems.push(`${tag}: horizontal overflow of ${p.overflowX}px (${(p.overflowers ?? []).slice(0, 3).join('; ')})`);
    }
    for (const s of states) {
        const tag = `state \`${s.name}\` @ ${s.viewport}`;
        if (!s.ok) problems.push(`${tag}: FAILED: ${s.error}`);
        for (const w of s.warnings ?? []) problems.push(`${tag}: ${w}`);
    }
    L.push('## Things to look at', '');
    if (problems.length) problems.forEach((p) => L.push(`- ${p}`));
    else L.push('- nothing flagged');
    L.push('');

    if (pages.length) {
        L.push('## Pages', '');
        L.push('| page | viewport | height | slices | top | status |', '|---|---|---:|---:|---|---|');
        for (const p of pages) {
            L.push(`| \`${p.page}\` | ${p.viewport} | ${p.docHeight || ''} | ${p.slices?.length ?? 0} | ${mdLink(p.top)} | ${p.ok ? 'ok' : `**FAILED** ${mdCell(p.error ?? `HTTP ${p.status}`)}`} |`);
        }
        L.push('');
        for (const p of pages) {
            if (!p.slices?.length) continue;
            L.push(`### \`${p.page}\` @ ${p.viewport}${p.title ? ` — ${mdCell(p.title)}` : ''}`, '');
            L.push(`- top: ${mdLink(p.top)}`);
            L.push(`- slices (${p.docHeight}px tall): ${p.slices.map((s) => `${mdLink(s.file)} y ${s.y}-${s.y + s.height}`).join(' · ')}`, '');
        }
    }

    if (states.length) {
        L.push('## States', '');
        L.push('| state | viewport | result | file | what it shows / notes |', '|---|---|---|---|---|');
        for (const s of states) {
            const extra = (s.extraFiles ?? []).map(mdLink).join(' ');
            const note = [s.desc, ...(s.notes ?? []).map((n) => `note: ${n}`)].filter(Boolean).join(' — ');
            L.push(`| \`${s.name}\` | ${s.viewport} | ${s.ok ? 'ok' : `**FAILED**: ${mdCell(s.error)}`} | ${mdLink(s.file)}${extra ? ` ${extra}` : ''} | ${mdCell(note)} |`);
        }
        L.push('');
    }
    if (meta.cleanup) L.push('## Cleanup', '', `- enquiry rows named "QA Tool": ${meta.cleanup.ok ? `removed (${mdCell(meta.cleanup.output)})` : `NOT removed: ${mdCell(meta.cleanup.error)}`}`, '');
    if (meta.skipped?.length) L.push('## Skipped during crawl', '', ...meta.skipped.map((s) => `- \`${s.path}\`: ${s.status ?? s.error}`), '');
    writeFileSync(join(OUT, 'index.md'), `${L.join('\n')}\n`);
}

/* ------------------------------------------------------------------- main */

function printHelp() {
    const m = /\/\*\*([\s\S]*?)\*\//.exec(readFileSync(SELF, 'utf8'));
    console.log((m ? m[1] : '').split('\n').map((l) => l.replace(/^ \* ?/, '')).join('\n').trim());
}

async function siteReachable() {
    for (let i = 0; i < 3; i++) {
        try {
            const r = await fetch(`${CFG.base}/`, { redirect: 'manual', signal: AbortSignal.timeout(10000) });
            if (r.status < 500) return true;
        } catch { /* try again */ }
        await sleep(1000);
    }
    return false;
}

async function main() {
    if (CFG.help) { printHelp(); return 0; }
    const stateNames = SCENARIOS.flatMap((s) => s.names);
    if (CFG.listStates) {
        const w = Math.max(...stateNames.map((n) => n.length));
        for (const s of SCENARIOS) {
            for (const n of s.names) log(`${n.padEnd(w)}  ${s.path.padEnd(34)} ${s.vps.join(',').padEnd(18)} ${s.desc[n] ?? ''}`);
        }
        return 0;
    }
    if (!CFG.out) die('--out <dir> is required');
    OUT = resolve(CFG.out);
    mkdirSync(OUT, { recursive: true });
    const existing = readdirSync(OUT).filter((f) => /\.(jpg|json|md)$/.test(f));
    if (existing.length) log(`note: ${OUT} already holds ${existing.length} file(s); earlier outputs with other names are left in place`);

    const pageVps = CFG.viewports.split(',').map((v) => parseViewport(v));
    const stateVpOverride = CFG.stateViewports ? CFG.stateViewports.split(',').map((v) => parseViewport(v)) : null;

    // what was asked for
    const wanted = new Set();
    const slugFilters = [];
    const unknown = [];
    if (CFG.only) {
        for (const g of CFG.only) {
            const re = globToRe(g);
            const hits = stateNames.filter((n) => re.test(n));
            if (hits.length) hits.forEach((n) => wanted.add(n));
            else slugFilters.push({ g, re });
        }
    } else if (CFG.states) {
        stateNames.forEach((n) => wanted.add(n));
    }
    const wantPages = !CFG.noPages && (CFG.only ? slugFilters.length > 0 : true);

    if (!(await siteReachable())) die(`cannot reach ${CFG.base}. Is the QA server running?`);

    let pages = [];
    let skipped = [];
    if (wantPages) {
        if (CFG.pages) {
            pages = CFG.pages;
        } else {
            const c = await crawl(CFG.base);
            pages = c.list;
            skipped = c.skipped;
            log(`crawled ${pages.length} pages from ${CFG.base}/${skipped.length ? ` (${skipped.length} skipped)` : ''}`);
        }
        if (slugFilters.length) {
            const keep = pages.filter((p) => slugFilters.some((f) => f.re.test(slugOf(p))));
            for (const f of slugFilters) if (!pages.some((p) => f.re.test(slugOf(p)))) unknown.push(f.g);
            pages = keep;
        }
    } else if (CFG.only) {
        for (const f of slugFilters) unknown.push(f.g);
    }
    if (unknown.length) log(`warning: --only name(s) match no state and no page: ${unknown.join(', ')}  (see --list-states)`);

    const jobs = [];
    for (const sc of SCENARIOS) {
        const names = sc.names.filter((n) => wanted.has(n));
        if (!names.length) continue;
        const vps = stateVpOverride ?? sc.vps.map((v) => parseViewport(v));
        for (const vp of vps) jobs.push({ kind: 'state', sc, vp, names });
    }
    for (const path of pages) for (const vp of pageVps) jobs.push({ kind: 'page', path, vp });
    if (!jobs.length) { log('nothing to do (no pages and no states selected). Try --states, or see --help.'); return CFG.only ? 2 : 0; }

    const nWorkers = Math.min(CFG.jobs, jobs.length);
    log(`${jobs.length} job(s): ${jobs.filter((j) => j.kind === 'page').length} page shots, ${jobs.filter((j) => j.kind === 'state').length} state scenarios; ${nWorkers} Chrome instance(s) from port ${CFG.port}`);
    const workers = Array.from({ length: nWorkers }, (_, i) => new Worker(i));
    const started = await Promise.allSettled(workers.map((w) => w.start()));
    started.forEach((s, i) => { if (s.status === 'rejected') log(`worker ${i} did not start: ${s.reason?.message ?? s.reason}`); });
    const live = workers.filter((_, i) => started[i].status === 'fulfilled');
    if (!live.length) die('no Chrome instance could be started');
    const chrome = live[0].version;

    const outcome = new Array(jobs.length);
    let finished = 0;
    const tick = (text) => log(`[${String(++finished).padStart(String(jobs.length).length)}/${jobs.length}] ${text}`);

    await runPool(live, jobs, async (w, job, i) => {
        const began = Date.now();
        if (job.kind === 'page') {
            try {
                const rec = await withRetry(w, `${job.path} @${job.vp.key}`, 240000, (tab) => pageShots(tab, job.path, job.vp));
                outcome[i] = rec;
                const flags = [rec.errors.length && `${rec.errors.length} browser error(s)`, rec.brokenImages.length && `${rec.brokenImages.length} broken image(s)`, rec.overflowX > 0 && `overflow ${rec.overflowX}px`, rec.warnings.length && `${rec.warnings.length} warning(s)`].filter(Boolean);
                tick(`page  ${job.path.padEnd(38)} ${job.vp.key.padEnd(9)} ${rec.ok ? 'ok    ' : 'FAILED'} ${String(rec.slices.length).padStart(2)} slices ${String(rec.docHeight).padStart(6)}px  ${secs(began)}s${flags.length ? `   ! ${flags.join(', ')}` : ''}`);
            } catch (e) {
                outcome[i] = { page: job.path, slug: slugOf(job.path), viewport: job.vp.key, ok: false, error: e.message, status: 0, docHeight: 0, files: [], top: null, slices: [], overflowX: 0, errors: [], brokenImages: [], warnings: [] };
                tick(`page  ${job.path.padEnd(38)} ${job.vp.key.padEnd(9)} FAILED  ${e.message}`);
            }
        } else {
            let results;
            try {
                results = await withRetry(w, `${job.sc.id} @${job.vp.key}`, 240000, (tab) => runScenario(tab, job.sc, job.vp, job.names));
            } catch (e) {
                results = job.names.map((n) => ({
                    name: n, viewport: job.vp.key, path: job.sc.path, ok: false, file: null, extraFiles: [], error: e.message,
                    desc: job.sc.desc[n] ?? '', notes: [], warnings: [],
                }));
            }
            outcome[i] = results;
            const bad = results.filter((r) => !r.ok);
            const warned = results.reduce((n, r) => n + (r.warnings?.length ?? 0), 0);
            tick(`state ${job.sc.id.padEnd(22)} ${job.vp.key.padEnd(9)} ${bad.length ? 'FAILED' : 'ok    '} ${results.length - bad.length}/${results.length} shots  ${secs(began)}s${warned ? `   ! ${warned} warning(s)` : ''}`);
            for (const r of bad) log(`        x ${r.name}: ${r.error}`);
            for (const r of results) for (const wmsg of r.warnings ?? []) log(`        ! ${r.name}: ${wmsg}`);
        }
    });

    await Promise.all(workers.map((w) => w.stop()));

    // remove the enquiry row(s) form-success created
    let cleanup = null;
    if (enquirySubmitted) {
        if (CFG.cleanup) {
            cleanup = cleanupEnquiries();
            log(cleanup.ok ? 'cleanup: removed the "QA Tool" enquiry rows' : `cleanup FAILED: ${cleanup.error}`);
        } else {
            cleanup = { ok: false, error: 'skipped (--no-cleanup)' };
            log(`cleanup skipped: run  php artisan tinker --execute "App\\Models\\ServiceEnquiry::where('name','QA Tool')->delete();"`);
        }
    }

    const pageRecs = outcome.filter((o) => o && !Array.isArray(o));
    const stateRecs = outcome.filter(Array.isArray).flat();
    writeReports({ chrome, workers: live.length, pageViewports: pageVps.map((v) => v.key), skipped, cleanup }, pageRecs, stateRecs);

    const badPages = pageRecs.filter((p) => !p.ok);
    const badStates = stateRecs.filter((s) => !s.ok);
    log('');
    log(`pages : ${pageRecs.length - badPages.length}/${pageRecs.length} ok, ${pageRecs.reduce((n, p) => n + (p.slices?.length ?? 0), 0)} slices`);
    log(`states: ${stateRecs.length - badStates.length}/${stateRecs.length} ok`);
    for (const p of badPages) log(`  FAILED page ${p.page} @${p.viewport}: ${p.error ?? `HTTP ${p.status}`}`);
    for (const s of badStates) log(`  FAILED state ${s.name} @${s.viewport}: ${s.error}`);
    log(`done in ${secs()}s -> ${join(OUT, 'index.md')}`);
    return CFG.strict && (badPages.length || badStates.length) ? 1 : 0;
}

/* ------------------------------------------------------ process plumbing */

const bail = (code) => () => {
    killAllSync();
    if (enquirySubmitted && CFG.cleanup) cleanupEnquiries();
    process.exit(code);
};
process.on('SIGINT', bail(130));
process.on('SIGTERM', bail(143));
process.on('exit', killAllSync);

main().then(
    (code) => process.exit(code),
    (e) => {
        console.error(e?.stack ?? e);
        killAllSync();
        if (enquirySubmitted && CFG.cleanup) cleanupEnquiries();
        process.exit(1);
    },
);
