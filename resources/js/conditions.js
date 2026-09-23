const root = document.getElementById('symptom-test');

if (root) {
    const cond = JSON.parse(document.getElementById('condition-data').textContent);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const phone = root.dataset.phone;
    const state = { step: 0, sym: [], dur: null, age: '', sex: '', preg: 'no', pathway: [], pathwayIndex: 0, flags: [] };
    const questions = root.querySelector('[data-questions]');
    const verdict = root.querySelector('[data-verdict]');
    const form = root.querySelector('[data-callback]');
    const toast = document.getElementById('wl-toast');
    let timer;

    const notify = (m, ms = 3200) => {
        if (!toast) return;
        toast.textContent = m;
        toast.classList.add('is-on');
        clearTimeout(timer);
        timer = setTimeout(() => toast.classList.remove('is-on'), ms);
    };
    const esc = (s) => String(s ?? '').replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[c]);

    // Pathway questions that duplicate the age/sex step are skipped.
    const pathway = (cond.screening || []).filter((q) => !/^how old|sex\?$/i.test(q.q));

    function steps() {
        const s = ['sym'];
        if (cond.dur) s.push('dur');
        s.push('about');
        if (pathway.length) s.push('pathway');
        s.push('flags');
        return s;
    }

    const labels = { sym: 'Symptoms', dur: 'How long', about: 'About you', pathway: 'Pathway', flags: 'Safety' };
    const opt = 'wl-opt flex w-full items-center gap-3 rounded-2xl border border-brand-hairline bg-white px-5 py-4 text-left text-[15px] font-medium text-brand-forest hover:border-brand-forest/50';
    const btnP = 'inline-flex items-center justify-center gap-2 rounded-full bg-brand-moss px-7 py-3.5 text-sm font-semibold text-white transition-all hover:bg-brand-moss-hover disabled:cursor-not-allowed disabled:opacity-40';
    const btnO = 'inline-flex items-center justify-center rounded-full border border-brand-forest px-6 py-3.5 text-sm font-semibold text-brand-forest transition-all hover:bg-brand-forest hover:text-white';

    function progress() {
        return `<ol class="mb-8 flex flex-wrap gap-2" aria-label="Progress">${steps().map((k, i) => {
            const on = i === state.step, done = i < state.step;
            return `<li class="flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold ${on ? 'bg-brand-forest text-white' : done ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-forest/5 text-brand-stone-light'}"><span>${done ? '✓' : i + 1}</span>${labels[k]}</li>`;
        }).join('')}</ol>`;
    }

    function check(list, i, label, warn = false) {
        const on = list.includes(i);
        return `<label class="${opt} ${warn ? 'border-red-200' : ''}" data-on="${on}"><input type="checkbox" class="h-4 w-4 accent-brand-moss" data-check="${i}" ${on ? 'checked' : ''}><span>${esc(label)}</span></label>`;
    }

    function body() {
        const k = steps()[state.step];
        if (k === 'sym') {
            return `<h3 class="text-2xl">Which of these do you have?</h3><p class="mt-2 text-sm text-brand-stone">Tick everything that applies. This helps our pharmacist see whether ${esc(cond.name.toLowerCase())} is the likely cause.</p><div class="mt-5 grid gap-2.5" data-group="sym">${cond.sym.map((s, i) => check(state.sym, i, s)).join('')}</div><p class="mt-3 text-xs text-brand-stone-light" data-count>${state.sym.length ? state.sym.length + ' selected' : 'Tick at least one to carry on'}</p>`;
        }
        if (k === 'dur') {
            return `<h3 class="text-2xl">${esc(cond.dur.q)}</h3>${cond.dur.note ? `<p class="mt-2 text-sm text-brand-stone">${esc(cond.dur.note)}</p>` : ''}<div class="mt-5 grid gap-2.5" role="radiogroup">${cond.dur.opts.map((o, i) => `<button type="button" role="radio" aria-checked="${state.dur === i}" data-dur="${i}" class="wl-opt flex w-full rounded-2xl border border-brand-hairline bg-white px-5 py-4 text-left text-[15px] font-medium text-brand-forest hover:border-brand-forest/50">${esc(o)}</button>`).join('')}</div>`;
        }
        if (k === 'about') {
            const female = cond.sex === 'F';
            return `<h3 class="text-2xl">A couple of details</h3><p class="mt-2 text-sm text-brand-stone">${esc(cond.elig)}</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
              <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light" for="st_age">Patient’s age *</label><input id="st_age" type="number" min="0" max="120" inputmode="numeric" placeholder="e.g. 34" value="${esc(state.age)}" data-field="age" class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-forest focus:border-brand-forest focus:outline-none focus:ring-2 focus:ring-brand-forest/20"></div>
              ${female ? `<div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light" for="st_sex">Sex *</label><select id="st_sex" data-field="sex" class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-forest"><option value="">Select…</option><option value="F" ${state.sex === 'F' ? 'selected' : ''}>Female</option><option value="M" ${state.sex === 'M' ? 'selected' : ''}>Male</option></select></div>
              <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light" for="st_preg">Are you pregnant?</label><select id="st_preg" data-field="preg" class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-forest"><option value="no" ${state.preg === 'no' ? 'selected' : ''}>No</option><option value="yes" ${state.preg === 'yes' ? 'selected' : ''}>Yes</option></select></div>` : ''}
            </div>`;
        }
        if (k === 'pathway') {
            const q = pathway[state.pathwayIndex];
            return `<p class="text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">NHS pathway check · ${state.pathwayIndex + 1} of ${pathway.length}</p><h3 class="mt-2 text-2xl">${esc(q.q)}</h3><div class="mt-5 grid gap-2.5" role="radiogroup">${q.options.map((o, i) => `<button type="button" role="radio" aria-checked="false" data-path="${i}" class="wl-opt flex w-full rounded-2xl border border-brand-hairline bg-white px-5 py-4 text-left text-[15px] font-medium text-brand-forest hover:border-brand-forest/50">${esc(o.text)}</button>`).join('')}</div>`;
        }
        return `<h3 class="text-2xl">Last thing — a safety check</h3><p class="mt-2 text-sm text-brand-stone">These are signs that need seeing sooner than a pharmacy visit. Most people won’t tick any.</p><div class="mt-5 grid gap-2.5" data-group="flags">${cond.flags.map((f, i) => check(state.flags, i, f, true)).join('')}</div>`;
    }

    function nav() {
        const k = steps()[state.step];
        const last = k === 'flags';
        const canNext = k === 'sym' ? state.sym.length > 0 : k === 'dur' ? state.dur !== null : true;
        const back = state.step > 0 ? `<button type="button" data-back class="${btnO}">← Back</button>` : '<span></span>';
        const next = k === 'pathway' ? '' : `<button type="button" data-next class="${btnP}" ${canNext ? '' : 'disabled'}>${last ? 'See my result →' : 'Continue →'}</button>`;
        return `<div class="mt-8 flex items-center justify-between gap-3 border-t border-brand-hairline pt-5">${back}${next}</div>`;
    }

    function paint() {
        questions.innerHTML = progress() + body() + nav();
        verdict.hidden = true;
        form.hidden = true;
        questions.hidden = false;
        questions.querySelectorAll('[data-check]').forEach((el) => el.addEventListener('change', () => {
            const list = el.closest('[data-group]').dataset.group === 'sym' ? state.sym : state.flags;
            const i = +el.dataset.check;
            const ix = list.indexOf(i);
            if (el.checked && ix === -1) list.push(i);
            if (!el.checked && ix > -1) list.splice(ix, 1);
            el.closest('label').dataset.on = el.checked;
            const c = questions.querySelector('[data-count]');
            if (c) c.textContent = state.sym.length ? state.sym.length + ' selected' : 'Tick at least one to carry on';
            const n = questions.querySelector('[data-next]');
            if (n && steps()[state.step] === 'sym') n.disabled = !state.sym.length;
        }));
        questions.querySelectorAll('[data-dur]').forEach((b) => b.addEventListener('click', () => {
            state.dur = +b.dataset.dur;
            questions.querySelectorAll('[data-dur]').forEach((x) => x.setAttribute('aria-checked', String(x === b)));
            questions.querySelector('[data-next]').disabled = false;
        }));
        questions.querySelectorAll('[data-field]').forEach((el) => el.addEventListener('input', () => (state[el.dataset.field] = el.value)));
        questions.querySelectorAll('[data-path]').forEach((b) => b.addEventListener('click', () => {
            const q = pathway[state.pathwayIndex];
            const o = q.options[+b.dataset.path];
            state.pathway.push({ q: q.q, a: o.text, result: o.result });
            if (o.result === 'fail') return result('pathway');
            if (state.pathwayIndex < pathway.length - 1) {
                state.pathwayIndex += 1;
                return paint();
            }
            state.step += 1;
            paint();
        }));
        questions.querySelector('[data-back]')?.addEventListener('click', () => {
            if (steps()[state.step] === 'pathway' && state.pathwayIndex > 0) {
                state.pathwayIndex -= 1;
                state.pathway.pop();
            } else {
                state.step = Math.max(0, state.step - 1);
                if (steps()[state.step] === 'pathway') { state.pathwayIndex = 0; state.pathway = []; }
            }
            paint();
        });
        questions.querySelector('[data-next]')?.addEventListener('click', () => {
            const k = steps()[state.step];
            if (k === 'sym' && !state.sym.length) return notify('Tick at least one symptom so we know what we’re looking at.', 3500);
            if (k === 'dur' && state.dur === null) return notify('Please choose one.');
            if (k === 'about') {
                const a = parseInt(state.age, 10);
                if (isNaN(a) || a < 0 || a > 120) return notify('Please enter the patient’s age.');
                if (cond.sex === 'F' && !state.sex) return notify('Please select sex — this service is for women only.', 3500);
            }
            if (k === 'flags') return result();
            state.step += 1;
            if (steps()[state.step] === 'pathway') { state.pathwayIndex = 0; state.pathway = []; }
            paint();
        });
        root.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function card(kind, badge, title, html) {
        const tones = { urgent: 'border-red-200 bg-red-50', no: 'border-brand-hairline bg-brand-ivory', maybe: 'border-brand-pistachio-line bg-brand-pistachio', yes: 'border-emerald-200 bg-emerald-50' };
        const badgeTone = { urgent: 'bg-red-600 text-white', no: 'bg-brand-forest text-white', maybe: 'bg-brand-moss text-white', yes: 'bg-emerald-600 text-white' };
        return `<div class="rounded-[24px] border p-8 ${tones[kind]}"><span class="inline-block rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.08em] ${badgeTone[kind]}">${badge}</span><h3 class="mt-4 text-2xl">${title}</h3><div class="mt-3 space-y-3 text-[15px] leading-relaxed text-brand-stone">${html}</div><button type="button" data-restart class="mt-6 text-sm font-semibold text-brand-forest underline decoration-brand-hairline underline-offset-4 hover:decoration-brand-moss">Start again</button></div>`;
    }

    function result(forced) {
        const age = parseInt(state.age, 10);
        const red = state.flags.length > 0;
        const durScore = cond.dur && state.dur !== null ? cond.dur.score[state.dur] || 0 : 0;
        const wrongSex = cond.sex === 'F' && state.sex !== 'F';
        const pregnant = cond.sex === 'F' && state.preg === 'yes';
        const tooYoung = age < cond.min, tooOld = age > cond.max;
        const name = esc(cond.name);
        const recap = `<p class="mb-5 rounded-2xl bg-white/70 px-4 py-3 text-sm text-brand-stone"><b class="text-brand-forest">What you told us:</b> ${state.sym.map((i) => esc(cond.sym[i])).join(' · ')}${cond.dur && state.dur !== null ? ' · ' + esc(cond.dur.opts[state.dur]) : ''}${isNaN(age) ? '' : ' · age ' + age}</p>`;
        let html;
        let eligible = false;

        if (red) {
            html = card('urgent', 'Needs urgent attention', 'Please get seen today rather than waiting', `<p>One of the things you ticked needs assessing more urgently than a pharmacy appointment. Call <b>NHS 111</b>, contact your GP for an urgent appointment, or go to A&amp;E if you feel very unwell. If you’re struggling to breathe, call 999.</p><p class="text-sm">We’d still rather you rang us on <b>${phone}</b> if you’re not sure — our pharmacist can tell you where to go.</p>`);
        } else if (forced === 'pathway') {
            html = card('maybe', 'Speak to our pharmacist', 'This falls outside the NHS pathway — but we can still help', `<p>One of your answers means the free NHS ${name} pathway may not apply. That doesn’t mean nothing can be done: call us on <b>${phone}</b> or walk in, and our pharmacist will assess you, advise, and arrange a GP or urgent appointment if that’s what’s needed.</p>`);
        } else if (durScore === 2) {
            html = card('no', 'Not eligible for this service', 'This one needs your GP rather than us', `<p>${esc(cond.dur?.note || 'Based on how long this has been going on, it needs a GP assessment rather than pharmacy treatment.')}</p><p>Contact your surgery, or call NHS 111 if you can’t get through. Do come to us for anything else — and we can still advise on easing the symptoms meanwhile.</p>`);
        } else if (wrongSex) {
            html = card('no', 'Not eligible for this service', `The NHS service for ${name} is for women aged ${cond.min}–${cond.max}`, `<p>That’s an NHS rule, not our choice. Men with these symptoms need a GP assessment, because the causes can be different. Call your surgery, or ring us on <b>${phone}</b> and we’ll point you the right way.</p>`);
        } else if (pregnant) {
            html = card('maybe', 'Please speak to us first', 'Speak to us before anything else', `<p>In pregnancy this needs handling slightly differently, and the NHS pathway doesn’t cover it. Please call us on <b>${phone}</b> — our pharmacist will advise and, where needed, arrange for your midwife or GP to see you promptly.</p>`);
        } else if (tooYoung || tooOld) {
            const band = cond.max < 120 ? `aged ${cond.min}–${cond.max}` : `${cond.min} and over`;
            html = card('no', 'Not eligible for this service', `The free NHS service for ${name} covers people ${band}`, `<p>Your symptoms do sound like they need looking at — it’s just that this particular NHS pathway has an age limit set nationally. ${tooYoung ? 'For someone younger, your GP or NHS 111 is the right route.' : 'Please contact your GP surgery.'}</p><p class="text-sm">Come in anyway if you’d like — we can often help with over-the-counter treatment or tell you whether it needs a doctor.</p>`);
        } else {
            eligible = true;
            const soon = durScore === 1;
            const free = cond.tag === 'Pharmacy First' || cond.tag === 'Minor Ailments';
            html = card('yes', free ? 'You appear to be eligible' : 'We can help with this', free ? 'Good news — we can treat this for you' : 'Good news — our pharmacist can help', `<p>Your symptoms${cond.min > 0 || cond.max < 120 ? ' and age' : ''} fit ${free ? `the free NHS service for ${name}` : `what our pharmacists advise on for ${name}`}. ${soon ? 'Given how long it’s been going on, the sooner you’re seen the better. ' : ''}You don’t need a GP appointment${free ? ' and there’s nothing to pay' : ''}.</p><p class="text-sm">Our pharmacist will assess you properly in our private consultation room${cond.tag === 'Pharmacy First' ? ' and, where it’s the right thing to do, can supply treatment including antibiotics' : ''}. Walk in any time, or leave your details below and we’ll call you — usually the same day.</p>`);
        }

        questions.hidden = true;
        verdict.innerHTML = recap + html;
        verdict.hidden = false;
        form.hidden = !eligible;
        verdict.querySelector('[data-restart]').addEventListener('click', restart);
        root.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function restart() {
        Object.assign(state, { step: 0, sym: [], dur: null, age: '', sex: '', preg: 'no', pathway: [], pathwayIndex: 0, flags: [] });
        paint();
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = Object.fromEntries(new FormData(form).entries());
        if (!fd.name || !fd.dob || !fd.phone) return notify('Please add your name, date of birth and phone number.', 3800);
        const btn = form.querySelector('[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Sending…';
        const answers = {
            symptoms: state.sym.map((i) => cond.sym[i]),
            duration: cond.dur && state.dur !== null ? cond.dur.opts[state.dur] : null,
            age: state.age, sex: state.sex || null, pregnant: cond.sex === 'F' ? state.preg : null,
            pathway: state.pathway,
        };
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ ...fd, condition: cond.name, answers }),
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.errors ? Object.values(data.errors)[0][0] : data.message || 'Something went wrong.');
            form.innerHTML = `<div class="rounded-[24px] border border-emerald-200 bg-emerald-50 p-8 text-center"><div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white text-emerald-700"><svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg></div><h3 class="mt-5 text-2xl">Sent to our pharmacist</h3><p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-brand-stone">We’ll be in touch shortly to arrange your consultation — usually the same day. If it’s urgent, call us on <b>${phone}</b> or pop in.</p></div>`;
        } catch (err) {
            btn.disabled = false;
            btn.textContent = 'Send to our pharmacist';
            notify(err.message, 4500);
        }
    });

    root.querySelector('[data-start]')?.addEventListener('click', () => {
        root.querySelector('[data-start-wrap]').hidden = true;
        paint();
    });
}

/* Conditions index: live filter */
const search = document.getElementById('condition-search');
if (search) {
    const cards = [...document.querySelectorAll('[data-condition]')];
    const empty = document.getElementById('condition-empty');
    search.addEventListener('input', () => {
        // Match every word separately rather than the raw string: the keywords that
        // describe a condition are rarely adjacent, so "burning wee" has to find a
        // UTI even though those two words never sit next to each other.
        const terms = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
        let n = 0;
        cards.forEach((c) => {
            const hay = c.dataset.condition;
            const hit = terms.every((t) => hay.includes(t));
            c.hidden = !hit;
            if (hit) n += 1;
        });
        empty.hidden = n > 0;
        document.querySelectorAll('[data-az-group]').forEach((g) => (g.hidden = ![...g.querySelectorAll('[data-condition]')].some((c) => !c.hidden)));
    });
}
