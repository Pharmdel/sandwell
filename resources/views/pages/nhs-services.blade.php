<x-layout title="NHS services" hero description="Free NHS care from your local pharmacy — Pharmacy First, vaccinations, blood pressure checks and contraception across six Sandwell branches.">
    <x-page-hero image="flu-vaccine.jpg" alt="A pharmacist preparing a vaccination">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-orange">NHS services · Free at the point of care</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[66px]" data-lines>
                <span class="line-mask"><span>Free NHS care,</span></span>
                <span class="line-mask"><span class="editorial-highlight">from your local pharmacy.</span></span>
            </h1>

            <p class="reveal mt-7 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                No GP appointment needed. Walk into any of our six branches or book online for same-day
                pharmacist-led treatment, vaccinations and health checks.
            </p>

            <div class="reveal mt-10 flex flex-wrap gap-3" style="--reveal-delay:440ms">
                <x-btn :href="route('book')" data-magnetic>Book an appointment</x-btn>
                <x-btn :href="route('repeat-prescriptions')" variant="light" data-magnetic>Order repeat prescription</x-btn>
            </div>

            <div class="no-interact reveal mt-12 border-t border-white/15 pt-7" style="--reveal-delay:540ms">
                <x-chip tone="nhs">NHS England contracted</x-chip>
            </div>
        </div>
    </x-page-hero>

    <section class="border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Most used</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">The services people <span class="editorial-highlight">come to us for.</span></h2>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3" data-reveal-group>
                <a href="{{ route('pharmacy-first') }}"
                    class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover lg:row-span-2">
                    <img src="{{ asset('images/mother-toddler.jpg') }}" alt="" class="h-56 w-full rounded-2xl object-cover">
                    <div class="mt-6 flex flex-1 flex-col">
                        <x-chip tone="nhs" class="self-start">NHS · Free</x-chip>
                        <h3 class="mt-3 text-[26px] leading-tight">Pharmacy First</h3>
                        <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                            Same-day treatment for sore throat, earache, sinusitis, UTI, infected insect bites,
                            shingles and impetigo — no appointment needed.
                        </p>
                        <span class="mt-auto inline-flex items-center gap-2 pt-6 text-sm font-semibold text-brand-orange">
                            Learn more
                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                        </span>
                    </div>
                </a>

                @foreach ([
                    ['href' => route('book'), 'chip' => 'NHS or private', 'title' => 'Flu & Covid-19 jabs', 'body' => 'Walk in or book online. NHS jabs for eligible groups, private jabs for everyone else.', 'img' => 'flu-vaccine.jpg', 'wide' => true],
                    ['href' => route('book'), 'chip' => 'NHS · Free', 'title' => 'Blood pressure checks', 'body' => 'A free check in branch, plus 24-hour home monitoring if you need it.', 'img' => null, 'wide' => false],
                    ['href' => route('book'), 'chip' => 'NHS · Free', 'title' => 'Contraception', 'body' => 'Oral contraception supplied and reviewed by our pharmacists, free on the NHS.', 'img' => null, 'wide' => false],
                ] as $card)
                    <a href="{{ $card['href'] }}"
                        class="reveal tilt group flex flex-col gap-5 rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover {{ $card['wide'] ? 'lg:col-span-2 sm:flex-row sm:items-center' : '' }}">
                        <div class="flex-1">
                            <x-chip tone="nhs">{{ $card['chip'] }}</x-chip>
                            <h3 class="mt-3 text-[26px] leading-tight">{{ $card['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $card['body'] }}</p>
                            <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-orange">
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
        </div>
    </section>

    <section class="no-interact border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Also on the NHS</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">More than you'd <span class="editorial-highlight">expect.</span></h2>
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
                    ->where('group', 'nhs');
            @endphp
            <div class="mt-12 grid gap-x-12 sm:grid-cols-2" data-reveal-group>
                @foreach ($listed as $service)
                    <a href="{{ $service['href'] }}"
                        class="reveal group -mx-3 flex items-center justify-between gap-4 rounded-xl border-b border-brand-hairline px-3 py-4 transition-colors duration-200 hover:bg-brand-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-brand-orange"></span>
                            <span class="min-w-0">
                                <span class="block text-[15px] text-brand-navy transition-colors duration-200 group-hover:text-brand-orange">{{ $service['name'] }}</span>
                                <span class="mt-0.5 block text-xs leading-snug text-brand-stone-light">{{ $service['summary'] }}</span>
                            </span>
                        </span>
                        <svg class="h-4 w-4 shrink-0 -translate-x-1 text-brand-orange opacity-0 transition-all duration-200 group-hover:translate-x-0 group-hover:opacity-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('services') }}" class="reveal mt-8 inline-flex items-center gap-2 text-sm font-semibold text-brand-orange hover:underline">
                See every service in one place
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
            </a>
        </div>
    </section>

    <section class="no-interact border-b border-brand-peach-line bg-brand-peach py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 text-center sm:px-8">
            <div class="reveal">
                <x-eyebrow>How Pharmacy First works</x-eyebrow>
                <h2 class="mx-auto mt-3 max-w-2xl text-4xl leading-tight md:text-[42px]">See a pharmacist, <span class="editorial-highlight">not a waiting room.</span></h2>
            </div>
            <x-steps :items="[
                ['title' => 'Walk in or book', 'body' => 'Drop into any branch or reserve a slot online in under two minutes.'],
                ['title' => 'Private consultation', 'body' => 'A pharmacist assesses you properly in a private consultation room.'],
                ['title' => 'Leave with treatment', 'body' => 'Including prescription-only medicines where clinically appropriate.'],
            ]" />
        </div>
    </section>

    <x-final-cta :href="route('repeat-prescriptions').'#nominate'" action="Nominate us as your pharmacy"
        sub="It takes less than a minute, and every NHS service on this page comes with it.">
        Make us your NHS pharmacy <span class="editorial-highlight">in under a minute.</span>
    </x-final-cta>
</x-layout>
