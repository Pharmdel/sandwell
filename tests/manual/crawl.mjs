/**
 * Link and asset crawler.
 *
 * Walks every internal page from "/", following links and checking that each
 * page, each referenced asset, and each in-page #fragment target actually
 * resolves. Catches the things a visual pass misses: a renamed slug that left a
 * dead link behind, an image referenced but never generated, an anchor pointing
 * at a section that no longer exists.
 *
 *   node tests/manual/crawl.mjs [baseUrl]
 */
const BASE = (process.argv[2] ?? 'http://127.0.0.1:8899').replace(/\/$/, '');

const pages = new Map();     // path -> html
const assets = new Set();
const fragments = new Map(); // path -> Set(fragment)
const broken = [];
const queue = ['/'];
const seen = new Set(queue);

// Relative hrefs -- especially bare "#section" links -- resolve against the page
// they appear on, not against the site root. Resolving them against BASE reports
// every in-page anchor as a broken link on "/".
const abs = (href, from = '/') => {
    try {
        const u = new URL(href, BASE + from);
        return u.origin === BASE ? u : null;
    } catch { return null; }
};

while (queue.length) {
    const path = queue.shift();
    let res, html = '';
    try {
        res = await fetch(BASE + path, { redirect: 'manual' });
        if (res.status >= 300 && res.status < 400) {
            // a redirect is fine as long as it lands somewhere real
            const to = abs(res.headers.get('location') ?? '', path);
            if (to && !seen.has(to.pathname)) { seen.add(to.pathname); queue.push(to.pathname); }
            continue;
        }
        html = await res.text();
    } catch (e) {
        broken.push(['PAGE', path, String(e)]);
        continue;
    }
    if (res.status !== 200) { broken.push(['PAGE', path, res.status]); continue; }
    pages.set(path, html);

    for (const m of html.matchAll(/(?:href|src)="([^"]+)"/g)) {
        const raw = m[1];
        if (raw.startsWith('mailto:') || raw.startsWith('tel:') || raw.startsWith('data:')) continue;
        const u = abs(raw, path);
        if (!u) continue;
        if (u.hash) {
            if (!fragments.has(u.pathname)) fragments.set(u.pathname, new Set());
            fragments.get(u.pathname).add(u.hash.slice(1));
        }
        const isAsset = /\.(png|jpe?g|webp|svg|css|js|ico|woff2?)$/i.test(u.pathname);
        if (isAsset) { assets.add(u.pathname); continue; }
        if (!seen.has(u.pathname)) { seen.add(u.pathname); queue.push(u.pathname); }
    }
}

for (const a of assets) {
    const r = await fetch(BASE + a, { method: 'HEAD' });
    if (r.status !== 200) broken.push(['ASSET', a, r.status]);
}

let fragChecked = 0;
for (const [path, ids] of fragments) {
    const html = pages.get(path);
    if (!html) continue;
    for (const id of ids) {
        fragChecked++;
        if (!html.includes(`id="${id}"`)) broken.push(['FRAGMENT', `${path}#${id}`, 'no matching id']);
    }
}

console.log(`pages: ${pages.size}   assets: ${assets.size}   fragments checked: ${fragChecked}`);
if (broken.length) {
    console.log('BROKEN:');
    for (const b of broken) console.log('  ', b.join('  '));
    process.exit(1);
}
console.log('all pages + assets OK');
console.log('all fragment targets OK');
