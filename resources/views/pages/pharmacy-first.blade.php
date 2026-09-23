@php
    // Pharmacy First and the Minor Ailments Scheme are two NHS routes to the same
    // outcome — a pharmacist treating you without a GP appointment — so they live on
    // one page, the way the group presents them themselves.
    $all = collect(config('conditions.list'));
    $pf = $all->where('tag', 'Pharmacy First')->values();

    // The grid shows every condition the group cards on their own page — the seven
    // Pharmacy First pathways plus the minor ailments alongside them. The A–Z
    // further down still carries all 35, so nothing is only reachable by search.
    $carded = $all->filter(fn ($c) => ! empty($c['icon']))
        ->sortBy(fn ($c) => [$c['tag'] === 'Pharmacy First' ? 0 : 1, $c['name']])
        ->values();
    $az = $all->flatMap(fn ($c) => collect($c['az'])->map(fn ($n) => [
            'name' => $n,
            'slug' => $c['slug'],
            'tag' => $c['tag'],
            'search' => strtolower($c['name'].' '.implode(' ', $c['az']).' '.$c['kw'].' '.implode(' ', $c['sym'])),
        ]))
        ->sortBy(fn ($x) => str($x['name'])->ascii()->lower())
        ->groupBy(fn ($x) => strtoupper(str($x['name'])->ascii()->substr(0, 1)));
@endphp

<x-layout title="Pharmacy First & minor illness" hero
    description="Free same-day NHS treatment for seven common conditions under Pharmacy First, plus free medicines for everyday minor ailments — no GP appointment needed.">
    @push('scripts') @vite(['resources/js/conditions.js', 'resources/js/services.js']) @endpush

    <x-page-hero image="mother-toddler.jpg" alt="A pharmacist with a mother and toddler in a consultation room">
        <div class="max-w-3xl">
            <div class="reveal"><x-chip tone="nhs">NHS Pharmacy First &amp; minor illness</x-chip></div>

            <h1 class="mt-6 text-4xl leading-[1.05] text-white md:text-[66px]" data-lines>
                <span class="line-mask"><span>See a pharmacist,</span></span>
                <span class="line-mask"><span class="editorial-highlight">not a waiting room.</span></span>
            </h1>

            <p class="reveal mt-7 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Free same-day treatment for seven common conditions — including antibiotics where
                appropriate — plus free medicines for everyday ailments. No GP appointment either way.
            </p>

            <div class="reveal mt-8 max-w-xl" style="--reveal-delay:420ms">
                <label for="condition-search" class="sr-only">Search symptoms or conditions</label>
                <input id="condition-search" type="search"
                    placeholder="Search a symptom or condition — e.g. itchy scalp, burning wee, earache"
                    class="w-full rounded-full border border-white/25 bg-white/10 px-6 py-4 text-[15px] text-white backdrop-blur placeholder:text-white/50 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/30">
            </div>

            <div class="no-interact reveal mt-10 flex flex-wrap gap-x-6 gap-y-2 border-t border-white/15 pt-7 text-[11px] font-semibold uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:540ms">
                <span>Free on the NHS</span>
                <span>No appointment needed</span>
                <span>All six branches</span>
            </div>
        </div>
    </x-page-hero>

    {{-- 1. Everything we can treat, carded by scheme --}}
    <section id="pharmacy-first" class="scroll-mt-24 border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>What can we treat you for?</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Seen and treated, <span class="editorial-highlight">the same day.</span></h2>
                <p class="mt-4 text-base leading-relaxed text-brand-stone">
                    Seven conditions come under NHS Pharmacy First, where our pharmacist can supply
                    prescription medicines directly. The rest fall under the Minor Ailments Scheme.
                    Either way it is free, and no GP appointment is needed.
                </p>
            </div>

            {{-- A collage rather than a even grid: the cards flow into columns and each
                 one crops its photo to a different depth, so the rows stagger instead of
                 marching. Card contents are unchanged. --}}
            <div class="condition-collage mt-12" data-reveal-group>
                @php $crops = ['h-40', 'h-28', 'h-52', 'h-32', 'h-44', 'h-24', 'h-36']; @endphp
                @foreach ($carded as $c)
                    @php
                        $isPf = $c['tag'] === 'Pharmacy First';
                        $crop = $crops[$loop->index % count($crops)];
                    @endphp
                    <a href="{{ route('conditions.show', $c['slug']) }}"
                        data-condition="{{ strtolower($c['name'].' '.implode(' ', $c['az']).' '.$c['kw'].' '.implode(' ', $c['sym'])) }}"
                        class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-4 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                        <span class="block overflow-hidden rounded-2xl">
                            <img src="{{ asset('images/'.$c['icon']) }}" alt="" loading="lazy"
                                class="{{ $crop }} w-full object-cover transition-transform duration-[600ms] group-hover:scale-[1.06]">
                        </span>
                        <span class="px-2 pb-1">
                            <span @class([
                                'mt-4 inline-block rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em]',
                                'bg-brand-nhs-blue/10 text-brand-nhs-blue' => $isPf,
                                'bg-brand-pistachio text-brand-moss' => ! $isPf,
                            ])>{{ $isPf ? 'Pharmacy First' : 'Minor ailments' }}</span>
                            <h3 class="mt-3 text-xl leading-snug">{{ $c['name'] }}</h3>
                            <p class="mt-1 text-xs text-brand-stone-light">{{ $c['age_label'] }} · free on the NHS</p>
                            <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss">
                                Check my symptoms
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </span>
                        </span>
                    </a>
                @endforeach
            </div>

            <p class="reveal mt-10 text-sm text-brand-stone">
                Not listed? We treat {{ $all->count() }} conditions in total —
                <a href="#a-z" class="font-semibold text-brand-moss hover:underline">see the full A–Z</a>.
            </p>
        </div>
    </section>

    {{-- 2. How it works --}}
    <section class="no-interact border-y border-brand-pistachio-line bg-brand-pistachio py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 text-center sm:px-8">
            <div class="reveal">
                <x-eyebrow>How it works</x-eyebrow>
                <h2 class="mx-auto mt-3 max-w-2xl text-4xl leading-tight md:text-[42px]">Three steps <span class="editorial-highlight">to feeling better.</span></h2>
            </div>
            <x-steps :items="[
                ['title' => 'Walk in or book', 'body' => 'No referral, no appointment necessary at any branch.'],
                ['title' => 'Private pharmacist assessment', 'body' => 'A proper consultation in a private room, not over the counter.'],
                ['title' => 'Treatment on the spot', 'body' => 'Including prescription medicines where clinically appropriate.'],
            ]" />
        </div>
    </section>

    {{-- 3. The Minor Ailments Scheme, explained --}}
    <section id="minor-ailments" class="scroll-mt-24 border-b border-brand-hairline bg-white py-24 md:py-28">
        <div class="mx-auto grid max-w-[1280px] gap-12 px-6 sm:px-8 lg:grid-cols-2 lg:gap-16">
            <div class="reveal">
                <x-eyebrow>The other NHS route</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Minor ailments, <span class="editorial-highlight">free of charge.</span></h2>
                <p class="mt-5 text-base leading-relaxed text-brand-stone">
                    Pharmacy First covers seven specific conditions. The Minor Ailments Scheme covers
                    everything else on this page — coughs, colds, hayfever, threadworm, nappy rash and
                    the rest — with our pharmacist assessing your symptoms and supplying the medicine
                    free of charge to eligible patients.
                </p>
                <p class="mt-4 text-base leading-relaxed text-brand-stone">
                    No GP appointment, no prescription charge, and no need to work out which scheme you
                    fall under — walk in and our pharmacist will place you on the right one.
                </p>
            </div>
            <div class="reveal rounded-[20px] border border-brand-hairline bg-brand-ivory p-8">
                <h3 class="text-2xl leading-tight">Am I eligible?</h3>
                <ul class="mt-5 space-y-3">
                    @foreach ([
                        'You do not pay for NHS prescriptions — treatment is supplied free of charge',
                        'You are registered with a GP in the local area',
                        'Your symptoms are suitable for pharmacist treatment, which we check first',
                        'Bring your NHS number if you have it, though we can usually look it up',
                    ] as $line)
                        <li class="flex gap-3 text-[15px] leading-relaxed text-brand-stone">
                            <span class="mt-[9px] h-1.5 w-1.5 shrink-0 rounded-full bg-brand-moss"></span>{{ $line }}
                        </li>
                    @endforeach
                </ul>
                <p class="mt-6 border-t border-brand-hairline pt-5 text-sm leading-relaxed text-brand-stone-light">
                    If the scheme is not right for you there is usually another route — our pharmacist
                    will tell you which, rather than sell you something that will not help.
                </p>
                <a href="#enquire" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss hover:underline">
                    Check if I am eligible
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- 4. Everything both schemes cover, A–Z --}}
    <section id="a-z" class="scroll-mt-24 border-b border-brand-hairline bg-brand-ivory py-24 md:py-28">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Pharmacy First &amp; minor ailments</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Everything we treat, <span class="editorial-highlight">A to Z.</span></h2>
                <p class="mt-4 text-base text-brand-stone">Every condition has its own one-minute symptom check. Search above, or browse below.</p>
            </div>

            <div class="mt-12 grid gap-x-10 gap-y-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($az as $letter => $items)
                    <div data-az-group>
                        <p class="mb-3 font-serif text-2xl text-brand-moss">{{ $letter }}</p>
                        <ul class="space-y-2">
                            @foreach ($items as $it)
                                <li data-condition="{{ $it['search'] }}">
                                    <a href="{{ route('conditions.show', $it['slug']) }}" class="group flex items-center justify-between gap-3 border-b border-brand-hairline pb-2.5 text-[15px] text-brand-forest transition hover:text-brand-moss">
                                        {{ $it['name'] }}
                                        <span class="text-[10px] uppercase tracking-wide text-brand-stone-light group-hover:text-brand-moss">{{ $it['tag'] === 'Pharmacy First' ? 'NHS' : ($it['tag'] === 'Minor Ailments' ? 'Free NHS' : 'Advice') }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <p id="condition-empty" hidden class="mt-10 rounded-2xl bg-white p-6 text-sm text-brand-stone">No matches — try a different word, or call us on {{ config('pharmacy.phone') }} and we’ll point you the right way.</p>

            <p class="reveal mt-10 font-serif text-sm italic text-brand-stone-light">
                Bring your NHS number — treatment is free if you don’t pay for prescriptions.
            </p>
        </div>
    </section>

    <x-service-form slug="pharmacy-first" :form="config('services.forms.pharmacy-first')" />

    <x-final-cta :href="route('branches')" action="Find your nearest branch"
        sub="Walk in to any of our six branches — a pharmacist is always available, no appointment needed.">
        Free NHS treatment, <span class="editorial-highlight">no GP appointment.</span>
    </x-final-cta>
</x-layout>
