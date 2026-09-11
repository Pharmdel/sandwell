const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const toast = document.getElementById('wl-toast');
let toastTimer;

function notify(message, ms = 3200) {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('is-on');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('is-on'), ms);
}

async function post(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify(data),
    });
    const body = await res.json().catch(() => ({}));
    if (!res.ok) {
        const first = body.errors ? Object.values(body.errors)[0][0] : body.message;
        throw new Error(first || 'Something went wrong — please try again or call 0121 500 5756.');
    }
    return body;
}

/* ---------------------------------------------------------------
   Modals
   --------------------------------------------------------------- */

const modals = new Map();
let openModal = null;

function open(id) {
    const m = document.getElementById(id);
    if (!m) return;
    if (openModal) close(openModal, true);
    m.hidden = false;
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => m.classList.add('is-open'));
    openModal = m;
    m.querySelector('input, select, textarea, button:not([data-modal-close])')?.focus();
}

function close(m, keepLock = false) {
    m.classList.remove('is-open');
    setTimeout(() => {
        m.hidden = true;
    }, 250);
    if (!keepLock) document.body.style.overflow = '';
    if (openModal === m) openModal = null;
}

document.querySelectorAll('[data-modal-open]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.modalOpen;
        if (id === 'wl-checker') checker.start(btn.dataset.preselect || '');
        open(id);
    });
});

document.querySelectorAll('[data-modal-close]').forEach((btn) => {
    btn.addEventListener('click', () => close(btn.closest('.wl-modal')));
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && openModal) close(openModal);
});

/* ---------------------------------------------------------------
   Suitability checker — mirrors the clinic's decision rules
   --------------------------------------------------------------- */

const REC = {
    orlistat: { name: 'Alli® (Orlistat 60mg)', tag: 'Daily capsule', why: 'Fits your budget and works well as a daily capsule alongside a reduced-fat diet, with pharmacist support throughout.' },
    wegovy_oral: { name: 'Wegovy® Oral Tablets', tag: 'Daily tablet', why: 'The proven power of semaglutide without needles — a once-daily tablet in your budget range.' },
    wegovy: { name: 'Wegovy® Injections', tag: 'Weekly injection', why: 'Once-weekly semaglutide with strong, well-established results — the best fit for your budget and preferences.' },
    mounjaro: { name: 'Mounjaro® Injections', tag: 'Weekly injection', why: 'The strongest results of any licensed treatment — the right match for your budget and how quickly you want to progress.' },
};

const checker = (() => {
    const root = document.getElementById('wl-checker');
    if (!root) return { start() {} };

    const state = { pos: 0, choice: '', bmi: null, form: '', budget: '', pace: '', rec: null, booking: false, sent: false };
    const steps = [...root.querySelectorAll('.wl-step')];
    const dots = [...root.querySelectorAll('[data-dot]')];
    const back = root.querySelector('[data-back]');
    const next = root.querySelector('[data-next]');
    const recWrap = root.querySelector('[data-rec]');
    const bookingForm = root.querySelector('[data-booking]');
    const bookCta = root.querySelector('[data-book-cta]');

    const flow = () => (state.choice && state.choice !== 'rec' ? ['choice', 'bmi', 'result'] : ['choice', 'bmi', 'form', 'budget', 'pace', 'result']);

    function recommend() {
        if (state.bmi !== null && state.bmi < 30) return null;
        if (state.choice && state.choice !== 'rec') return REC[state.choice];
        const tablets = state.form === 'tablets';
        const inject = state.form === 'injections';
        if (state.budget === '60') return REC.orlistat;
        if (state.budget === '150') return tablets ? REC.wegovy_oral : inject ? REC.wegovy : state.pace === 'fast' ? REC.wegovy : REC.wegovy_oral;
        return tablets ? REC.wegovy_oral : REC.mounjaro;
    }

    function renderRec() {
        state.rec = recommend();
        if (!state.rec) {
            recWrap.innerHTML = `<div class="rounded-2xl bg-gradient-to-br from-[#7c3d0d] to-brand-orange-hover p-6 text-white"><h3 class="font-serif text-2xl text-white">Let’s talk it through</h3><p class="mt-2 text-sm leading-relaxed text-white/85">Weight-loss medicines are licensed for a BMI of 30 or above. Based on your BMI, a chat with our pharmacist about diet, activity and other support is the right next step — leave your details and we’ll call you.</p></div>`;
        } else {
            recWrap.innerHTML = `<div class="rounded-2xl bg-brand-navy p-6 text-white"><span class="inline-block rounded-full bg-white/15 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.08em]">${state.rec.tag}</span><h3 class="mt-3 font-serif text-2xl text-white">We recommend: ${state.rec.name}</h3><p class="mt-2 text-sm leading-relaxed text-white/85">${state.rec.why} Our pharmacist will confirm suitability at your free consultation.</p></div>`;
        }
    }

    function show() {
        const keys = flow();
        const key = keys[state.pos];
        steps.forEach((s) => (s.hidden = s.dataset.step !== key));
        dots.forEach((d, i) => {
            d.hidden = i >= keys.length;
            d.classList.toggle('bg-brand-orange', i <= state.pos);
            d.classList.toggle('bg-brand-hairline', i > state.pos);
        });
        back.style.visibility = state.pos ? 'visible' : 'hidden';
        const last = state.pos === keys.length - 1;
        if (last) {
            renderRec();
            bookingForm.hidden = !state.booking;
            bookCta.hidden = state.booking;
        }
        next.textContent = last ? (state.booking ? 'Send my booking request' : 'Book your free call →') : 'Continue →';
        next.disabled = false;
    }

    function setBmiFromInputs() {
        const manual = parseFloat(root.querySelector('[name="bmi"]').value);
        if (manual > 0) state.bmi = +manual.toFixed(1);
    }

    function calcBmi() {
        const metric = root.querySelector('[data-units]').dataset.units === 'metric';
        let cm, kg;
        if (metric) {
            cm = parseFloat(root.querySelector('[name="height_cm"]').value);
            kg = parseFloat(root.querySelector('[name="weight_kg"]').value);
        } else {
            const ft = parseFloat(root.querySelector('[name="height_ft"]').value) || 0;
            const inch = parseFloat(root.querySelector('[name="height_in"]').value) || 0;
            const st = parseFloat(root.querySelector('[name="weight_st"]').value) || 0;
            const lb = parseFloat(root.querySelector('[name="weight_lb"]').value) || 0;
            cm = (ft * 12 + inch) * 2.54;
            kg = (st * 14 + lb) * 0.45359237;
        }
        const out = root.querySelector('[data-bmi-out]');
        if (cm > 0 && kg > 0) {
            const b = kg / Math.pow(cm / 100, 2);
            state.bmi = +b.toFixed(1);
            out.hidden = false;
            out.innerHTML = `Your BMI is <b>${state.bmi}</b>${state.bmi < 30 ? ' — below the 30 needed for weight-loss medicines, but our pharmacist can still talk you through other support.' : '.'}`;
        } else {
            out.hidden = true;
            state.bmi = null;
        }
    }

    root.querySelectorAll('[data-bmi-input]').forEach((el) => el.addEventListener('input', calcBmi));
    root.querySelector('[data-toggle-hw]').addEventListener('click', (e) => {
        const box = root.querySelector('[data-hw]');
        box.hidden = !box.hidden;
        e.currentTarget.setAttribute('aria-expanded', String(!box.hidden));
        if (!box.hidden) {
            root.querySelector('[name="bmi"]').value = '';
            calcBmi();
        }
    });
    root.querySelectorAll('[data-unit]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const units = btn.dataset.unit;
            root.querySelector('[data-units]').dataset.units = units;
            root.querySelectorAll('[data-unit]').forEach((b) => b.setAttribute('aria-pressed', String(b === btn)));
            root.querySelectorAll('[data-unit-group]').forEach((g) => (g.hidden = g.dataset.unitGroup !== units));
            calcBmi();
        });
    });

    root.querySelectorAll('[data-q]').forEach((btn) => {
        btn.addEventListener('click', () => {
            state[btn.dataset.q] = btn.dataset.v;
            btn.parentElement.querySelectorAll('[data-q]').forEach((b) => b.setAttribute('aria-checked', String(b === btn)));
        });
    });

    back.addEventListener('click', () => {
        if (state.booking) {
            state.booking = false;
        } else {
            state.pos = Math.max(0, state.pos - 1);
        }
        show();
    });

    next.addEventListener('click', () => {
        const keys = flow();
        const key = keys[state.pos];
        if (key === 'choice' && !state.choice) return notify('Pick a treatment, or let us recommend one.');
        if (key === 'bmi') {
            setBmiFromInputs();
            if (state.bmi === null) return notify('Enter your BMI, or your height and weight.');
        }
        if (key === 'form' && !state.form) return notify('Pick the option that suits you.');
        if (key === 'budget' && !state.budget) return notify('Choose a budget range.');
        if (key === 'pace' && !state.pace) return notify('Choose a pace.');
        if (state.pos === keys.length - 1) {
            if (!state.booking) {
                state.booking = true;
                show();
                bookingForm.querySelector('[name="name"]').focus();
                return;
            }
            return submit();
        }
        state.pos += 1;
        show();
    });

    async function submit() {
        if (state.sent) return;
        const f = bookingForm;
        const name = f.querySelector('[name="name"]').value.trim();
        const phone = f.querySelector('[name="phone"]').value.trim();
        if (!name || !phone) return notify('Please add your name and phone number.');

        next.disabled = true;
        next.textContent = 'Booking…';
        const bestTime = f.querySelector('[name="best_time"]').value;
        const budgetLabel = { 60: '~£60/mo', 150: '£100-150/mo', 350: '£200-350/mo' }[state.budget] || '';

        try {
            await post(root.dataset.endpoint, {
                name,
                phone,
                email: f.querySelector('[name="email"]').value.trim(),
                best_time: bestTime,
                conditions: f.querySelector('[name="conditions"]').value.trim(),
                medication: f.querySelector('[name="medication"]').value.trim(),
                nhs_number: f.querySelector('[name="nhs_number"]').value.trim(),
                scr_consent: f.querySelector('[name="scr_consent"]').checked,
                bmi: state.bmi,
                choice: state.choice,
                comfortable_with: state.form,
                budget: budgetLabel,
                pace: state.pace,
                recommended: state.rec ? state.rec.name : 'none - BMI below 30',
            });
            state.sent = true;
            root.querySelector('[data-body]').innerHTML = `<div class="px-2 py-8 text-center"><div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-700"><svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg></div><h3 class="mt-5 font-serif text-3xl text-brand-navy">Consultation call booked</h3><p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-brand-stone">Our pharmacist will call you ${bestTime ? '(' + bestTime.toLowerCase() + ') ' : ''}to confirm suitability and get you started — the call is free and there’s nothing to pay today.</p><button type="button" data-modal-close class="mt-7 rounded-full bg-brand-orange px-8 py-3.5 text-sm font-semibold text-white hover:bg-brand-orange-hover">Done</button></div>`;
            root.querySelector('[data-body] [data-modal-close]').addEventListener('click', () => close(root));
        } catch (err) {
            next.disabled = false;
            next.textContent = 'Send my booking request';
            notify(err.message, 4500);
        }
    }

    return {
        start(preselect) {
            if (state.sent) return;
            state.pos = 0;
            state.booking = false;
            if (preselect) {
                state.choice = preselect;
                root.querySelectorAll('[data-q="choice"]').forEach((b) => b.setAttribute('aria-checked', String(b.dataset.v === preselect)));
            }
            show();
        },
    };
})();

/* ---------------------------------------------------------------
   Switch-provider form
   --------------------------------------------------------------- */

const switchForm = document.getElementById('wl-switch-form');
if (switchForm) {
    switchForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(switchForm);
        const data = Object.fromEntries(fd.entries());
        if (!data.medication || !data.dose || !data.name || !data.phone) return notify('Please fill in the medication, dose, your name and phone.', 3500);
        const btn = switchForm.querySelector('[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Sending…';
        try {
            await post(switchForm.action, data);
            switchForm.closest('[data-body]').innerHTML = `<div class="px-2 py-8 text-center"><div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-700"><svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg></div><h3 class="mt-5 font-serif text-3xl text-brand-navy">Switch request sent</h3><p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-brand-stone">Our pharmacist will call to confirm your dose history and time the handover so you never miss a week.</p><button type="button" data-modal-close class="mt-7 rounded-full bg-brand-orange px-8 py-3.5 text-sm font-semibold text-white hover:bg-brand-orange-hover">Done</button></div>`;
            document.querySelector('#wl-switch [data-body] [data-modal-close]').addEventListener('click', () => close(document.getElementById('wl-switch')));
        } catch (err) {
            btn.disabled = false;
            btn.textContent = 'Request my switch';
            notify(err.message, 4500);
        }
    });
}

/* ---------------------------------------------------------------
   Foundayo waiting list
   --------------------------------------------------------------- */

const waitForm = document.getElementById('wl-waitlist-form');
if (waitForm) {
    const bmiOut = waitForm.querySelector('[data-fdy-bmi]');
    const bmiCalc = () => {
        const h = parseFloat(waitForm.querySelector('[name="height_cm"]').value) || 0;
        const w = parseFloat(waitForm.querySelector('[name="weight_kg"]').value) || 0;
        if (h > 0 && w > 0) {
            const bmi = Math.round((w / Math.pow(h / 100, 2)) * 10) / 10;
            bmiOut.dataset.bmi = bmi;
            bmiOut.innerHTML = `Your BMI is about <b>${bmi}</b>. ${bmi >= 30 ? 'That is within the range Foundayo is licensed for.' : bmi >= 27 ? 'Foundayo can be considered from a BMI of 27 if you also have a weight-related condition — our pharmacist will check with you.' : 'Foundayo is licensed from a BMI of 27 upwards, so it may not be suitable. We can talk through other options.'}`;
        } else {
            bmiOut.dataset.bmi = '';
            bmiOut.textContent = 'Enter your height and weight and we’ll work out your BMI.';
        }
    };
    waitForm.querySelectorAll('[name="height_cm"], [name="weight_kg"]').forEach((el) => el.addEventListener('input', bmiCalc));

    waitForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(waitForm);
        const data = Object.fromEntries(fd.entries());
        if (!data.name || !data.phone) return notify('Please add your name and phone number.', 3500);
        data.bmi = parseFloat(bmiOut.dataset.bmi) || null;
        data.height_cm = parseFloat(data.height_cm) || null;
        data.weight_kg = parseFloat(data.weight_kg) || null;
        const btn = waitForm.querySelector('[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Adding you…';
        try {
            await post(waitForm.action, data);
            const first = data.name.split(' ')[0].replace(/[<>&]/g, '');
            const via = data.contact_pref === 'email' ? 'email' : data.contact_pref === 'either' ? 'phone or email' : 'phone';
            waitForm.closest('[data-body]').innerHTML = `<h3 class="font-serif text-3xl text-brand-navy">You’re on the list</h3><p class="mt-3 text-sm leading-relaxed text-brand-stone">Thanks ${first}. We’ll be in touch as soon as Foundayo is available to us — by ${via}.</p><div class="mt-5 rounded-2xl bg-brand-peach p-4 text-sm leading-relaxed text-brand-orange-hover">In the meantime, if you’d like to start something now, our pharmacist can talk you through Mounjaro, Wegovy or Alli. Call <b>0121 500 5756</b> or use the suitability check on this page.</div><button type="button" data-modal-close class="mt-6 w-full rounded-full bg-brand-orange px-8 py-3.5 text-sm font-semibold text-white hover:bg-brand-orange-hover">Close</button>`;
            document.querySelector('#wl-waitlist [data-body] [data-modal-close]').addEventListener('click', () => close(document.getElementById('wl-waitlist')));
        } catch (err) {
            btn.disabled = false;
            btn.textContent = 'Add me to the list';
            notify(err.message, 5000);
        }
    });
}
