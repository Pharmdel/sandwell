@php
    $wl = config('weightloss');
    $reviews = config('pharmacy.reviews');
    $phone = config('pharmacy.phone');
    $btn = 'inline-flex items-center justify-center gap-2 rounded-full px-7 py-3.5 text-sm font-semibold transition-all duration-200 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $btnPrimary = $btn.' bg-brand-moss text-white hover:-translate-y-0.5 hover:bg-brand-moss-hover hover:shadow-moss-glow focus-visible:ring-brand-moss';
    $btnOutline = $btn.' border border-brand-forest text-brand-forest hover:bg-brand-forest hover:text-white focus-visible:ring-brand-forest';
    $btnLight = $btn.' border border-white/25 text-white hover:bg-white hover:text-brand-forest focus-visible:ring-white';
    $input = 'w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-forest transition placeholder:text-brand-stone-light focus:border-brand-forest focus:outline-none focus:ring-2 focus:ring-brand-forest/20';
    $label = 'mb-1.5 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light';
    $opt = 'wl-opt flex w-full flex-col items-start rounded-2xl border border-brand-hairline bg-white px-5 py-4 text-left text-[15px] font-medium text-brand-forest hover:border-brand-forest/50';
    $faqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]], $wl['faqs'])];
@endphp

<x-layout title="Weight loss clinic West Bromwich | Mounjaro & Wegovy" hero description="Pharmacist-led weight loss with Wegovy, Mounjaro, Wegovy oral tablets and Alli — free suitability check, free consultation call, regular check-ins and free local delivery across six Sandwell branches.">
    @push('scripts')
        @vite('resources/js/weight-loss.js')
        <script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endpush

    {{-- Hero --}}
    <x-page-hero image="weightloss-outdoor.jpg" alt="A woman walking outdoors in morning light">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">Weight loss clinic · West Bromwich</p>

            <h1 class="mt-5 text-5xl leading-[1.02] text-white md:text-[76px]" data-lines>
                <span class="line-mask"><span>Pharmacist-led</span></span>
                <span class="line-mask"><span class="editorial-highlight">weight loss.</span></span>
            </h1>

            <p class="reveal mt-8 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Get help losing weight with safe, effective treatments — Wegovy, Mounjaro and Alli, all with
                regular check-ins from our pharmacist in West Bromwich.
            </p>

            <ul class="reveal mt-7 space-y-2.5 text-[15px] font-medium text-white" style="--reveal-delay:400ms">
                @foreach (['GPhC-registered pharmacist care', 'Free 1-minute suitability check', 'All licensed treatments — injections & tablets'] as $li)
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/20 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $li }}
                    </li>
                @endforeach
            </ul>

            <div class="reveal mt-10 flex flex-wrap gap-3" style="--reveal-delay:460ms">
                <button type="button" data-modal-open="wl-checker" data-magnetic class="{{ $btnPrimary }}">Start your free suitability check</button>
                <a href="#switch" class="{{ $btnLight }}" data-magnetic>Already on treatment? Switch to us</a>
            </div>

            <div class="no-interact reveal mt-12 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-white/15 pt-7 text-sm text-white/80" style="--reveal-delay:540ms">
                <span class="text-[11px] font-bold uppercase tracking-[0.12em] text-white">Highly rated</span>
                <span class="flex gap-0.5 text-brand-moss" aria-hidden="true">
                    @for ($i = 0; $i < 5; $i++)<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L1.5 7.7l5.9-.9z"/></svg>@endfor
                </span>
                <span><span data-count="{{ $reviews['count'] }}">{{ $reviews['count'] }}</span> Google reviews across our six pharmacies</span>
            </div>
        </div>
    </x-page-hero>

    {{-- Treatments --}}
    <section id="treatments" class="scroll-mt-28 border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Our treatments</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Four proven options — <span class="editorial-highlight">one that’s right for you.</span></h2>
                <p class="mt-5 text-base leading-relaxed text-brand-stone">
                    Injections, daily tablets or capsules — every plan comes with regular pharmacist check-ins.
                    Answer five quick questions and we’ll recommend the best fit.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                @foreach ($wl['treatments'] as $t)
                    <article class="reveal tilt relative flex flex-col rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card {{ $t['badge'] ? 'ring-1 ring-brand-moss/40' : '' }}">
                        @if ($t['badge'])
                            <x-chip tone="orange" class="absolute right-5 top-5">{{ $t['badge'] }}</x-chip>
                        @endif
                        <div class="flex h-40 items-center justify-center rounded-2xl bg-brand-ivory p-4">
                            <img src="{{ asset('images/'.$t['image']) }}" alt="{{ $t['name'] }}" class="max-h-full max-w-full object-contain">
                        </div>
                        <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">{{ $t['type'] }}</p>
                        <h3 class="mt-1.5 text-[24px] leading-tight">{{ $t['name'] }}</h3>
                        <ul class="mt-4 space-y-2 text-sm leading-relaxed text-brand-stone">
                            @foreach ($t['bullets'] as $b)
                                <li class="flex gap-2.5"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-moss"></span>{{ $b }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-auto flex flex-col gap-2 pt-6">
                            <button type="button" data-modal-open="wl-checker" data-preselect="{{ $t['key'] }}" class="{{ $btnPrimary }} w-full">Check my suitability →</button>
                            <button type="button" data-modal-open="info-{{ $t['key'] }}" class="text-sm font-semibold text-brand-forest underline decoration-brand-hairline underline-offset-4 hover:decoration-brand-moss">More about {{ str($t['name'])->before('®') }}</button>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Foundayo --}}
            <article class="reveal mt-6 grid gap-8 rounded-[24px] border border-brand-hairline bg-white p-8 shadow-editorial-card lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-chip tone="navy">Coming soon</x-chip>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">From the makers of Mounjaro</span>
                    </div>
                    <h3 class="mt-3 text-[28px] leading-tight">{{ $wl['foundayo']['name'] }}</h3>
                    <p class="mt-3 text-base leading-relaxed text-brand-stone">{{ $wl['foundayo']['intro'] }}</p>
                    <ul class="mt-5 grid gap-2 text-sm leading-relaxed text-brand-stone sm:grid-cols-2">
                        @foreach ($wl['foundayo']['bullets'] as $b)
                            <li class="flex gap-2.5"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-moss"></span>{{ $b }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="lg:col-span-4">
                    <button type="button" data-modal-open="wl-waitlist" class="{{ $btnOutline }} w-full">Register your interest →</button>
                    <p class="mt-3 text-center text-xs leading-relaxed text-brand-stone-light">{{ $wl['foundayo']['note'] }}</p>
                </div>
            </article>

            <div class="reveal mt-10 text-center">
                <button type="button" data-modal-open="wl-checker" class="text-sm font-semibold text-brand-moss hover:underline">Not sure? Take the 1-minute suitability check →</button>
            </div>
        </div>
    </section>

    {{-- Outcomes --}}
    <section class="no-interact border-y border-brand-pistachio-line bg-brand-pistachio py-20 md:py-24">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="grid gap-6 md:grid-cols-2" data-reveal-group>
                @foreach ($wl['outcomes'] as $o)
                    <div class="reveal rounded-[20px] border border-brand-pistachio-line bg-white p-8">
                        <p class="font-serif text-6xl text-brand-forest md:text-7xl">−<span data-count="{{ $o['figure'] }}">{{ $o['figure'] }}</span> <span class="text-3xl text-brand-stone-light">lbs</span></p>
                        <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-moss">{{ $o['when'] }}</p>
                        <p class="mt-1 text-lg text-brand-forest">{{ $o['text'] }}</p>
                    </div>
                @endforeach
            </div>
            <p class="reveal mt-6 text-xs leading-relaxed text-brand-stone">{{ $wl['outcomes_disclaimer'] }}</p>
        </div>
    </section>

    {{-- Journey --}}
    <section id="journey" class="scroll-mt-28 bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>How it works</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Your weight loss journey <span class="editorial-highlight">starts here.</span></h2>
            </div>
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                @foreach ($wl['journey'] as $i => $s)
                    <div class="reveal rounded-[20px] border border-brand-hairline bg-white p-7 shadow-editorial-card">
                        <div class="flex items-center justify-between">
                            <span class="font-serif text-4xl text-brand-moss">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <x-chip tone="quiet">{{ $s['when'] }}</x-chip>
                        </div>
                        <h3 class="mt-4 text-xl">{{ $s['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $s['body'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="reveal mt-12 flex flex-col items-center gap-5 text-center">
                <button type="button" data-modal-open="wl-checker" class="{{ $btnPrimary }}">Get started →</button>
                <p class="max-w-2xl text-sm leading-relaxed text-brand-stone">{{ $wl['eligibility_note'] }}</p>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="no-interact border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Real results</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">What our weight-loss patients <span class="editorial-highlight">say.</span></h2>
            </div>
            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach ($wl['testimonials'] as $q)
                    <figure class="reveal flex flex-col rounded-[20px] border border-brand-hairline bg-brand-ivory p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-forest text-sm font-semibold text-white">{{ $q['initial'] }}</span>
                            <span>
                                <span class="block text-sm font-semibold text-brand-forest">{{ $q['name'] }}</span>
                                <span class="flex gap-0.5 text-brand-moss" aria-label="Five stars">
                                    @for ($i = 0; $i < 5; $i++)<svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L1.5 7.7l5.9-.9z"/></svg>@endfor
                                </span>
                            </span>
                        </div>
                        <p class="mt-4 text-sm font-bold text-brand-moss-hover">{{ $q['result'] }}</p>
                        <blockquote class="mt-2 font-serif text-lg italic leading-relaxed text-brand-forest">“{{ $q['quote'] }}”</blockquote>
                        <figcaption class="mt-auto pt-4 text-xs text-brand-stone-light">Weight loss patient</figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Switch --}}
    <section id="switch" class="scroll-mt-28 bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] items-center gap-12 px-6 sm:px-8 lg:grid-cols-2">
            <div class="reveal">
                <x-eyebrow>Already on treatment?</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Already taking weight-loss treatment? <span class="editorial-highlight">Switch to us.</span></h2>
                <p class="mt-5 text-base leading-relaxed text-brand-stone">
                    On Wegovy or Mounjaro with an online provider? Move your care to a local pharmacist — we match
                    your current medication and dose, with face-to-face support whenever you want it.
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach ($wl['switch']['bullets'] as $b)
                        <li class="flex items-center gap-3 text-[15px] font-medium text-brand-forest">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            {{ $b }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="reveal rounded-[24px] border border-brand-hairline bg-white p-8 text-center shadow-editorial-card md:p-10">
                <p class="font-serif text-3xl text-brand-forest">Switch in <span class="editorial-highlight">2 minutes.</span></p>
                <p class="mt-3 text-sm leading-relaxed text-brand-stone">Tell us what you’re on — we’ll match it and call you to arrange the handover.</p>
                <button type="button" data-modal-open="wl-switch" class="{{ $btnPrimary }} mt-6 w-full">Switch in 2 minutes →</button>
                <p class="mt-3 text-xs text-brand-stone-light">Takes two minutes — we handle the rest.</p>
            </div>
        </div>
    </section>

    {{-- FAQs --}}
    <section id="faqs" class="scroll-mt-28 border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Good to know</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Weight loss — <span class="editorial-highlight">your questions answered.</span></h2>
            </div>
            <div class="mt-12 max-w-3xl divide-y divide-brand-hairline border-y border-brand-hairline" data-reveal-group>
                @foreach ($wl['faqs'] as [$q, $a])
                    <details class="wl-faq reveal group py-1">
                        <summary class="flex items-center justify-between gap-6 py-5 text-lg font-medium text-brand-forest">
                            {{ $q }}
                            <span class="wl-faq__icon flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-brand-hairline text-brand-moss transition-transform duration-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg>
                            </span>
                        </summary>
                        <div class="pb-6 pr-14 text-[15px] leading-relaxed text-brand-stone">{{ $a }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Guides --}}
    <section id="guides" class="scroll-mt-28 bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Health Hub</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Read up <span class="editorial-highlight">before you decide.</span></h2>
                <p class="mt-5 text-base leading-relaxed text-brand-stone">Pharmacist-written guides to every treatment we offer — how each one works, what it costs, and how to handle the side effects.</p>
            </div>
            <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach ($wl['guides'] as $g)
                    <a href="{{ route('health-hub.show', $g['slug']) }}" class="reveal tilt group flex flex-col rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover">
                        <x-chip tone="quiet" class="self-start">{{ $g['chip'] }}</x-chip>
                        <h3 class="mt-3 text-lg leading-snug">{{ $g['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $g['blurb'] }}</p>
                        <span class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-brand-moss">Read the guide <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg></span>
                    </a>
                @endforeach
            </div>
            <div class="reveal mt-10 text-center">
                <a href="{{ route('health-hub') }}#weight-loss" class="text-sm font-semibold text-brand-moss hover:underline">All weight loss guides →</a>
            </div>
        </div>
    </section>

    {{-- Regulatory --}}
    <section class="border-t border-brand-hairline bg-white py-16">
        <div class="mx-auto grid max-w-[1280px] gap-8 px-6 text-sm sm:px-8 md:grid-cols-3">
            <div class="reveal">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Regulation</p>
                <p class="mt-2 text-brand-forest">GPhC-registered pharmacist care. Regulated by the <a href="https://www.pharmacyregulation.org/registers/pharmacy" target="_blank" rel="noopener" class="font-semibold underline decoration-brand-hairline underline-offset-4 hover:decoration-brand-moss">General Pharmaceutical Council</a>.</p>
            </div>
            <div class="reveal">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Superintendent pharmacists</p>
                <p class="mt-2 text-brand-forest">One per operating company — <a href="{{ route('terms') }}#companies" class="font-semibold underline decoration-brand-hairline underline-offset-4 hover:decoration-brand-moss">named on each branch page</a>.</p>
            </div>
            <div class="reveal">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Registered pharmacies</p>
                <p class="mt-2 text-brand-forest">Six premises, each separately registered — <a href="{{ route('terms') }}#companies" class="font-semibold underline decoration-brand-hairline underline-offset-4 hover:decoration-brand-moss">see the full list</a>.</p>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="grain relative overflow-hidden bg-brand-forest-deep py-24 text-center md:py-32">
        <div class="pointer-events-none absolute left-1/2 top-1/2 h-[600px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[radial-gradient(circle,rgba(31,79,45,0.55),transparent_70%)]"></div>
        <div class="reveal relative mx-auto max-w-3xl px-6">
            <h2 class="text-4xl leading-tight text-white md:text-[52px]">Start your journey <span class="editorial-highlight">this week.</span></h2>
            <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-slate-300">A free one-minute check, a free consultation call, and nothing to pay until you begin treatment.</p>
            <button type="button" data-modal-open="wl-checker" data-magnetic class="{{ $btnPrimary }} mt-9">Start your free suitability check</button>
            <p class="mt-5 text-sm text-slate-400">Or call <span class="font-semibold text-white">{{ $phone }}</span></p>
        </div>
    </section>

    {{-- ============ Suitability checker ============ --}}
    <div id="wl-checker" class="wl-modal" hidden role="dialog" aria-modal="true" aria-labelledby="wl-checker-title" data-endpoint="{{ route('weight-loss.consultation') }}">
        <div class="absolute inset-0 bg-brand-forest-deep/60 backdrop-blur-sm" data-modal-close></div>
        <div class="wl-modal__box relative max-h-[94vh] w-full max-w-2xl overflow-y-auto rounded-t-3xl bg-white p-6 shadow-editorial-hover sm:rounded-3xl sm:p-10">
            <button type="button" data-modal-close aria-label="Close" class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-brand-ivory text-brand-forest hover:bg-brand-forest hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
            <div data-body>
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">Free suitability check</p>
                <h2 id="wl-checker-title" class="mt-2 text-3xl leading-tight">Find your <span class="editorial-highlight">best-fit treatment.</span></h2>
                <p class="mt-2 text-sm text-brand-stone">Five quick questions — takes under a minute.</p>
                <div class="mt-5 flex gap-1.5" aria-hidden="true">
                    @for ($i = 0; $i < 6; $i++)<span data-dot class="h-1.5 flex-1 rounded-full bg-brand-hairline transition-colors"></span>@endfor
                </div>

                <div class="wl-step mt-7" data-step="choice">
                    <h3 class="text-xl">Which treatment would you like?</h3>
                    <div class="mt-4 grid gap-2.5" role="radiogroup" aria-label="Treatment">
                        @foreach ($wl['treatments'] as $t)
                            <button type="button" role="radio" aria-checked="false" data-q="choice" data-v="{{ $t['key'] }}" class="{{ $opt }}">
                                {{ $t['name'] }}<span class="wl-opt__sub text-xs font-normal text-brand-stone">{{ $t['short'] }}</span>
                            </button>
                        @endforeach
                        <button type="button" role="radio" aria-checked="false" data-q="choice" data-v="rec" class="{{ $opt }} border-dashed">✨ Recommend me one based on my needs</button>
                    </div>
                </div>

                <div class="wl-step mt-7" data-step="bmi" hidden>
                    <h3 class="text-xl">Do you know your BMI?</h3>
                    <label for="wlq_bmi" class="{{ $label }} mt-4">Your BMI</label>
                    <input id="wlq_bmi" name="bmi" type="number" step="0.1" min="10" max="80" inputmode="decimal" placeholder="e.g. 31.4" class="{{ $input }}">
                    <button type="button" data-toggle-hw aria-expanded="false" class="{{ $opt }} mt-3">I don’t know my BMI<span class="wl-opt__sub text-xs font-normal text-brand-stone">We’ll work it out from your height &amp; weight</span></button>
                    <div data-hw hidden class="mt-4 rounded-2xl border border-brand-hairline bg-brand-ivory p-5">
                        <div class="flex gap-2" role="group" aria-label="Units">
                            <button type="button" data-unit="metric" aria-pressed="true" class="rounded-full border border-brand-forest px-4 py-1.5 text-xs font-semibold text-brand-forest aria-pressed:bg-brand-forest aria-pressed:text-white">cm / kg</button>
                            <button type="button" data-unit="imperial" aria-pressed="false" class="rounded-full border border-brand-forest px-4 py-1.5 text-xs font-semibold text-brand-forest aria-pressed:bg-brand-forest aria-pressed:text-white">ft &amp; in / st &amp; lb</button>
                        </div>
                        <div data-units="metric" class="mt-4">
                            <div data-unit-group="metric" class="grid gap-3 sm:grid-cols-2">
                                <div><label for="wlq_h" class="{{ $label }}">Height (cm)</label><input id="wlq_h" name="height_cm" type="number" min="100" max="250" inputmode="decimal" data-bmi-input class="{{ $input }}"></div>
                                <div><label for="wlq_w" class="{{ $label }}">Weight (kg)</label><input id="wlq_w" name="weight_kg" type="number" min="30" max="400" inputmode="decimal" data-bmi-input class="{{ $input }}"></div>
                            </div>
                            <div data-unit-group="imperial" hidden class="grid gap-3 sm:grid-cols-4">
                                <div><label for="wlq_ft" class="{{ $label }}">Height (ft)</label><input id="wlq_ft" name="height_ft" type="number" min="3" max="8" inputmode="numeric" data-bmi-input class="{{ $input }}"></div>
                                <div><label for="wlq_in" class="{{ $label }}">(in)</label><input id="wlq_in" name="height_in" type="number" min="0" max="11" inputmode="numeric" data-bmi-input class="{{ $input }}"></div>
                                <div><label for="wlq_st" class="{{ $label }}">Weight (st)</label><input id="wlq_st" name="weight_st" type="number" min="4" max="60" inputmode="numeric" data-bmi-input class="{{ $input }}"></div>
                                <div><label for="wlq_lb" class="{{ $label }}">(lb)</label><input id="wlq_lb" name="weight_lb" type="number" min="0" max="13" inputmode="numeric" data-bmi-input class="{{ $input }}"></div>
                            </div>
                        </div>
                        <p data-bmi-out hidden aria-live="polite" class="mt-4 rounded-xl bg-white px-4 py-3 text-sm text-brand-forest"></p>
                    </div>
                </div>

                <div class="wl-step mt-7" data-step="form" hidden>
                    <h3 class="text-xl">Which are you comfortable taking?</h3>
                    <div class="mt-4 grid gap-2.5" role="radiogroup">
                        @foreach ([['tablets', 'Tablets or capsules only'], ['injections', 'Injections only'], ['either', 'Either — whatever works best']] as [$v, $l])
                            <button type="button" role="radio" aria-checked="false" data-q="form" data-v="{{ $v }}" class="{{ $opt }}">{{ $l }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="wl-step mt-7" data-step="budget" hidden>
                    <h3 class="text-xl">What’s your monthly budget?</h3>
                    <div class="mt-4 grid gap-2.5" role="radiogroup">
                        @foreach ([['60', 'Around £60 a month'], ['150', '£100–150 a month'], ['350', '£200–350 a month']] as [$v, $l])
                            <button type="button" role="radio" aria-checked="false" data-q="budget" data-v="{{ $v }}" class="{{ $opt }}">{{ $l }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="wl-step mt-7" data-step="pace" hidden>
                    <h3 class="text-xl">How quickly would you like to lose weight?</h3>
                    <div class="mt-4 grid gap-2.5" role="radiogroup">
                        @foreach ([['steady', 'Steady — around 0.5–1 lb a week'], ['moderate', 'Moderate — around 1–2 lb a week'], ['fast', 'As fast as is safely possible']] as [$v, $l])
                            <button type="button" role="radio" aria-checked="false" data-q="pace" data-v="{{ $v }}" class="{{ $opt }}">{{ $l }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="wl-step mt-7" data-step="result" hidden>
                    <div data-rec aria-live="polite"></div>
                    <div data-book-cta class="mt-5">
                        <p class="text-sm text-brand-stone">No payment now — our pharmacist calls you to confirm everything first.</p>
                        <button type="button" data-modal-open="wl-switch" class="mt-3 text-sm font-semibold text-brand-moss hover:underline">Already on treatment from another provider? Switch to us instead →</button>
                    </div>
                    <form data-booking hidden class="mt-6 space-y-4" onsubmit="return false">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div><label for="wlq_name" class="{{ $label }}">Full name *</label><input id="wlq_name" name="name" type="text" autocomplete="name" required class="{{ $input }}"></div>
                            <div><label for="wlq_phone" class="{{ $label }}">Phone *</label><input id="wlq_phone" name="phone" type="tel" autocomplete="tel" required class="{{ $input }}"></div>
                            <div><label for="wlq_email" class="{{ $label }}">Email</label><input id="wlq_email" name="email" type="email" autocomplete="email" class="{{ $input }}"></div>
                            <div><label for="wlq_time" class="{{ $label }}">Best time to call</label>
                                <select id="wlq_time" name="best_time" class="{{ $input }}">
                                    @foreach ($wl['best_times'] as $bt)<option value="{{ $bt }}">{{ $bt }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div><label for="wlq_conditions" class="{{ $label }}">Any medical conditions?</label><input id="wlq_conditions" name="conditions" type="text" placeholder="e.g. type 2 diabetes, thyroid, none" class="{{ $input }}"></div>
                        <div><label for="wlq_meds" class="{{ $label }}">Current medication</label><input id="wlq_meds" name="medication" type="text" placeholder="Anything you take regularly" class="{{ $input }}"></div>
                        <div><label for="wlq_nhs" class="{{ $label }}">NHS number (optional)</label><input id="wlq_nhs" name="nhs_number" type="text" inputmode="numeric" placeholder="10 digits" class="{{ $input }}"></div>
                        <label class="flex items-start gap-3 rounded-xl bg-brand-ivory p-4 text-sm leading-relaxed text-brand-stone">
                            <input type="checkbox" name="scr_consent" class="mt-1 h-4 w-4 rounded border-brand-hairline accent-brand-moss">
                            <span>I consent to Hollytree Pharmacy pharmacist accessing my <strong class="text-brand-forest">NHS Summary Care Record</strong> to check my medication history before supplying treatment.</span>
                        </label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    </form>
                </div>

                <div class="mt-8 flex items-center justify-between gap-3 border-t border-brand-hairline pt-5">
                    <button type="button" data-back class="{{ $btnOutline }}" style="visibility:hidden">← Back</button>
                    <button type="button" data-next class="{{ $btnPrimary }}">Continue →</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ Treatment info modals ============ --}}
    @foreach ($wl['treatments'] as $t)
        <div id="info-{{ $t['key'] }}" class="wl-modal" hidden role="dialog" aria-modal="true" aria-labelledby="info-{{ $t['key'] }}-title">
            <div class="absolute inset-0 bg-brand-forest-deep/60 backdrop-blur-sm" data-modal-close></div>
            <div class="wl-modal__box relative max-h-[94vh] w-full max-w-2xl overflow-y-auto rounded-t-3xl bg-white p-6 shadow-editorial-hover sm:rounded-3xl sm:p-10">
                <button type="button" data-modal-close aria-label="Close" class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-brand-ivory text-brand-forest hover:bg-brand-forest hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
                <div class="grid gap-6 sm:grid-cols-[180px_1fr] sm:items-start">
                    <div class="flex h-44 items-center justify-center rounded-2xl bg-brand-ivory p-4"><img src="{{ asset('images/'.$t['image']) }}" alt="{{ $t['name'] }}" class="max-h-full max-w-full object-contain"></div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-moss">{{ $t['tag'] }}</p>
                        <h2 id="info-{{ $t['key'] }}-title" class="mt-1 text-3xl leading-tight">{{ $t['name'] }}</h2>
                    </div>
                </div>
                <div class="mt-6 space-y-4 text-[15px] leading-relaxed text-brand-stone">
                    @foreach ($t['detail'] as [$h, $p])
                        <p><strong class="text-brand-forest">{{ $h }}:</strong> {{ $p }}</p>
                    @endforeach
                </div>
                <p class="mt-6 text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Available strengths</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($t['strengths'] as $s)<span class="rounded-full border border-brand-hairline bg-brand-ivory px-3 py-1 text-xs font-medium text-brand-forest">{{ $s }}</span>@endforeach
                </div>
                <p class="mt-3 text-xs leading-relaxed text-brand-stone-light">{{ $t['strength_note'] }}</p>
                <button type="button" data-modal-open="wl-checker" data-preselect="{{ $t['key'] }}" class="{{ $btnPrimary }} mt-6 w-full">Check my suitability →</button>
            </div>
        </div>
    @endforeach

    {{-- ============ Switch modal ============ --}}
    <div id="wl-switch" class="wl-modal" hidden role="dialog" aria-modal="true" aria-labelledby="wl-switch-title">
        <div class="absolute inset-0 bg-brand-forest-deep/60 backdrop-blur-sm" data-modal-close></div>
        <div class="wl-modal__box relative max-h-[94vh] w-full max-w-xl overflow-y-auto rounded-t-3xl bg-white p-6 shadow-editorial-hover sm:rounded-3xl sm:p-10">
            <button type="button" data-modal-close aria-label="Close" class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-brand-ivory text-brand-forest hover:bg-brand-forest hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
            <div data-body>
                <h2 id="wl-switch-title" class="text-3xl leading-tight">Switch your treatment <span class="editorial-highlight">to us.</span></h2>
                <p class="mt-2 text-sm text-brand-stone">Tell us what you’re on — we’ll match it and call you to arrange the handover.</p>
                <form id="wl-switch-form" action="{{ route('weight-loss.switch') }}" method="post" class="mt-6 space-y-4">
                    <div><label for="wls_med" class="{{ $label }}">Which medication are you currently taking? *</label>
                        <select id="wls_med" name="medication" required class="{{ $input }}">
                            <option value="">Select…</option>
                            @foreach ($wl['switch']['medications'] as $m)<option value="{{ $m }}">{{ $m }}</option>@endforeach
                        </select>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label for="wls_dose" class="{{ $label }}">Current strength / dose *</label><input id="wls_dose" name="dose" type="text" required placeholder="e.g. Mounjaro 5mg weekly" class="{{ $input }}"></div>
                        <div><label for="wls_provider" class="{{ $label }}">Current provider</label><input id="wls_provider" name="provider" type="text" placeholder="e.g. an online clinic" class="{{ $input }}"></div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label for="wls_name" class="{{ $label }}">Full name *</label><input id="wls_name" name="name" type="text" autocomplete="name" required class="{{ $input }}"></div>
                        <div><label for="wls_phone" class="{{ $label }}">Phone *</label><input id="wls_phone" name="phone" type="tel" autocomplete="tel" required class="{{ $input }}"></div>
                    </div>
                    <div><label for="wls_email" class="{{ $label }}">Email</label><input id="wls_email" name="email" type="email" autocomplete="email" class="{{ $input }}"></div>
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    <button type="submit" class="{{ $btnPrimary }} w-full">Request my switch</button>
                    <p class="text-xs leading-relaxed text-brand-stone-light">We’ll call you to confirm your dose history before anything changes — you stay on schedule throughout.</p>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ Foundayo waiting list ============ --}}
    <div id="wl-waitlist" class="wl-modal" hidden role="dialog" aria-modal="true" aria-labelledby="wl-waitlist-title">
        <div class="absolute inset-0 bg-brand-forest-deep/60 backdrop-blur-sm" data-modal-close></div>
        <div class="wl-modal__box relative max-h-[94vh] w-full max-w-xl overflow-y-auto rounded-t-3xl bg-white p-6 shadow-editorial-hover sm:rounded-3xl sm:p-10">
            <button type="button" data-modal-close aria-label="Close" class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-brand-ivory text-brand-forest hover:bg-brand-forest hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
            <div data-body>
                <h2 id="wl-waitlist-title" class="text-3xl leading-tight">Register your <span class="editorial-highlight">interest.</span></h2>
                <p class="mt-2 text-sm text-brand-stone">Foundayo isn’t in our hands yet. Leave your details and we’ll contact you as soon as we can supply it — in the order people registered. Nothing to pay now.</p>
                <form id="wl-waitlist-form" action="{{ route('weight-loss.waitlist') }}" method="post" class="mt-6 space-y-4">
                    <div><label for="fdy_name" class="{{ $label }}">Full name *</label><input id="fdy_name" name="name" type="text" autocomplete="name" required class="{{ $input }}"></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label for="fdy_phone" class="{{ $label }}">Phone *</label><input id="fdy_phone" name="phone" type="tel" autocomplete="tel" required class="{{ $input }}"></div>
                        <div><label for="fdy_email" class="{{ $label }}">Email</label><input id="fdy_email" name="email" type="email" autocomplete="email" class="{{ $input }}"></div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label for="fdy_h" class="{{ $label }}">Height (cm)</label><input id="fdy_h" name="height_cm" type="number" min="100" max="250" inputmode="decimal" class="{{ $input }}"></div>
                        <div><label for="fdy_w" class="{{ $label }}">Weight (kg)</label><input id="fdy_w" name="weight_kg" type="number" min="30" max="400" inputmode="decimal" class="{{ $input }}"></div>
                    </div>
                    <p data-fdy-bmi aria-live="polite" class="rounded-xl bg-brand-ivory px-4 py-3 text-sm text-brand-forest">Enter your height and weight and we’ll work out your BMI.</p>
                    <div><label for="fdy_cur" class="{{ $label }}">Are you on a weight loss treatment now?</label>
                        <select id="fdy_cur" name="current_treatment" class="{{ $input }}">
                            <option value="none">No, nothing at the moment</option>
                            <option value="mounjaro">Yes — Mounjaro</option>
                            <option value="wegovy">Yes — Wegovy</option>
                            <option value="other">Yes — something else</option>
                        </select>
                    </div>
                    <div><label for="fdy_pref" class="{{ $label }}">How should we contact you?</label>
                        <select id="fdy_pref" name="contact_pref" class="{{ $input }}">
                            <option value="phone">Phone call</option>
                            <option value="email">Email</option>
                            <option value="either">Either is fine</option>
                        </select>
                    </div>
                    <div><label for="fdy_notes" class="{{ $label }}">Anything you’d like us to know (optional)</label><textarea id="fdy_notes" name="notes" rows="3" placeholder="Medical conditions, previous treatments, questions…" class="{{ $input }}"></textarea></div>
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    <button type="submit" class="{{ $btnPrimary }} w-full">Add me to the list</button>
                    <p class="text-xs leading-relaxed text-brand-stone-light">{{ $wl['foundayo']['small_print'] }}</p>
                </form>
            </div>
        </div>
    </div>

    <div id="wl-toast" role="status" aria-live="polite" class="max-w-[90vw] rounded-full bg-brand-forest px-5 py-3 text-sm text-white shadow-editorial-hover"></div>
</x-layout>
