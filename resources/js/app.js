const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------------------------------------------------------------
   Menu overlay
   --------------------------------------------------------------- */

const menu = document.getElementById('site-menu');

function openMenu() {
    menu.hidden = false;
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => menu.classList.add('is-open'));
    menu.querySelector('[data-menu-close]')?.focus();
}

function closeMenu() {
    menu.classList.remove('is-open');
    document.body.style.overflow = '';
    setTimeout(() => {
        menu.hidden = true;
    }, 300);
}

if (menu) {
    document.querySelectorAll('[data-menu-open]').forEach((el) => el.addEventListener('click', openMenu));
    document.querySelectorAll('[data-menu-close]').forEach((el) => el.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !menu.hidden) closeMenu();
    });
}

/* ---------------------------------------------------------------
   Header state + scroll progress
   --------------------------------------------------------------- */

const header = document.querySelector('[data-header]');
const progress = document.getElementById('scroll-progress');

function onScroll() {
    const y = window.scrollY;
    header?.classList.toggle('is-scrolled', y > 40);

    if (progress) {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.transform = `scaleX(${max > 0 ? y / max : 0})`;
    }
}

window.addEventListener('scroll', onScroll, { passive: true });
onScroll();

/* ---------------------------------------------------------------
   Scroll reveal — fade/rise, line masks and clip wipes
   --------------------------------------------------------------- */

const revealables = document.querySelectorAll('.reveal, .clip-reveal, .line-mask');

if (revealables.length) {
    // Children of a [data-reveal-group] cascade at 90ms intervals.
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
        group.querySelectorAll(':scope > .reveal, :scope > .clip-reveal').forEach((child, i) => {
            child.style.setProperty('--reveal-delay', `${i * 90}ms`);
        });
    });

    // Stacked headline lines cascade at 110ms.
    document.querySelectorAll('[data-lines]').forEach((block) => {
        block.querySelectorAll('.line-mask').forEach((line, i) => {
            line.style.setProperty('--reveal-delay', `${i * 110}ms`);
        });
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.05 }
    );

    revealables.forEach((el) => observer.observe(el));
}

/* Hero content animates immediately rather than waiting for a scroll. */
requestAnimationFrame(() => {
    document.querySelectorAll('[data-hero] .reveal, [data-hero] .line-mask, [data-hero] .clip-reveal')
        .forEach((el) => el.classList.add('is-visible'));
});

/* ---------------------------------------------------------------
   Parallax + counters (skipped entirely when motion is reduced)
   --------------------------------------------------------------- */

if (!reduced) {
    const layers = [...document.querySelectorAll('[data-parallax]')];

    if (layers.length) {
        let ticking = false;

        const applyParallax = () => {
            layers.forEach((el) => {
                const speed = parseFloat(el.dataset.parallax) || 0.15;
                const rect = el.getBoundingClientRect();
                if (rect.bottom < -200 || rect.top > window.innerHeight + 200) return;
                const offset = (rect.top + rect.height / 2 - window.innerHeight / 2) * -speed;
                el.style.transform = `translate3d(0, ${offset.toFixed(1)}px, 0)`;
            });
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(applyParallax);
        }, { passive: true });

        applyParallax();
    }

    // Count numbers up when they scroll into view.
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length) {
        const countObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseFloat(el.dataset.count);
                const decimals = (el.dataset.count.split('.')[1] || '').length;
                const started = performance.now();
                const duration = 1400;

                const tick = (now) => {
                    const p = Math.min((now - started) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = (target * eased).toFixed(decimals);
                    if (p < 1) requestAnimationFrame(tick);
                };

                requestAnimationFrame(tick);
                countObserver.unobserve(el);
            });
        }, { threshold: 0.5 });

        counters.forEach((el) => countObserver.observe(el));
    }

    // Cards tilt slightly toward the pointer.
    document.querySelectorAll('.tilt').forEach((card) => {
        card.addEventListener('pointermove', (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            card.style.transform = `perspective(900px) rotateX(${(-py * 4).toFixed(2)}deg) rotateY(${(px * 5).toFixed(2)}deg) translateY(-6px)`;
        });
        card.addEventListener('pointerleave', () => {
            card.style.transform = '';
        });
    });

    // Buttons drift a few pixels toward the cursor.
    document.querySelectorAll('[data-magnetic]').forEach((btn) => {
        btn.addEventListener('pointermove', (e) => {
            const r = btn.getBoundingClientRect();
            const x = (e.clientX - r.left - r.width / 2) * 0.18;
            const y = (e.clientY - r.top - r.height / 2) * 0.3;
            btn.style.transform = `translate(${x.toFixed(1)}px, ${y.toFixed(1)}px)`;
        });
        btn.addEventListener('pointerleave', () => {
            btn.style.transform = '';
        });
    });
}

/* ---------------------------------------------------------------
   Hero rail — auto-rotation with depth-of-field blur
   --------------------------------------------------------------- */

const rail = document.querySelector('[data-carousel]');

if (rail && !reduced) {
    const cards = [...rail.querySelectorAll('[data-carousel-item]')];
    const half = cards.length / 2; // the second set is the duplicate
    let index = 0;
    let paused = false;
    let timer;
    let moveEnd;

    // Distance from the rail's centre decides how much each card is blurred.
    function focus() {
        const rect = rail.getBoundingClientRect();
        const centre = rect.left + rect.width / 2;
        cards.forEach((card) => {
            const r = card.getBoundingClientRect();
            const d = Math.min(Math.abs(r.left + r.width / 2 - centre) / (rect.width / 2), 1);
            const eased = d * d;
            card.style.setProperty('--card-focus-blur', `${(eased * 6).toFixed(2)}px`);
            card.style.setProperty('--card-dim', (1 - eased * 0.4).toFixed(3));
            card.style.setProperty('--card-scale', (1 - eased * 0.04).toFixed(3));
        });
    }

    const offsetOf = (card) => card.offsetLeft - cards[0].offsetLeft;

    function goTo(i, smooth = true) {
        rail.scrollTo({ left: offsetOf(cards[i]), behavior: smooth ? 'smooth' : 'auto' });
    }

    function advance() {
        index += 1;
        rail.classList.add('is-moving');
        clearTimeout(moveEnd);
        moveEnd = setTimeout(() => {
            rail.classList.remove('is-moving');
            // Landed on the duplicate set: jump back to its identical twin.
            if (index >= half) {
                index -= half;
                goTo(index, false);
            }
        }, 900);
        goTo(index);
    }

    function play() {
        clearInterval(timer);
        timer = setInterval(() => {
            if (!paused && !document.hidden) advance();
        }, 3600);
    }

    // A manual click should not fight the timer, so restart it from now.
    function step(dir) {
        if (dir < 0 && index === 0) {
            // Jump to the duplicate of card 0, then scroll back into the first set.
            index = half;
            goTo(index, false);
        }
        if (dir > 0) return advance();
        index -= 1;
        goTo(index);
        play();
    }

    rail.parentElement.querySelector('[data-rail-prev]')?.addEventListener('click', () => step(-1));
    rail.parentElement.querySelector('[data-rail-next]')?.addEventListener('click', () => {
        step(1);
        play();
    });

    rail.addEventListener('scroll', () => requestAnimationFrame(focus), { passive: true });
    ['pointerenter', 'focusin', 'pointerdown'].forEach((e) => rail.addEventListener(e, () => (paused = true)));
    ['pointerleave', 'focusout'].forEach((e) => rail.addEventListener(e, () => (paused = false)));
    document.addEventListener('visibilitychange', () => !document.hidden && play());
    window.addEventListener('resize', () => requestAnimationFrame(focus));

    focus();
    play();
}

/* ---------------------------------------------------------------
   Reviews rail — advances a page of cards at a time
   --------------------------------------------------------------- */

/* Testimonials ---------------------------------------------------------------
   A tablist: the reviewer list selects which review is featured, and the feature
   advances on its own until someone interacts with it. */
const testimonials = document.querySelector('[data-testimonials]');

if (testimonials) {
    const tabs = [...testimonials.querySelectorAll('[data-testimonial-tab]')];
    const panels = [...testimonials.querySelectorAll('[data-testimonial]')];
    const list = testimonials.querySelector('.tm-scroll');
    let current = 0;
    let paused = false;
    let timer;

    // `scroll` is deliberately off for pointer-driven changes: scrolling the list
    // under the cursor would slide a different name beneath it and re-fire hover.
    function show(i, { scroll = false, moveFocus = false } = {}) {
        current = (i + panels.length) % panels.length;

        panels.forEach((panel, n) => {
            const on = n === current;
            panel.classList.toggle('is-active', on);
            panel.toggleAttribute('aria-hidden', !on);
        });

        tabs.forEach((tab, n) => {
            const on = n === current;
            tab.classList.toggle('is-active', on);
            tab.setAttribute('aria-selected', String(on));
            tab.tabIndex = on ? 0 : -1;
        });

        const tab = tabs[current];

        if (scroll) {
            const top = tab.offsetTop - list.offsetTop - (list.clientHeight - tab.offsetHeight) / 2;
            list.scrollTo({ top, behavior: reduced ? 'auto' : 'smooth' });
        }

        if (moveFocus) tab.focus();
    }

    function play() {
        clearInterval(timer);
        if (reduced) return;
        timer = setInterval(() => {
            if (!paused && !document.hidden) show(current + 1, { scroll: true });
        }, 5000);
    }

    tabs.forEach((tab, i) => {
        // Hover swaps the review; click covers touch, where there is no hover.
        tab.addEventListener('pointerenter', (e) => { if (e.pointerType === 'mouse') show(i); });
        tab.addEventListener('click', () => { show(i); play(); });
    });

    // Standard tablist keyboard handling.
    testimonials.querySelector('[role="tablist"]').addEventListener('keydown', (e) => {
        const step = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[e.key];
        if (step) { e.preventDefault(); show(current + step, { scroll: true, moveFocus: true }); play(); return; }
        if (e.key === 'Home') { e.preventDefault(); show(0, { scroll: true, moveFocus: true }); play(); }
        if (e.key === 'End') { e.preventDefault(); show(panels.length - 1, { scroll: true, moveFocus: true }); play(); }
    });

    ['pointerenter', 'focusin'].forEach((e) => testimonials.addEventListener(e, () => (paused = true)));
    ['pointerleave', 'focusout'].forEach((e) => testimonials.addEventListener(e, () => (paused = false)));
    document.addEventListener('visibilitychange', () => !document.hidden && play());

    show(0);
    play();
}


/* Homepage FAQ scroller ------------------------------------------------------
   The list is deliberately shorter than its content, so an opened answer can be
   left half out of view. Bring the question it belongs to up to the top of the
   scroller, and close whichever one was open so only one answer competes for the
   limited height. */
const faqScroll = document.querySelector('.faq-scroll');

if (faqScroll) {
    const items = [...faqScroll.querySelectorAll('details.wl-faq')];

    items.forEach((item) => {
        item.addEventListener('toggle', () => {
            if (!item.open) return;

            items.forEach((other) => other !== item && (other.open = false));

            // Wait a frame so the answer has laid out before measuring.
            requestAnimationFrame(() => {
                const top = item.offsetTop - faqScroll.querySelector('div').offsetTop;
                faqScroll.scrollTo({
                    top,
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth',
                });
            });
        });
    });
}
