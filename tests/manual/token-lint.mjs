#!/usr/bin/env node
/**
 * Token lint.
 *
 * Tailwind v4 fails SILENTLY: a utility that names a colour token which does not
 * exist (text-brand-orange after a rename, say) is not an error, it simply emits
 * no CSS, and the element renders in whatever it would have been anyway. Nothing
 * in the build, the crawler or a markup grep will notice. This lint is the check
 * that does.
 *
 * It reads the defined tokens out of the @theme block in resources/css/app.css,
 * then scans every class string in the Blade views, the JS templates and the CSS:
 *
 *   undefined colour token   text-brand-orange, hover:bg-brand-navy/40 ...
 *   stock Tailwind colour    text-slate-300 (the theme switches these off)
 *   undefined shadow         shadow-moss-glow
 *   undefined var()          var(--color-brand-peach)
 *   off-palette literal      an rgb()/rgba()/#hex value that is not a token colour,
 *                            white, black, or one of Google's four logo colours
 *
 *   node tests/manual/token-lint.mjs              report, exit 1 on any finding
 *   node tests/manual/token-lint.mjs --verbose    also list tokens and their usage
 *   node tests/manual/token-lint.mjs --self-test  prove the lint can actually fail
 */
import { readFileSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, relative, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '../..');
const VERBOSE = process.argv.includes('--verbose');
const SELF_TEST = process.argv.includes('--self-test');

/* ---------------------------------------------------------------- tokens */

const css = readFileSync(join(ROOT, 'resources/css/app.css'), 'utf8');
const themeAt = css.indexOf('@theme');
const themeBlock = css.slice(themeAt, css.indexOf('\n}\n', themeAt));

const colours = new Set(['white', 'black', 'transparent', 'current', 'inherit']);
const hexByName = new Map();
for (const [, name, value] of themeBlock.matchAll(/--color-([a-z0-9-]+)\s*:\s*([^;]+);/g)) {
    colours.add(name);
    const hex = value.trim().match(/^#([0-9a-fA-F]{6})$/);
    if (hex) hexByName.set(name, hex[1].toLowerCase());
}

const shadows = new Set();
for (const [, name] of themeBlock.matchAll(/--shadow-([a-z0-9-]+)\s*:/g)) shadows.add(name);

// every literal that is allowed to appear, as lowercase 6-digit hex
const allowedHex = new Set(['ffffff', '000000', '4285f4', '34a853', 'fbbc05', 'ea4335', ...hexByName.values()]);

const toHex = (r, g, b) => [r, g, b].map((n) => Number(n).toString(16).padStart(2, '0')).join('');
const expandHex = (h) => (h.length === 3 ? [...h].map((c) => c + c).join('') : h.slice(0, 6)).toLowerCase();

/* ---------------------------------------------------------------- scanner */

const PREFIXES = [
    'text', 'bg', 'border', 'border-t', 'border-b', 'border-l', 'border-r', 'border-x', 'border-y',
    'ring', 'ring-offset', 'outline', 'fill', 'stroke', 'from', 'via', 'to',
    'divide', 'decoration', 'accent', 'caret', 'placeholder', 'shadow',
];
// Longest first: with `ring` ahead of `ring-offset`, the regex takes `ring` and swallows
// "offset-" into the value, so ring-offset-brand-peach was never judged. The same
// applies to border vs border-t.
const prefixAlt = [...PREFIXES]
    .sort((a, b) => b.length - a.length)
    .map((p) => p.replace('-', '\\-'))
    .join('|');

const UTIL = new RegExp(
    `(?<![A-Za-z0-9_\\-.:\\[/])((?:[a-z0-9\\-\\[\\]&*=_>:'"()]+:)*)(!?)(${prefixAlt})-([a-z][a-z0-9\\-]*)(?:\\/(\\d+|\\[[^\\]]+\\]))?(?![A-Za-z0-9_\\-\\]])`,
    'g',
);

const STOCK = /^(slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-\d{2,3}$/;

const NOT_COLOUR = new Set([
    'xs', 'sm', 'base', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', '8xl', '9xl',
    'left', 'center', 'right', 'justify', 'start', 'end', 'wrap', 'nowrap', 'balance', 'pretty',
    'ellipsis', 'clip', 'top', 'bottom', 'middle',
    'solid', 'dashed', 'dotted', 'double', 'none', 'hidden', 'collapse', 'separate', 'spacing',
    'inset', 'offset', 'auto', 'from', 'clone', 'slice',
    'inner', '2xs', 'md',
    'fixed', 'local', 'scroll', 'cover', 'contain', 'repeat', 'no', 'origin', 'blend',
    'gradient', 'linear', 'radial', 'conic', 'size', 'position', 'image', 'opacity',
]);

// Literal colours. In CSS the space-separated form rgb(15 41 15 / 0.06) is common.
const RGB_LITERAL = /rgba?\(\s*(\d{1,3})[\s,]+(\d{1,3})[\s,]+(\d{1,3})(?:[\s,/]+[\d.]+%?)?\s*\)/g;
const HEX_LITERAL = /(?<![A-Za-z0-9_&-])#([0-9a-fA-F]{8}|[0-9a-fA-F]{6}|[0-9a-fA-F]{3,4})\b(?![-\w])/g;

/**
 * Lint one file's text. Returns [{line, kind, text}].
 * `inThemeBlock` lines are skipped for literals: that is where the tokens are defined.
 */
export function lintText(text, { isCss = false } = {}) {
    const out = [];
    const lines = text.split('\n');
    let inTheme = false;
    let depth = 0;

    lines.forEach((line, i) => {
        const n = i + 1;

        if (isCss) {
            if (!inTheme && /@theme\b/.test(line)) { inTheme = true; depth = 0; }
            if (inTheme) {
                depth += (line.match(/{/g) ?? []).length - (line.match(/}/g) ?? []).length;
                if (depth <= 0 && /}/.test(line)) inTheme = false;
                return;
            }
        }

        // var(--color-*) references
        for (const [, name] of line.matchAll(/var\(--color-([a-z0-9-]+)\)/g)) {
            if (!colours.has(name)) out.push({ line: n, kind: 'undefined var()', text: `--color-${name}` });
        }

        // literal colours that are not part of the palette
        for (const m of line.matchAll(RGB_LITERAL)) {
            const [, r, g, b] = m;
            if (+r > 255 || +g > 255 || +b > 255) continue;
            if (!allowedHex.has(toHex(r, g, b))) out.push({ line: n, kind: 'off-palette literal', text: m[0] });
        }
        for (const m of line.matchAll(HEX_LITERAL)) {
            const h = expandHex(m[1]);
            if (!allowedHex.has(h)) out.push({ line: n, kind: 'off-palette literal', text: m[0] });
        }

        if (isCss) return; // utility classes are not written in the stylesheet itself

        for (const m of line.matchAll(UTIL)) {
            const [full, , , prefix, value] = m;

            if (STOCK.test(value)) { out.push({ line: n, kind: 'stock Tailwind colour', text: full }); continue; }

            if (prefix === 'shadow') {
                const looksCustom = /^(editorial|moss|primary|orange|glow|brand)/.test(value);
                if (looksCustom && !shadows.has(value)) out.push({ line: n, kind: 'undefined shadow', text: full });
                continue;
            }

            const brandish = /^(brand|footer)(-|$)/.test(value);
            const oldBrand = /^(navy|orange|peach|apricot|sage)(-|$)/.test(value);
            if (!brandish && !oldBrand && !colours.has(value)) continue;
            if (NOT_COLOUR.has(value)) continue;

            if (!colours.has(value)) out.push({ line: n, kind: 'undefined colour token', text: full });
        }
    });
    return out;
}

/* -------------------------------------------------------------- self-test */

if (SELF_TEST) {
    const cases = [
        // [description, fixture, isCss, expected kinds (sorted), expected count]
        ['undefined token', '<a class="text-brand-orange">', false, ['undefined colour token']],
        ['undefined token behind a variant and opacity', '<a class="hover:bg-brand-navy/40">', false, ['undefined colour token']],
        ['undefined token in a group variant', '<a class="group-hover:text-brand-peach-line">', false, ['undefined colour token']],
        ['removed token that used to exist', '<a class="bg-brand-moss-soft">', false, ['undefined colour token']],
        ['removed footer token', '<p class="text-footer-tagline">', false, ['undefined colour token']],
        ['undefined ring-offset token', '<a class="focus-visible:ring-offset-brand-peach">', false, ['undefined colour token']],
        ['undefined token inside a JS template string', 'el.innerHTML = `<b class="text-brand-sage">x</b>`;', false, ['undefined colour token']],
        ['stock Tailwind palette', '<p class="text-slate-300 bg-emerald-50">', false, ['stock Tailwind colour', 'stock Tailwind colour']],
        ['undefined shadow', '<a class="hover:shadow-moss-glow">', false, ['undefined shadow']],
        ['undefined var()', '.x { color: var(--color-brand-orange); }', true, ['undefined var()']],
        ['off-palette hex literal', '<i style="color:#7c3d0d">', false, ['off-palette literal']],
        ['off-palette rgb literal', 'box-shadow: 0 2px 8px rgb(37 41 85 / 0.1);', true, ['off-palette literal']],
        ['off-palette rgba in an arbitrary value', '<div class="bg-[radial-gradient(circle,rgba(42,49,112,0.55),transparent)]">', false, ['off-palette literal']],
        // negatives: all of these must come back clean
        ['VALID: tokens, variants, sizes, custom shadow', '<a class="text-brand-forest bg-brand-primary hover:bg-brand-primary-hover text-sm text-center shadow-editorial-card focus-visible:ring-brand-moss">', false, []],
        ['VALID: opacity suffix and arbitrary size', '<i class="bg-brand-forest/5 border-brand-forest/35 border-t-[3px] text-[11px]">', false, []],
        ['VALID: pure white and black alphas', 'background: rgb(255 255 255 / 0.5); box-shadow: 0 2px 12px rgb(0 0 0 / 0.15);', true, []],
        ['VALID: primary as rgba', 'bg-[radial-gradient(circle,rgba(136,231,136,0.55),transparent_70%)]', false, []],
        ['VALID: forest as space-separated rgb', 'box-shadow: 0 4px 20px rgb(15 41 15 / 0.06);', true, []],
        ['VALID: Google logo colours', '<path fill="#4285F4"/><path fill="#EA4335"/>', false, []],
        ['VALID: token definitions are skipped', '@theme {\n  --color-brand-x: #123456;\n}', true, []],
        ['VALID: HTML entity is not a hex colour', 'Tom &#8212; Jerry &#x2014;', false, []],
    ];

    let failed = 0;
    for (const [name, fixture, isCss, expected] of cases) {
        const got = lintText(fixture, { isCss }).map((f) => f.kind).sort();
        const want = [...expected].sort();
        const ok = got.length === want.length && got.every((k, i) => k === want[i]);
        if (!ok) failed++;
        console.log(`  ${ok ? 'PASS' : 'FAIL'}  ${name}${ok ? '' : `\n          expected ${JSON.stringify(want)}\n          got      ${JSON.stringify(got)}`}`);
    }
    console.log(`\n${failed ? `SELF-TEST FAILED: ${failed} of ${cases.length}` : `SELF-TEST OK: ${cases.length} cases, the lint detects seeded failures and accepts valid input`}`);
    process.exit(failed ? 1 : 0);
}

/* ------------------------------------------------------------------- scan */

function* walk(dir) {
    for (const entry of readdirSync(dir)) {
        const full = join(dir, entry);
        if (statSync(full).isDirectory()) yield* walk(full);
        else yield full;
    }
}

const files = [
    ...walk(join(ROOT, 'resources/views')),
    ...walk(join(ROOT, 'resources/js')),
    join(ROOT, 'resources/css/app.css'),
].filter((f) => /\.(php|js|css)$/.test(f) && !f.endsWith('welcome.blade.php'));

const findings = [];
const used = new Map();

for (const file of files) {
    const rel = relative(ROOT, file);
    const text = readFileSync(file, 'utf8');
    for (const f of lintText(text, { isCss: file.endsWith('.css') })) findings.push({ rel, ...f });

    if (VERBOSE && !file.endsWith('.css')) {
        for (const m of text.matchAll(UTIL)) {
            const v = m[4];
            if (/^(brand|footer)(-|$)/.test(v) || ['white', 'black', 'transparent'].includes(v)) used.set(v, (used.get(v) ?? 0) + 1);
        }
    }
}

if (VERBOSE) {
    console.log(`defined colour tokens (${colours.size}):`);
    console.log('  ' + [...colours].sort().join(', '));
    console.log(`\nused as utilities (${used.size}):`);
    for (const [name, n] of [...used].sort((a, b) => b[1] - a[1])) {
        console.log(`  ${String(n).padStart(4)}  ${name}${colours.has(name) ? '' : '   <-- UNDEFINED'}`);
    }
    const unused = [...colours].filter((c) => !used.has(c) && !/^(black|current|inherit)$/.test(c));
    console.log(`\ndefined but not used as a utility (${unused.length}): ${unused.sort().join(', ') || 'none'}\n`);
}

console.log(`scanned ${files.length} files, ${colours.size} defined colour tokens`);

if (findings.length === 0) {
    console.log('OK: no undefined tokens, no stock Tailwind colours, no undefined shadows, no off-palette literals');
    process.exit(0);
}

const byKind = new Map();
for (const f of findings) {
    if (!byKind.has(f.kind)) byKind.set(f.kind, []);
    byKind.get(f.kind).push(f);
}
for (const [kind, list] of byKind) {
    console.log(`\n${kind} (${list.length})`);
    for (const f of list.slice(0, 50)) console.log(`  ${f.rel}:${f.line}  ${f.text}`);
    if (list.length > 50) console.log(`  ... and ${list.length - 50} more`);
}
console.log(`\nFAILED: ${findings.length} finding(s)`);
process.exit(1);
