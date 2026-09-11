@php
    $branches = config('sandwell.branches');
    $reviews = config('sandwell.reviews');
    $answers = array_column(array_merge(...array_column(config('sandwell.faqs'), 'items')), 1, 0);
    $homeFaqs = array_map(fn ($q) => [$q, $answers[$q]], config('sandwell.faqs_home'));
    $faqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(
        fn ($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]],
        $homeFaqs,
    )];
@endphp

<x-layout hero>
    @push('scripts')
        <script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
        @vite('resources/js/branch-map.js')
    @endpush

    <x-page-hero image="hero-consult.jpg" alt="A pharmacist talking with a patient at the counter">
        <div class="max-w-3xl">
            <h1 class="text-5xl leading-[1.02] text-white md:text-[82px]" data-lines>
                <span class="line-mask"><span>Your local pharmacy,</span></span>
                <span class="line-mask"><span class="editorial-highlight">without the wait.</span></span>
            </h1>

            <p class="reveal mt-8 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Six neighbourhood pharmacies across Sandwell and Stourbridge. NHS and private care,
                same-day pharmacist consultations, and free prescription delivery to your door.
            </p>

            <div class="reveal mt-10 flex flex-wrap gap-3" style="--reveal-delay:440ms">
                <x-btn :href="route('book')" data-magnetic>Book an appointment</x-btn>
                <x-btn :href="route('repeat-prescriptions')" variant="light" data-magnetic>Order repeat prescription</x-btn>
            </div>

            <div class="no-interact reveal mt-12 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/15 pt-7" style="--reveal-delay:540ms">
                <span class="font-serif text-3xl text-white" data-count="{{ $reviews['rating'] }}">{{ $reviews['rating'] }}</span>
                <span class="flex gap-0.5 text-brand-orange" aria-hidden="true">
                    @for ($i = 0; $i < 5; $i++)<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L1.5 7.7l5.9-.9z"/></svg>@endfor
                </span>
                <span class="text-sm text-white/70"><span data-count="{{ $reviews['count'] }}">{{ $reviews['count'] }}</span> Google reviews</span>
                <span class="text-sm text-white/70">Free delivery over {{ config('sandwell.free_delivery_threshold') }}</span>
            </div>
        </div>

        <x-slot:foot>
            {{-- Rolling service rail: each card links through to the service and names
                 which scheme it sits under. The set is rendered twice so the loop can reset
                 invisibly at the halfway point; the copies are hidden from assistive tech. --}}
            <div class="relative">
                <div data-carousel class="no-scrollbar flex gap-5 overflow-x-auto pb-1">
                    @foreach ([0, 1] as $pass)
                        @foreach ([
                            ['hero-consult.jpg', 'Pharmacy First', 'Same-day NHS treatment for seven common conditions', 'NHS service', 'nhs', route('pharmacy-first')],
                            ['delivery-door.jpg', 'Repeat prescriptions', 'Nominate us and we deliver free to your door', 'NHS service', 'nhs', route('repeat-prescriptions')],
                            ['weightloss-outdoor.jpg', 'Weight loss clinic', 'Wegovy and Mounjaro with weekly pharmacist check-ins', 'Private service', 'private', route('weight-loss')],
                            ['flu-vaccine.jpg', 'Flu &amp; Covid-19 jabs', 'Walk in or book — NHS and private', 'NHS &amp; private', 'nhs', route('services.show', 'flu-covid-vaccinations')],
                            ['consult-room.jpg', 'Blood pressure checks', 'Free NHS check, with 24-hour monitoring if needed', 'NHS service', 'nhs', route('services.show', 'hypertension')],
                            ['products.jpg', 'Online shop', 'Pharmacy essentials, free delivery over £15', 'Shop', 'shop', route('shop')],
                        ] as [$img, $service, $line, $type, $tone, $href])
                            @php
                                $toneClass = ['nhs' => 'text-[#8cc2ef]', 'private' => 'text-brand-orange', 'shop' => 'text-white/55'][$tone];
                            @endphp
                            <a href="{{ $href }}" data-carousel-item @if ($pass) aria-hidden="true" tabindex="-1" @endif
                                class="group relative aspect-[4/5] w-[78vw] shrink-0 overflow-hidden rounded-[28px] shadow-editorial-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-inset sm:w-[400px] lg:w-[413px]">
                                <img src="{{ asset('images/hero/'.str($img)->beforeLast('.').'-1600.jpg') }}"
                                    srcset="{{ asset('images/hero/'.str($img)->beforeLast('.').'-1600.jpg') }} 1600w, {{ asset('images/hero/'.str($img)->beforeLast('.').'-2600.jpg') }} 2600w"
                                    sizes="(min-width: 640px) 413px, 78vw" alt=""
                                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.05]" @if ($pass) loading="lazy" @endif>
                                <span class="absolute inset-x-0 bottom-0 flex h-1/2 flex-col justify-center bg-brand-navy px-8">
                                    <span class="text-[11px] font-bold uppercase tracking-[0.14em] {{ $toneClass }}">{!! $type !!}</span>
                                    <span class="mt-2 font-serif text-[28px] leading-tight text-white">{!! $service !!}</span>
                                    <span class="mt-2 text-sm leading-relaxed text-white/70">{{ $line }}</span>
                                    <span class="mt-3 inline-flex items-center gap-1.5 text-[13px] font-semibold text-white/50 transition-colors duration-300 group-hover:text-white group-focus-visible:text-white">
                                        View service
                                        <svg class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    @endforeach
                </div>

                @foreach ([['prev', 'Previous services', 'left-3 sm:left-5', 'M15 19l-7-7 7-7'], ['next', 'More services', 'right-3 sm:right-5', 'M9 5l7 7-7 7']] as [$dir, $label, $pos, $path])
                    <button type="button" data-rail-{{ $dir }} aria-label="{{ $label }}"
                        class="absolute top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/25 bg-brand-navy-deep/70 text-white backdrop-blur transition-all duration-200 hover:scale-105 hover:bg-brand-orange hover:border-brand-orange active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange {{ $pos }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" /></svg>
                    </button>
                @endforeach
            </div>
        </x-slot:foot>
    </x-page-hero>

    {{-- Service strip — an infinite marquee, doubled so the loop is seamless --}}
    <section class="marquee no-interact overflow-hidden border-y border-brand-hairline bg-white/60 py-4">
        <div class="marquee-track">
            @for ($pass = 0; $pass < 2; $pass++)
                <div class="flex shrink-0 items-center gap-6 pr-6 text-[11px] font-semibold uppercase tracking-[0.12em] text-brand-stone" @if($pass) aria-hidden="true" @endif>
                    @foreach (['Pharmacy First', 'Repeat prescriptions', 'Flu & Covid jabs', 'Weight loss clinic', 'Blood pressure checks', 'Travel vaccinations', 'Contraception', 'Free delivery over £15'] as $label)
                        <span class="h-1 w-1 shrink-0 rounded-full bg-brand-orange"></span>
                        <span class="whitespace-nowrap">{{ $label }}</span>
                    @endforeach
                </div>
            @endfor
        </div>
    </section>

    {{-- Services bento --}}
    <section class="bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>What we do</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">
                    Everything your pharmacy<br><span class="editorial-highlight">can do for you.</span>
                </h2>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3" data-reveal-group>
                <a href="{{ route('pharmacy-first') }}"
                    class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover lg:row-span-2">
                    <img src="{{ asset('images/consult-room.jpg') }}" alt="" class="h-56 w-full rounded-2xl object-cover">
                    <div class="mt-6 flex flex-1 flex-col">
                        <x-chip tone="nhs" class="self-start">NHS</x-chip>
                        <h3 class="mt-3 text-[26px] leading-tight">Pharmacy First</h3>
                        <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                            See a pharmacist, not a waiting room. Same-day NHS treatment for seven common
                            conditions without needing a GP appointment.
                        </p>
                        <span class="mt-auto inline-flex items-center gap-2 pt-6 text-sm font-semibold text-brand-orange">
                            Learn more
                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                        </span>
                    </div>
                </a>

                @foreach ([
                    ['href' => route('weight-loss'), 'chip' => 'Private clinic', 'tone' => 'peach', 'title' => 'Weight loss clinic', 'body' => 'Wegovy & Mounjaro with weekly pharmacist support and clinical monitoring.', 'img' => 'weightloss-outdoor.jpg', 'cta' => 'Learn more'],
                    ['href' => route('shop'), 'chip' => 'Home delivery', 'tone' => 'quiet', 'title' => 'Online shop', 'body' => 'Essentials delivered, free over '.config('sandwell.free_delivery_threshold').'. Vitamins, remedies and everyday healthcare.', 'img' => 'parcel-doorstep.jpg', 'cta' => 'Shop essentials'],
                ] as $card)
                    <a href="{{ $card['href'] }}"
                        class="reveal tilt group flex flex-col gap-6 rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover sm:flex-row sm:items-center lg:col-span-2">
                        <div class="flex-1">
                            <x-chip :tone="$card['tone']">{{ $card['chip'] }}</x-chip>
                            <h3 class="mt-3 text-[26px] leading-tight">{{ $card['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $card['body'] }}</p>
                            <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-orange">
                                {{ $card['cta'] }}
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </span>
                        </div>
                        <img src="{{ asset('images/'.$card['img']) }}" alt="" class="h-40 w-full rounded-2xl object-cover sm:w-52">
                    </a>
                @endforeach
            </div>

            {{-- Everything else, each row through to where that service lives --}}
            <div class="reveal mt-6 rounded-[20px] border border-brand-hairline bg-white p-8 shadow-editorial-card">
                <div class="grid gap-x-12 sm:grid-cols-2">
                    @foreach ([
                        ['Blood pressure checks', 'Free NHS service', route('services.show', 'hypertension')],
                        ['Contraception consultations', 'Confidential & free', route('services.show', 'contraception')],
                        ['Acne treatment', 'Private clinic', route('services.show', 'acne')],
                        ['Travel vaccinations', 'Walk-ins welcome', route('services.show', 'travel-vaccinations')],
                        ['Vitamin B12 injections', 'Clinical wellness', route('services.show', 'b12-injections')],
                        ['Hair loss treatments', 'Private clinic', route('services.show', 'hair-loss')],
                        ['Period delay service', 'Discreet care', route('services.show', 'period-delay')],
                        ['MDS blister packs', 'Free weekly packing', route('services.show', 'mds-trays')],
                        ['Chickenpox vaccination', 'Paediatric care', route('services.show', 'chickenpox-vaccination')],
                        ['Free treatment for 31 conditions', 'NHS Pharmacy First', route('pharmacy-first')],
                    ] as [$name, $note, $href])
                        {{-- The negative margin lets the hover tint breathe past the text without
                             moving the rows, and the second-to-last row loses its rule only once
                             the grid is actually two columns. --}}
                        <a href="{{ $href }}"
                            class="group -mx-3 flex items-center justify-between gap-4 rounded-xl border-b border-brand-hairline px-3 py-3.5 transition-colors duration-200 last:border-0 hover:bg-brand-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange sm:[&:nth-last-child(2)]:border-0">
                            <span class="text-sm text-brand-navy transition-colors duration-200 group-hover:text-brand-orange">{{ $name }}</span>
                            <span class="flex shrink-0 items-center gap-1.5 text-right text-xs text-brand-stone-light">
                                {{ $note }}
                                <svg class="h-3.5 w-3.5 shrink-0 -translate-x-1 text-brand-orange opacity-0 transition-all duration-200 group-hover:translate-x-0 group-hover:opacity-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
                <p class="mt-6 text-center text-sm text-brand-stone-light">
                    <a href="{{ route('services') }}" class="font-serif italic underline decoration-brand-hairline underline-offset-4 transition hover:text-brand-navy hover:decoration-brand-orange">See all 19 services →</a>
                </p>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="no-interact border-y border-brand-peach-line bg-brand-peach py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 text-center sm:px-8">
            <div class="reveal">
                <x-eyebrow>Simple by design</x-eyebrow>
                <h2 class="mx-auto mt-3 max-w-xl text-4xl leading-tight md:text-[42px]">
                    Getting your medicines,<br><span class="editorial-highlight">sorted.</span>
                </h2>
            </div>

            <x-steps :items="[
                ['title' => 'Nominate us', 'body' => 'Choose any Sandwell Pharmacy branch in the NHS App, or let our team set it up for you.'],
                ['title' => 'We prepare it', 'body' => 'Our dispensing teams safety-check, assemble and pack your medication with clinical precision.'],
                ['title' => 'Delivered to your door', 'body' => 'Your medicines arrive via our own tracked courier service, completely free of charge.'],
            ]" />
        </div>
    </section>

    {{-- Branches: hovering a branch lights up its pin on the map, and vice versa --}}
    <section class="bg-brand-ivory py-24 md:py-32" data-branch-map>
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Where to find us</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">
                    Six branches. <span class="editorial-highlight">One standard of care.</span>
                </h2>
                <p class="mt-4 text-base leading-relaxed text-brand-stone">
                    Hover a branch to find it on the map, or open it for opening hours, services and directions.
                </p>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-12">
                <div class="grid gap-3 sm:grid-cols-2 lg:col-span-7" data-reveal-group>
                    @foreach ($branches as $branch)
                        <a href="{{ route('branches.show', $branch['slug']) }}" data-branch="{{ $branch['slug'] }}" data-town="{{ $branch['town'] }}" data-lat="{{ $branch['lat'] }}" data-lon="{{ $branch['lon'] }}"
                            class="reveal group flex flex-col rounded-2xl border bg-white p-5 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:border-brand-orange hover:shadow-editorial-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange {{ $branch['hub'] ? 'border-brand-orange/40' : 'border-brand-hairline' }}">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-lg leading-snug">{{ $branch['name'] }}</h3>
                                @if ($branch['hub'])<x-chip tone="peach">Hub</x-chip>@endif
                            </div>
                            <p class="mt-1 text-xs leading-relaxed text-brand-stone">{{ $branch['address'] }}</p>
                            <p class="text-xs text-brand-stone-light">{{ $branch['postcode'] }}</p>
                            <div class="mt-auto flex items-center justify-between gap-2 pt-3">
                                <p class="flex items-center gap-1.5 text-xs font-medium text-brand-orange">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Open until {{ $branch['closes'] }}
                                </p>
                                <span class="text-brand-stone-light transition-transform duration-200 group-hover:translate-x-1 group-hover:text-brand-orange">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="reveal lg:col-span-5">
                    <figure class="lg:sticky lg:top-28">
                        <div class="relative overflow-hidden rounded-[28px] border border-brand-hairline shadow-editorial-card">
                            <div id="branch-map" class="h-[420px] w-full bg-brand-hairline lg:h-[560px]">
                                {{-- Replaced by the interactive map once Leaflet loads --}}
                                <img data-map-fallback src="{{ asset('images/branches-map.jpg') }}" width="970" height="1303" loading="lazy"
                                    alt="Map of the West Midlands showing all six Sandwell Pharmacy Group branches"
                                    class="h-full w-full object-cover">
                            </div>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="no-interact text-[11px] text-brand-stone-light">Scroll to zoom after clicking the map · pins are the branch postcodes</p>
                            <button type="button" data-map-reset
                                class="rounded-full border border-brand-hairline px-3 py-1.5 text-[11px] font-semibold text-brand-navy transition hover:border-brand-navy hover:bg-brand-navy hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange">
                                Reset map
                            </button>
                        </div>
                    </figure>
                </div>
            </div>

            <div class="reveal mt-12 text-center">
                <x-btn :href="route('branches')" variant="outline" class="group">See all six branches</x-btn>
            </div>
        </div>
    </section>

    {{-- Reviews: the group's nine published Google reviews, verbatim. One is featured
         at a time; the list beside it selects, and links out for the other 575. --}}
    <section class="border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <x-eyebrow>Trusted locally</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[46px]">
                    Loved by our neighbours,<br><span class="editorial-highlight">rated {{ $reviews['rating'] }} on Google.</span>
                </h2>
                <p class="no-interact mt-5 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-base text-brand-stone">
                    <span class="flex gap-0.5 text-brand-orange" aria-hidden="true">
                        @for ($i = 0; $i < 5; $i++)<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L1.5 7.7l5.9-.9z"/></svg>@endfor
                    </span>
                    <span><span class="font-semibold text-brand-navy">{{ $reviews['count'] }} Google reviews</span> across our six pharmacies</span>
                </p>
            </div>

            <div class="reveal mt-14 grid gap-10 lg:grid-cols-[minmax(0,1.5fr)_minmax(0,0.9fr)] lg:items-center lg:gap-14" data-testimonials>
                {{-- Featured review, sitting on a stack of decorative cards --}}
                <div class="relative">
                    <div aria-hidden="true" class="absolute inset-0 translate-x-4 translate-y-7 rotate-[1.8deg] rounded-[28px] border border-brand-hairline bg-brand-ivory"></div>
                    <div aria-hidden="true" class="absolute inset-0 -translate-x-3.5 translate-y-3.5 -rotate-[1.3deg] rounded-[28px] border border-brand-peach-line bg-brand-peach/60"></div>

                    {{-- Every review occupies the same grid cell, so the card is always as tall
                         as the longest one and swapping never shifts the page. --}}
                    <div class="relative grid rounded-[28px] border border-brand-hairline bg-white p-8 shadow-editorial-card sm:p-10">
                        @foreach (config('sandwell.reviews_quotes') as $i => $q)
                            <figure id="testimonial-{{ $i }}" data-testimonial="{{ $i }}" role="tabpanel"
                                aria-labelledby="testimonial-tab-{{ $i }}" @if (! $loop->first) aria-hidden="true" @endif
                                class="tm-panel [grid-area:1/1] flex flex-col {{ $loop->first ? 'is-active' : '' }}">
                                <div class="flex items-center gap-3.5">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-navy font-serif text-xl text-white">{{ $q['initial'] }}</span>
                                    <span class="min-w-0">
                                        <span class="block truncate text-[15px] font-semibold text-brand-navy">{{ $q['name'] }}</span>
                                        <span class="flex gap-0.5" aria-label="{{ $q['rating'] }} out of 5 stars">
                                            @for ($star = 1; $star <= 5; $star++)
                                                <svg class="h-3.5 w-3.5 {{ $star <= $q['rating'] ? 'text-brand-orange' : 'text-brand-hairline' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L1.5 7.7l5.9-.9z"/></svg>
                                            @endfor
                                        </span>
                                    </span>
                                </div>
                                <blockquote class="my-auto py-7 font-serif text-[20px] italic leading-relaxed text-brand-navy sm:text-[23px]">“{{ $q['quote'] }}”</blockquote>
                                <figcaption class="flex items-center gap-1.5 text-[11px] uppercase tracking-[0.1em] text-brand-stone-light">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M23 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.2a5.3 5.3 0 01-2.3 3.5v2.9h3.700a11.2 11.2 0 003.4-8.6z"/><path fill="#34A853" d="M12 23.5c3.1 0 5.7-1 7.6-2.8l-3.7-2.9a7 7 0 01-10.4-3.7H1.6v3a11.5 11.5 0 0010.4 6.4z"/><path fill="#FBBC05" d="M5.5 14.1a6.9 6.9 0 010-4.4v-3H1.6a11.5 11.5 0 000 10.4z"/><path fill="#EA4335" d="M12 5.4c1.7 0 3.3.6 4.5 1.8l3.3-3.3A11.5 11.5 0 001.6 6.7l3.9 3a6.9 6.9 0 016.5-4.3z"/></svg>
                                    Posted on Google
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>

                {{-- The nine reviewers, scrollable --}}
                <div>
                    <h3 class="font-serif text-2xl text-brand-navy">Patient testimonies</h3>
                    <p class="mt-1 text-sm text-brand-stone">All nine published on Google, word for word.</p>

                    <div class="tm-scroll mt-5 pr-2" role="tablist" aria-label="Patient testimonies" aria-orientation="vertical">
                        @foreach (config('sandwell.reviews_quotes') as $i => $q)
                            <button type="button" id="testimonial-tab-{{ $i }}" data-testimonial-tab="{{ $i }}"
                                role="tab" aria-controls="testimonial-{{ $i }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}"
                                class="tm-tab group flex w-full items-center gap-3 rounded-2xl border border-transparent px-3 py-2.5 text-left transition-all duration-200 hover:bg-brand-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange {{ $loop->first ? 'is-active' : '' }}">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-navy font-serif text-sm text-white">{{ $q['initial'] }}</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-semibold text-brand-navy">{{ $q['name'] }}</span>
                                    <span class="flex gap-0.5" aria-hidden="true">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <svg class="h-3 w-3 {{ $star <= $q['rating'] ? 'text-brand-orange' : 'text-brand-hairline' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L1.5 7.7l5.9-.9z"/></svg>
                                        @endfor
                                    </span>
                                </span>
                            </button>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="reveal mt-12 flex justify-center">
                <a href="{{ config('sandwell.google_reviews_url') }}" target="_blank" rel="noopener" data-magnetic
                    class="group inline-flex items-center gap-2 rounded-full border border-brand-navy px-7 py-3.5 text-sm font-semibold text-brand-navy transition-all duration-200 hover:bg-brand-navy hover:text-white active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange">
                    Read all {{ $reviews['count'] }} reviews on Google
                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5h5v5M19 5l-8 8M18 13v5a1 1 0 01-1 1H6a1 1 0 01-1-1V7a1 1 0 011-1h5" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- FAQs — five most asked, on the footer's ground so the page closes into it. --}}
    <section id="faqs" class="scroll-mt-24 border-t border-white/10 bg-footer py-16 text-footer-text md:py-20">
        <div class="mx-auto max-w-[720px] px-6 sm:px-8">
            <div class="text-center">
                <x-eyebrow class="!text-footer-link">Frequently asked</x-eyebrow>
                <h2 class="reveal mt-2.5 text-[30px] leading-tight text-white md:text-[36px]">
                    Everything you <span class="editorial-highlight">wanted to ask.</span>
                </h2>
            </div>

            {{-- The list reveals as one block: an item scrolled out of this container is
                 clipped, so a per-item observer would never fire for it. --}}
            <div class="faq-scroll reveal mt-8 pr-3" style="--reveal-delay:140ms">
                <div class="divide-y divide-white/[0.12] border-y border-white/[0.12]">
                    @foreach ($homeFaqs as [$q, $a])
                        <details class="wl-faq group py-0.5">
                            <summary class="flex items-center justify-between gap-5 py-4 text-[15px] font-medium leading-snug text-white transition-colors duration-200 hover:text-footer-link">
                                {{ $q }}
                                <span class="wl-faq__icon flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-white/25 text-footer-link transition-transform duration-300">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg>
                                </span>
                            </summary>
                            <div class="pb-5 pr-0 text-[14px] leading-relaxed text-footer-text sm:pr-10">{{ $a }}</div>
                        </details>
                    @endforeach
                </div>
            </div>

            <p class="reveal mt-7 text-center text-[13px] text-footer-meta">
                Still have a question? Call
                <a href="{{ config('sandwell.phone_href') }}"
                    class="text-footer-link underline decoration-footer-link/40 underline-offset-4 transition hover:text-white hover:decoration-white">{{ config('sandwell.phone') }}</a>
                or email
                <a href="mailto:{{ config('sandwell.email') }}"
                    class="text-footer-link underline decoration-footer-link/40 underline-offset-4 transition hover:text-white hover:decoration-white">{{ config('sandwell.email') }}</a>
            </p>
        </div>
    </section>

</x-layout>
