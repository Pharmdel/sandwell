/* Service enquiry forms -------------------------------------------------------
   One handler for every catalogue-driven form: posts JSON, renders Laravel's
   field errors inline, and swaps the form for a confirmation on success. */
document.querySelectorAll('[data-service-form]').forEach((form) => {
    const button = form.querySelector('button[type="submit"]');
    const label = form.querySelector('[data-form-label]');
    const done = form.querySelector('[data-form-done]');
    const message = form.querySelector('[data-form-message]');
    const original = label.textContent;

    const clearErrors = () => form.querySelectorAll('[data-error-for]').forEach((e) => {
        e.hidden = true;
        e.textContent = '';
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors();

        button.disabled = true;
        label.textContent = 'Sending…';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(Object.fromEntries(new FormData(form))),
            });

            const payload = await response.json().catch(() => ({}));

            if (response.status === 422) {
                Object.entries(payload.errors ?? {}).forEach(([field, messages]) => {
                    const slot = form.querySelector(`[data-error-for="${field}"]`);
                    if (slot) {
                        slot.textContent = messages[0];
                        slot.hidden = false;
                    }
                });
                form.querySelector('[data-error-for]:not([hidden])')?.scrollIntoView({ block: 'center', behavior: 'smooth' });
                return;
            }

            if (! response.ok) throw new Error('Request failed');

            message.textContent = payload.message ?? 'Our pharmacist will be in touch shortly.';
            form.querySelectorAll('.grid, button[type="submit"], p.mt-4').forEach((el) => (el.hidden = true));
            done.hidden = false;
            done.scrollIntoView({ block: 'center', behavior: 'smooth' });
        } catch {
            message.textContent = '';
            const slot = form.querySelector('[data-error-for]');
            if (slot) {
                slot.textContent = 'Something went wrong — please try again, or call us on 0121 500 5756.';
                slot.hidden = false;
            }
        } finally {
            button.disabled = false;
            label.textContent = original;
        }
    });
});

/* Blood pressure eligibility check -------------------------------------------
   Three questions, then the verdict. The free NHS check needs all three to line
   up; anything else still has a route, so the "no" branch says which and why. */
const bpCheck = document.querySelector('[data-bp-check]');

if (bpCheck) {
    const steps = [...bpCheck.querySelectorAll('[data-bp-step]')];
    const output = bpCheck.querySelector('[data-bp-result]');
    const dots = [...bpCheck.querySelectorAll('[data-bp-dot]')];
    const answers = {};
    let position = 0;

    const render = () => {
        steps.forEach((s, i) => (s.hidden = i !== position));
        dots.forEach((d, i) => d.classList.toggle('is-on', i <= position));
    };

    const verdict = () => {
        const free = answers.age === 'yes' && answers.meds === 'no' && answers.last5 === 'no';
        const why = answers.last5 === 'yes'
            ? 'the NHS covers one pharmacy check every five years'
            : answers.age === 'no'
                ? 'the free NHS check is for adults aged 40 and over'
                : 'the free check is for people not already on blood pressure medication';

        const option = (price, title, note) => `
            <div class="flex items-start gap-4 rounded-2xl border border-brand-hairline bg-white p-4">
                <span class="shrink-0 font-serif text-xl text-brand-moss">${price}</span>
                <span><b class="block text-[15px] text-brand-forest">${title}</b>
                <small class="text-[13px] text-brand-stone">${note}</small></span>
            </div>`;

        output.innerHTML = free
            ? `<p class="rounded-2xl bg-brand-pistachio p-5 text-[15px] leading-relaxed text-brand-forest">
                 <b>Good news — you qualify for a free NHS check.</b><br>
                 No appointment needed: walk in any time during opening hours and it takes about five minutes.</p>
               <div class="mt-4 space-y-3">
                 ${option('Free', 'Walk in today', 'Mon–Fri 8:30am–6pm · Sat 9am–1pm')}
                 ${option('£50', '24-hour monitoring (ABPM)', 'A full day and night of readings — no referral needed')}
               </div>`
            : `<p class="rounded-2xl bg-brand-ivory p-5 text-[15px] leading-relaxed text-brand-forest">
                 <b>Not this time — ${why}.</b><br>You can still get checked today. Here are your options:</p>
               <div class="mt-4 space-y-3">
                 ${option('£5', 'Private check — today', 'Walk in, five minutes, results and advice on the spot')}
                 ${option('£50', '24-hour monitoring (ABPM)', 'A full day and night of readings — no referral needed')}
               </div>`;
    };

    bpCheck.querySelectorAll('[data-bp-option]').forEach((option) => {
        option.addEventListener('click', () => {
            const step = option.closest('[data-bp-step]');
            answers[step.dataset.bpStep] = option.dataset.bpOption;
            step.querySelectorAll('[data-bp-option]').forEach((o) => o.classList.remove('is-chosen'));
            option.classList.add('is-chosen');
            position = Math.min(position + 1, steps.length - 1);
            render();
            if (position === steps.length - 1) verdict();
        });
    });

    bpCheck.querySelector('[data-bp-back]')?.addEventListener('click', () => {
        position = Math.max(0, position - 1);
        render();
    });

    bpCheck.querySelector('[data-bp-restart]')?.addEventListener('click', () => {
        position = 0;
        bpCheck.querySelectorAll('[data-bp-option]').forEach((o) => o.classList.remove('is-chosen'));
        render();
    });

    render();
}

/* Flu & Covid-19 eligibility --------------------------------------------------
   Mirrors the published NHS criteria: the flu programme is broad, the Covid-19
   programme is narrow (75+, care homes, immunosuppressed). Anyone who misses
   both still has the private flu route if they are 18 or over. */
const fluCheck = document.querySelector('[data-flu-check]');

if (fluCheck) {
    const ageField = fluCheck.querySelector('[data-flu-age]');
    const output = fluCheck.querySelector('[data-flu-result]');

    const card = (tone, title, body) => `
        <div class="rounded-2xl border p-5 ${tone === 'yes'
            ? 'border-brand-pistachio-line bg-brand-pistachio'
            : 'border-brand-hairline bg-white'}">
            <b class="block text-[15px] text-brand-forest">${title}</b>
            <span class="mt-1 block text-[14px] leading-relaxed text-brand-stone">${body}</span>
        </div>`;

    fluCheck.querySelector('[data-flu-run]').addEventListener('click', () => {
        const age = parseInt(ageField.value, 10);

        if (Number.isNaN(age) || age < 0 || age > 120) {
            output.innerHTML = card('no', 'Please enter an age', 'We need an age to check this season’s criteria.');
            ageField.focus();
            return;
        }

        const ticked = (key) => fluCheck.querySelector(`[data-flu-reason="${key}"]`).checked;
        const any = (...keys) => keys.some(ticked);

        const nhsFlu = age >= 65 || any('pregnant', 'condition', 'immuno', 'carehome', 'carer', 'contact', 'frontline');
        const nhsCovid = age >= 75 || any('carehome', 'immuno');

        const parts = [];

        if (nhsFlu) {
            parts.push(card('yes', 'Free NHS flu jab — you qualify',
                'Book in with us and there is nothing to pay.'));
        } else if (age >= 18) {
            parts.push(card('no', 'Private flu jab — £30',
                'You are not in an NHS-eligible group this season, but anyone 18 or over can have a private flu jab, paid in store on the day.'));
        } else {
            parts.push(card('no', 'Speak to us about flu',
                'Children outside the eligible groups are usually vaccinated through school or their GP — call us on 0121 500 5756 and we will point you the right way.'));
        }

        parts.push(nhsCovid
            ? card('yes', 'Free NHS Covid-19 vaccination — you qualify',
                'This can be given at the same visit as your flu jab.')
            : card('no', 'NHS Covid-19 vaccination — not this season',
                'The autumn programme covers adults aged 75 and over, care home residents and anyone immunosuppressed.'));

        if (nhsFlu && nhsCovid) {
            parts.push(card('yes', 'Have both in one visit',
                'Co-administration is recommended by the NHS — one 15-minute appointment, one jab in each arm.'));
        }

        output.innerHTML = `<div class="space-y-3">${parts.join('')}</div>
            <a href="/book" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border border-brand-forest px-6 py-3.5 text-sm font-semibold text-brand-forest transition-all duration-200 hover:bg-brand-forest hover:text-white">
                Book your appointment
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
            </a>`;
        output.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    });
}

/* MDS eligibility ------------------------------------------------------------
   Same quiz shell as the blood pressure check, but the last answer is a choice
   of route rather than a test, so the verdict restates what they picked and
   sends them to the form below. */
const mdsCheck = document.querySelector('[data-mds-check]');

if (mdsCheck) {
    const steps = [...mdsCheck.querySelectorAll('[data-mds-step]')];
    const output = mdsCheck.querySelector('[data-mds-result]');
    const dots = [...mdsCheck.querySelectorAll('[data-mds-dot]')];
    const answers = {};
    let position = 0;

    const render = () => {
        steps.forEach((s, i) => (s.hidden = i !== position));
        dots.forEach((d, i) => d.classList.toggle('is-on', i <= position));
    };

    const verdict = () => {
        const priority = answers.route === 'priority';
        const heavy = answers.items === '7+' || answers.miss === 'often';

        output.innerHTML = `
            <div class="rounded-2xl border p-5 ${priority ? 'border-brand-pistachio-line bg-brand-pistachio' : 'border-brand-hairline bg-white'}">
                <b class="block text-[15px] text-brand-forest">${priority
                    ? 'Priority setup — you can start right away'
                    : 'Free NHS route — we’ll add you to the list'}</b>
                <span class="mt-1 block text-[14px] leading-relaxed text-brand-stone">${priority
                    ? 'We’ll prepare your weekly trays at £25 a month. Leave your details below and we’ll call to arrange it, usually within one working day.'
                    : 'Leave your details below and we’ll add you to the free waiting list, then contact you the moment a place opens. If your needs change, priority setup is always available.'}</span>
            </div>
            ${heavy ? `<p class="mt-3 text-[13px] leading-relaxed text-brand-stone">
                Based on your answers, trays are likely to make a real difference day to day —
                mention that when we call and our pharmacist will prioritise the review.</p>` : ''}
            <a href="#enquire" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-moss px-6 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-brand-moss-hover">
                Continue to the form
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
            </a>`;
    };

    mdsCheck.querySelectorAll('[data-mds-option]').forEach((option) => {
        option.addEventListener('click', () => {
            const step = option.closest('[data-mds-step]');
            answers[step.dataset.mdsStep] = option.dataset.mdsOption;
            step.querySelectorAll('[data-mds-option]').forEach((o) => o.classList.remove('is-chosen'));
            option.classList.add('is-chosen');
            position = Math.min(position + 1, steps.length - 1);
            render();
            if (position === steps.length - 1) verdict();
        });
    });

    mdsCheck.querySelector('[data-mds-back]')?.addEventListener('click', () => {
        position = Math.max(0, position - 1);
        render();
    });

    render();
}

/* Delivery area check --------------------------------------------------------
   Matched on the outward code, which is all we need to answer "do you come to
   my street?" — anything outside the served list gets the phone number rather
   than a flat no, because the boundary is a radius, not a postcode list. */
const postcodeCheck = document.querySelector('[data-postcode-check]');

if (postcodeCheck) {
    const field = postcodeCheck.querySelector('[data-postcode-input]');
    const output = postcodeCheck.querySelector('[data-postcode-result]');

    const AREAS = {
        B70: 'West Bromwich', B71: 'West Bromwich', B69: 'Oldbury & Tividale',
        B66: 'Smethwick', B67: 'Smethwick', B68: 'Oldbury', B21: 'Handsworth',
        B42: 'Great Barr', B43: 'Great Barr', B44: 'Great Barr',
        WS10: 'Wednesbury', WS1: 'Walsall', WS2: 'Walsall', WS3: 'Walsall',
        WS4: 'Walsall', WS5: 'Walsall', DY4: 'Tipton',
        DY8: 'Stourbridge', DY9: 'Stourbridge',
    };

    const box = (tone, title, body) => {
        output.innerHTML = `
            <div class="rounded-2xl border p-5 ${tone === 'yes'
                ? 'border-brand-pistachio-line bg-brand-pistachio'
                : 'border-brand-hairline bg-brand-ivory'}">
                <b class="block text-[15px] text-brand-forest">${title}</b>
                <span class="mt-1 block text-[14px] leading-relaxed text-brand-stone">${body}</span>
            </div>`;
    };

    const check = () => {
        const raw = field.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

        // The inward code is always the last three characters, so anything before
        // them is the outward code. Splitting on that beats a greedy pattern,
        // which reads "DY81AA" as DY81A rather than DY8.
        const outward = raw.length >= 5 ? raw.slice(0, -3) : raw;
        const valid = /^[A-Z]{1,2}\d{1,2}[A-Z]?$/.test(outward);

        if (! valid) {
            box('no', 'That does not look like a postcode', 'Enter it like B70 7RW and we will check.');
            return;
        }

        const area = AREAS[outward];

        if (area) {
            box('yes', `Yes — we deliver to ${area}`,
                'NHS prescription deliveries are free of charge, with our own drivers. We will let you know when your medication is on its way.');
        } else {
            box('no', 'We may still reach you',
                `We deliver free within about five miles of the pharmacy, and ${outward} sits outside the areas we list. Call us on 0121 500 5756 and we will check your street.`);
        }
    };

    postcodeCheck.querySelector('[data-postcode-run]').addEventListener('click', check);
    field.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            check();
        }
    });
}
