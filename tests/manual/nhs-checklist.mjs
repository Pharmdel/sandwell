/**
 * NHS service page checklist.
 *
 * Every NHS page in the menu is checked against the features the group publishes
 * on the equivalent page of sandwellpharmacygroup.co.uk. Static checks read the
 * HTML; live checks drive a real browser, because the mistakes that actually slip
 * through are the ones markup alone cannot show -- a Tailwind class that was never
 * compiled, a JS hook renamed in the template but not the script, a widget that
 * renders but throws the moment you use it.
 *
 *   node tests/manual/nhs-checklist.mjs [baseUrl]
 */
import { spawn } from 'node:child_process';

const BASE = process.argv[2] ?? 'http://127.0.0.1:8899';
const CHROME = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const PORT = 9980 + Math.floor(Math.random() * 15);

/* ---------------------------------------------------------------- the checklist */

const PAGES = [
    {
        page: 'Pharmacy First & Minor Illness',
        path: '/pharmacy-first',
        html: [
            ['19 condition cards', (h) => count(h, /<a [^>]*data-condition=/g) === 19],
            ['7 Pharmacy First tags', (h) => count(h, />Pharmacy First<\/span>/g) === 7],
            ['12 minor ailment tags', (h) => count(h, />Minor ailments<\/span>/g) === 12],
            ['every card names an age range', (h) => count(h, /free on the NHS<\/p>/g) === 19],
            ['full A–Z below the grid', (h) => count(h, /<li data-condition=/g) >= 40],
            ['symptom search', (h) => h.includes('id="condition-search"')],
            ['minor ailments section', (h) => h.includes('id="minor-ailments"')],
            ['callback form', (h) => h.includes('data-service-form')],
        ],
        live: [
            ['card images all load', `[...document.querySelectorAll('a[data-condition] img')].every(i => i.complete && i.naturalWidth > 0)`],
            ['Pharmacy First tag is NHS blue', `getComputedStyle([...document.querySelectorAll('a[data-condition] span')].find(s => s.textContent.trim() === 'Pharmacy First')).color === 'rgb(0, 94, 184)'`],
            ['minor ailment tag is brand orange', `getComputedStyle([...document.querySelectorAll('a[data-condition] span')].find(s => s.textContent.trim() === 'Minor ailments')).color === 'rgb(251, 120, 8)'`],
            ['search filters the A–Z', `(() => { const i = document.getElementById('condition-search'); i.value = 'earache'; i.dispatchEvent(new Event('input', { bubbles: true })); return [...document.querySelectorAll('li[data-condition]')].filter(e => !e.hidden).length < 5; })()`],
        ],
    },
    {
        page: 'Symptom check (per condition)',
        path: '/conditions/sinusitis',
        html: [
            ['condition data embedded', (h) => h.includes('id="condition-data"')],
            ['symptom test section', (h) => h.includes('id="symptom-test"')],
            ['start button', (h) => h.includes('data-start')],
        ],
        live: [
            ['quiz starts', `(() => { document.querySelector('[data-start]').click(); return document.querySelectorAll('[data-check]').length > 0; })()`],
            ['progress stepper renders', `document.querySelectorAll('[aria-label="Progress"] li').length >= 4`],
            ['Next is blocked until a symptom is ticked', `document.querySelector('[data-next]').disabled === true`],
            ['ticking a symptom unblocks Next', `(() => { document.querySelector('[data-check]').click(); return document.querySelector('[data-next]').disabled === false; })()`],
        ],
    },
    {
        page: 'Repeat Prescriptions',
        path: '/repeat-prescriptions',
        html: [
            ['postcode delivery checker', (h) => h.includes('data-postcode-check')],
            ['prescription request form', (h) => h.includes('name="medications"')],
            ['collect-or-deliver choice', (h) => h.includes('name="delivery"')],
            ['nominate section', (h) => h.includes('id="nominate"')],
        ],
        live: [
            ['served postcode is accepted', `(() => { const r = document.querySelector('[data-postcode-check]'); r.querySelector('[data-postcode-input]').value = 'B70 7RW'; r.querySelector('[data-postcode-run]').click(); return r.querySelector('[data-postcode-result] b').textContent.startsWith('Yes'); })()`],
            ['outward code is parsed, not the inward', `(() => { const r = document.querySelector('[data-postcode-check]'); r.querySelector('[data-postcode-input]').value = 'DY8 1AA'; r.querySelector('[data-postcode-run]').click(); return r.querySelector('[data-postcode-result] b').textContent.includes('Stourbridge'); })()`],
            ['unserved postcode still offers a route', `(() => { const r = document.querySelector('[data-postcode-check]'); r.querySelector('[data-postcode-input]').value = 'SW1A 1AA'; r.querySelector('[data-postcode-run]').click(); return r.querySelector('[data-postcode-result]').textContent.includes('0121 500 5756'); })()`],
        ],
    },
    {
        page: 'Flu & Covid Jabs',
        path: '/services/flu-covid-vaccinations',
        html: [
            ['four season options', (h) => h.includes('What we offer this season')],
            ['eligibility checker', (h) => h.includes('data-flu-check')],
            ['booking form', (h) => h.includes('data-service-form')],
            ['four vaccine choices', (h) => count(h, /<option value="(NHS flu|Private flu|NHS Covid|Flu \+ Covid)/g) === 4],
        ],
        live: [
            ['65+ qualifies for a free flu jab', `(() => { const r = document.querySelector('[data-flu-check]'); r.querySelector('[data-flu-age]').value = '70'; r.querySelectorAll('[data-flu-reason]').forEach(c => c.checked = false); r.querySelector('[data-flu-run]').click(); return r.querySelector('[data-flu-result]').textContent.includes('Free NHS flu jab'); })()`],
            ['75+ also qualifies for Covid', `(() => { const r = document.querySelector('[data-flu-check]'); r.querySelector('[data-flu-age]').value = '80'; r.querySelector('[data-flu-run]').click(); return r.querySelector('[data-flu-result]').textContent.includes('Free NHS Covid-19'); })()`],
            ['healthy 30-year-old offered the £30 private jab', `(() => { const r = document.querySelector('[data-flu-check]'); r.querySelector('[data-flu-age]').value = '30'; r.querySelectorAll('[data-flu-reason]').forEach(c => c.checked = false); r.querySelector('[data-flu-run]').click(); return r.querySelector('[data-flu-result]').textContent.includes('£30'); })()`],
        ],
    },
    {
        page: 'Contraception',
        path: '/services/contraception',
        html: [
            ['EHC three-step', (h) => h.includes('Come in as soon as possible')],
            ['pills we supply', (h) => h.includes('Desogestrel')],
            ['eligibility panel', (h) => h.includes('Eligibility')],
            ['consultation form', (h) => h.includes('data-service-form')],
        ],
        live: [
            ['form asks which service', `document.querySelectorAll('[name="detail"] option').length === 4`],
            ['date of birth is required', `document.querySelector('[name="dob"]').required === true`],
        ],
    },
    {
        page: 'MDS Blister Packs',
        path: '/services/mds-trays',
        html: [
            ['five-question checker', (h) => count(h, /data-mds-step=/g) === 6],
            ['both routes priced', (h) => h.includes('£25 per month')],
            ['details form', (h) => h.includes('data-service-form')],
        ],
        live: [
            ['priority route verdict', `(() => { const r = document.querySelector('[data-mds-check]'); const c = (s, v) => r.querySelector('[data-mds-step="' + s + '"] [data-mds-option="' + v + '"]').click(); c('items','7+'); c('who','carer'); c('issue','weekly'); c('miss','often'); c('route','priority'); return r.querySelector('[data-mds-result] b').textContent.includes('Priority setup'); })()`],
            ['free NHS route verdict', `(() => { const r = document.querySelector('[data-mds-check]'); const c = (s, v) => r.querySelector('[data-mds-step="' + s + '"] [data-mds-option="' + v + '"]').click(); c('items','1-3'); c('who','myself'); c('issue','monthly'); c('miss','rarely'); c('route','free'); return r.querySelector('[data-mds-result] b').textContent.includes('Free NHS route'); })()`],
        ],
    },
    {
        page: 'Blood Pressure Checks',
        path: '/services/hypertension',
        html: [
            ['four offerings', (h) => h.includes('24-hour monitoring')],
            ['free / £5 / £50 all priced', (h) => h.includes('£5') && h.includes('£50')],
            ['explainer', (h) => h.includes('High blood pressure, explained')],
            ['symptom list', (h) => h.includes('You may have high blood pressure')],
            ['qualify checker', (h) => h.includes('data-bp-check')],
        ],
        live: [
            ['40+, no meds, no recent check qualifies', `(() => { const r = document.querySelector('[data-bp-check]'); const c = (s, v) => r.querySelector('[data-bp-step="' + s + '"] [data-bp-option="' + v + '"]').click(); c('age','yes'); c('meds','no'); c('last5','no'); return r.querySelector('[data-bp-result]').textContent.includes('you qualify'); })()`],
            ['under 40 is told why not', `(() => { const r = document.querySelector('[data-bp-check]'); r.querySelector('[data-bp-restart]')?.click(); const c = (s, v) => r.querySelector('[data-bp-step="' + s + '"] [data-bp-option="' + v + '"]').click(); c('age','no'); c('meds','no'); c('last5','no'); return r.querySelector('[data-bp-result]').textContent.includes('40 and over'); })()`],
        ],
    },
];

/* Applies to every page, because these are the failures that recur. */
const UNIVERSAL = [
    ['no JavaScript errors', null],
    ['no horizontal overflow', `document.documentElement.scrollWidth <= window.innerWidth`],
    // Assert the observer fired, not the exact opacity: reading opacity mid
    // transition returns something like "0.43" and fails a page that is fine.
    ['every reveal animation fires', `[...document.querySelectorAll('.reveal')].filter(e => !e.classList.contains('is-visible') && parseFloat(getComputedStyle(e).opacity) < 0.9).length === 0`],
    ['no unstyled Tailwind leftovers', `![...document.querySelectorAll('[class*="brand-"]')].some(e => getComputedStyle(e).color === 'rgb(0, 0, 0)' && e.className.includes('text-brand'))`],
];

const count = (h, re) => (h.match(re) ?? []).length;

/* ------------------------------------------------------------------- the runner */

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
const chrome = spawn(CHROME, ['--headless=new', `--remote-debugging-port=${PORT}`, '--disable-gpu',
    '--hide-scrollbars', '--no-first-run', `--user-data-dir=/tmp/cdp-check-${Date.now()}`, 'about:blank'], { stdio: 'ignore' });
await sleep(3000);

const targets = await (await fetch(`http://127.0.0.1:${PORT}/json/list`)).json();
const ws = new WebSocket(targets.find((t) => t.type === 'page').webSocketDebuggerUrl);
await new Promise((r) => (ws.onopen = r));

let id = 0;
const pending = new Map();
let pageErrors = [];
ws.onmessage = (e) => {
    const m = JSON.parse(e.data);
    if (m.method === 'Runtime.exceptionThrown') pageErrors.push(m.params.exceptionDetails.text);
    if (m.id && pending.has(m.id)) { pending.get(m.id)(m.result); pending.delete(m.id); }
};
const send = (method, params = {}) => new Promise((res) => { const i = ++id; pending.set(i, res); ws.send(JSON.stringify({ id: i, method, params })); });
const evaluate = async (expr) => {
    const r = await send('Runtime.evaluate', { expression: `(() => { try { return JSON.stringify(${expr}); } catch (e) { return JSON.stringify('ERROR: ' + e.message); } })()`, returnByValue: true });
    if (r.exceptionDetails) return `ERROR: ${r.exceptionDetails.text}`;
    return JSON.parse(r.result.value);
};

await send('Page.enable');
await send('Runtime.enable');
await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 1000, deviceScaleFactor: 1, mobile: false });

let passed = 0;
let failed = [];

for (const spec of PAGES) {
    console.log(`\n\x1b[1m${spec.page}\x1b[0m  ${spec.path}`);

    const html = await (await fetch(BASE + spec.path)).text();
    for (const [name, test] of spec.html) {
        const ok = test(html);
        console.log(`  ${ok ? '\x1b[32m✓\x1b[0m' : '\x1b[31m✗\x1b[0m'} ${name}`);
        ok ? passed++ : failed.push(`${spec.page}: ${name}`);
    }

    pageErrors = [];
    await send('Page.navigate', { url: BASE + spec.path });
    await sleep(2200);
    // settle every reveal observer before judging them
    const height = await evaluate('document.body.scrollHeight');
    for (let y = 0; y <= height; y += 500) {
        await send('Runtime.evaluate', { expression: `window.scrollTo({ top: ${y}, behavior: 'instant' })` });
        await sleep(90);
    }
    await sleep(500);

    for (const [name, expr] of spec.live) {
        const result = await evaluate(expr);
        const ok = result === true;
        console.log(`  ${ok ? '\x1b[32m✓\x1b[0m' : '\x1b[31m✗\x1b[0m'} ${name}${ok ? '' : `  \x1b[90m(${result})\x1b[0m`}`);
        ok ? passed++ : failed.push(`${spec.page}: ${name}`);
    }

    for (const [name, expr] of UNIVERSAL) {
        const ok = expr === null ? pageErrors.length === 0 : (await evaluate(expr)) === true;
        console.log(`  ${ok ? '\x1b[32m✓\x1b[0m' : '\x1b[31m✗\x1b[0m'} ${name}${ok || expr !== null ? '' : `  \x1b[90m(${pageErrors[0]})\x1b[0m`}`);
        ok ? passed++ : failed.push(`${spec.page}: ${name}`);
    }
}

console.log(`\n${'─'.repeat(62)}`);
if (failed.length === 0) {
    console.log(`\x1b[32m✓ all ${passed} checks passed\x1b[0m`);
} else {
    console.log(`\x1b[31m✗ ${failed.length} failed\x1b[0m, ${passed} passed`);
    failed.forEach((f) => console.log(`   \x1b[31m·\x1b[0m ${f}`));
}

ws.close();
chrome.kill('SIGKILL');
process.exit(failed.length ? 1 : 0);
