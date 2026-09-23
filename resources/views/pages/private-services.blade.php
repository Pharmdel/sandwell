<x-layout title="Private services" hero description="Discreet, pharmacist-led private treatments — weight loss, hair loss, vitamin B12 and acne — assessed in branch or online.">
    <x-page-hero image="consult-room.jpg" alt="A patient in a private consultation with a pharmacist">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">Private services · Pharmacist-led</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[66px]" data-lines>
                <span class="line-mask"><span>Private care,</span></span>
                <span class="line-mask"><span class="editorial-highlight">without the waiting list.</span></span>
            </h1>

            <p class="reveal mt-7 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Discreet, clinically-led treatments from our pharmacist prescribers — assessed in branch
                or online, with medication dispensed by our own team.
            </p>

            <div class="reveal mt-10" style="--reveal-delay:440ms">
                <x-btn :href="route('book')" data-magnetic>Book a private consultation</x-btn>
            </div>

            <div class="no-interact reveal mt-12 flex flex-wrap gap-x-6 gap-y-2 border-t border-white/15 pt-7 text-[11px] font-semibold uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:540ms">
                <span>GPhC-registered prescribers</span>
                <span>Same-week appointments</span>
                <span>Discreet packaging</span>
            </div>
        </div>
    </x-page-hero>

    <section class="border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Our clinics</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Treatments we <span class="editorial-highlight">specialise in.</span></h2>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3" data-reveal-group>
                <a href="{{ route('weight-loss') }}"
                    class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover lg:row-span-2">
                    <img src="{{ asset('images/weightloss-outdoor.jpg') }}" alt="" class="h-56 w-full rounded-2xl object-cover">
                    <div class="mt-6 flex flex-1 flex-col">
                        <x-chip tone="peach" class="self-start">Most popular</x-chip>
                        <h3 class="mt-3 text-[26px] leading-tight">Weight loss clinic</h3>
                        <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                            Wegovy &amp; Mounjaro with weekly pharmacist check-ins and clinical monitoring throughout.
                        </p>
                        <p class="mt-4 text-xs text-brand-stone-light">Price confirmed at consultation</p>
                        <span class="mt-auto inline-flex items-center gap-2 pt-6 text-sm font-semibold text-brand-moss">
                            Learn more
                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                        </span>
                    </div>
                </a>

                @foreach ([
                    ['chip' => 'Prescription clinic', 'title' => 'Hair loss', 'body' => 'Finasteride and minoxidil plans, assessed by a prescriber and reviewed regularly.', 'img' => 'consult-room.jpg', 'wide' => true],
                    ['chip' => 'In-clinic injection', 'title' => 'Vitamin B12 injections', 'body' => 'For diagnosed deficiency or wellbeing, administered in branch.', 'img' => null, 'wide' => false],
                    ['chip' => 'Dermatology clinic', 'title' => 'Acne treatment', 'body' => 'Prescription-strength topical and oral options for stubborn skin.', 'img' => null, 'wide' => false],
                ] as $card)
                    <a href="{{ route('book') }}"
                        class="reveal tilt group flex flex-col gap-5 rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover {{ $card['wide'] ? 'lg:col-span-2 sm:flex-row sm:items-center' : '' }}">
                        <div class="flex-1">
                            <x-chip tone="quiet">{{ $card['chip'] }}</x-chip>
                            <h3 class="mt-3 text-[26px] leading-tight">{{ $card['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $card['body'] }}</p>
                            <p class="mt-4 text-xs text-brand-stone-light">Price confirmed at consultation</p>
                            <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss">
                                Learn more
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </span>
                        </div>
                        @if ($card['img'])
                            <img src="{{ asset('images/'.$card['img']) }}" alt="" class="h-40 w-full rounded-2xl object-cover sm:w-52">
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="reveal mt-12 text-center">
                <p class="mb-4 text-sm text-brand-stone">Not sure which weight loss treatment suits you?</p>
                <x-btn :href="route('weight-loss')" variant="outline" class="group">Compare Wegovy &amp; Mounjaro</x-btn>
            </div>
        </div>
    </section>

    <section class="no-interact border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Also available privately</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Quietly, and <span class="editorial-highlight">without fuss.</span></h2>
            </div>
            {{-- Driven by the service catalogue so this list can never fall behind the
                 pages that exist. Every entry is a link through to its own page. --}}
            @php
                $listed = collect(config('services.services'))
                    ->map(fn ($s, $slug) => ['name' => $s['name'], 'summary' => $s['summary'], 'group' => $s['group'], 'href' => route('services.show', $slug)])
                    ->values()
                    ->merge(collect(config('services.external'))->map(fn ($s) => [
                        'name' => $s['name'], 'summary' => $s['summary'], 'group' => $s['group'],
                        'href' => route($s['route']).(isset($s['fragment']) ? '#'.$s['fragment'] : ''),
                    ]))
                    ->where('group', 'private');
            @endphp
            <div class="mt-12 grid gap-x-12 sm:grid-cols-2" data-reveal-group>
                @foreach ($listed as $service)
                    <a href="{{ $service['href'] }}"
                        class="reveal group -mx-3 flex items-center justify-between gap-4 rounded-xl border-b border-brand-hairline px-3 py-4 transition-colors duration-200 hover:bg-brand-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-brand-moss"></span>
                            <span class="min-w-0">
                                <span class="block text-[15px] text-brand-forest transition-colors duration-200 group-hover:text-brand-moss">{{ $service['name'] }}</span>
                                <span class="mt-0.5 block text-xs leading-snug text-brand-stone-light">{{ $service['summary'] }}</span>
                            </span>
                        </span>
                        <svg class="h-4 w-4 shrink-0 -translate-x-1 text-brand-moss opacity-0 transition-all duration-200 group-hover:translate-x-0 group-hover:opacity-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('services') }}" class="reveal mt-8 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss hover:underline">
                See every service in one place
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
            </a>
        </div>
    </section>

    <section class="no-interact border-b border-brand-pistachio-line bg-brand-pistachio py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 text-center sm:px-8">
            <div class="reveal">
                <x-eyebrow>How it works</x-eyebrow>
                <h2 class="mx-auto mt-3 max-w-2xl text-4xl leading-tight md:text-[42px]">Assessed properly, <span class="editorial-highlight">treated quickly.</span></h2>
            </div>
            <x-steps :items="[
                ['title' => 'Book or walk in', 'body' => 'Choose a time that suits you at any of our six branches.'],
                ['title' => 'Pharmacist assessment', 'body' => 'A prescriber reviews your history and confirms the treatment is right for you.'],
                ['title' => 'Treatment dispensed', 'body' => 'Collected in branch or delivered discreetly to your door.'],
            ]" />
        </div>
    </section>

    <section class="no-interact bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <figure class="reveal">
                <blockquote class="font-serif text-3xl italic leading-snug text-brand-forest">
                    “I'd put off sorting my skin for years. One consultation and I left with a proper plan — no GP wait, no lecture.”
                </blockquote>
                <figcaption class="mt-6 flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-forest text-sm font-semibold text-white">A</span>
                    <span>
                        <span class="block text-sm font-semibold text-brand-forest">Aisha R.</span>
                        <span class="block text-xs text-brand-stone-light">Smethwick branch</span>
                    </span>
                </figcaption>
            </figure>

            <div class="reveal rounded-[20px] border border-brand-hairline bg-white p-8 shadow-editorial-card">
                <h3 class="text-[28px] leading-tight">Clinically led. <span class="editorial-highlight">Always.</span></h3>
                <div class="mt-6">
                    @foreach ([
                        'Every treatment is prescribed by a GPhC-registered pharmacist independent prescriber',
                        'Your GP is informed with your consent',
                        'Follow-up is included, not charged as an extra',
                    ] as $row)
                        <p class="border-b border-brand-hairline py-4 text-sm leading-relaxed text-brand-stone last:border-0">{{ $row }}</p>
                    @endforeach
                </div>
                <x-chip tone="quiet" class="mt-6">GPhC-registered prescribers</x-chip>
            </div>
        </div>
    </section>

    <x-final-cta :href="route('book')" action="Book a private consultation"
        sub="Same-week appointments at all six branches, with treatment dispensed by our own dispensary.">
        Private care that <span class="editorial-highlight">feels personal.</span>
    </x-final-cta>
</x-layout>
