@php
    $mapQuery = urlencode($branch['name'].', '.$branch['address'].', '.$branch['postcode']);
@endphp

<x-layout :title="$branch['name']" hero :description="$branch['name'].' — an NHS community pharmacy in '.$branch['town'].'. Pharmacy First, repeat prescriptions, vaccinations and free delivery.'">
    <x-page-hero image="hero-consult.jpg" :alt="'Inside '.$branch['name']">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-orange">
                Our branches · {{ $branch['town'] }}
            </p>

            <h1 class="mt-5 text-5xl leading-[1.02] text-white md:text-[72px]" data-lines>
                <span class="line-mask"><span>{{ str($branch['name'])->before(' Pharmacy') }}</span></span>
                <span class="line-mask"><span class="editorial-highlight">Pharmacy.</span></span>
            </h1>

            <address class="no-interact reveal mt-7 text-lg not-italic leading-relaxed text-white/80" style="--reveal-delay:340ms">
                {{ $branch['address'] }}<br>{{ $branch['postcode'] }}
            </address>

            <p class="no-interact reveal mt-4 flex items-center gap-2 text-sm font-medium text-white" style="--reveal-delay:390ms">
                <span class="h-2 w-2 animate-pulsing-dot rounded-full bg-emerald-400"></span>
                Open now · until {{ $branch['closes'] }}
            </p>

            <div class="reveal mt-10 flex flex-wrap gap-3" style="--reveal-delay:440ms">
                <x-btn :href="route('book')" data-magnetic>Book an appointment here</x-btn>
                <x-btn href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" variant="light"
                    target="_blank" rel="noopener" data-magnetic>Get directions</x-btn>
            </div>

            <div class="no-interact reveal mt-12 flex flex-wrap gap-x-6 gap-y-2 border-t border-white/15 pt-7 text-[11px] font-semibold uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:540ms">
                <span>NHS community pharmacy</span>
                @if ($branch['hub'])<span>Dispensing hub</span>@endif
                <span>Step-free access</span>
            </div>
        </div>
    </x-page-hero>

    {{-- At a glance --}}
    <section class="no-interact bg-brand-ivory pb-24 md:pb-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal grid divide-y divide-brand-hairline rounded-[20px] border border-brand-hairline bg-white shadow-editorial-card sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x">
                <div class="p-7">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Opening hours</p>
                    <div class="mt-4 space-y-2">
                        @foreach (config('sandwell.hours') as $row)
                            <div class="flex justify-between gap-3 text-sm">
                                <span class="text-brand-navy">{{ $row['day'] }}</span>
                                <span class="text-brand-stone">{{ $row['time'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-7">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Contact</p>
                    <p class="mt-4 font-serif text-2xl text-brand-navy">{{ config('sandwell.phone') }}</p>
                    <p class="mt-2 text-sm text-brand-stone">Ask for the {{ $branch['short'] }} branch.</p>
                </div>
                <div class="p-7">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Access</p>
                    <p class="mt-4 text-sm leading-relaxed text-brand-stone">{{ $branch['access'] }}</p>
                </div>
                <div class="p-7">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Parking</p>
                    <p class="mt-4 text-sm leading-relaxed text-brand-stone">{{ $branch['parking'] }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Services here --}}
    <section class="border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] gap-14 px-6 sm:px-8 lg:grid-cols-12">
            <div class="no-interact reveal lg:col-span-7">
                <x-eyebrow>Available here</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">
                    What {{ $branch['short'] }} <span class="editorial-highlight">offers.</span>
                </h2>
                <div class="mt-10 grid gap-x-10 sm:grid-cols-2">
                    @foreach ([
                        ['Pharmacy First', 'nhs'], ['Repeat prescriptions & free delivery', 'nhs'],
                        ['Flu & Covid-19 vaccinations', 'nhs'], ['Blood pressure checks', 'nhs'],
                        ['Contraception', 'nhs'], ['Weight loss clinic', 'private'],
                        ['Travel vaccinations', 'private'], ['Vitamin B12 injections', 'private'],
                        ['MDS blister packs', 'nhs'], ['Acne treatment', 'private'],
                    ] as [$service, $kind])
                        <div class="flex items-center justify-between gap-3 border-b border-brand-hairline py-3.5">
                            <span class="text-[15px] text-brand-navy">{{ $service }}</span>
                            <x-chip :tone="$kind === 'nhs' ? 'nhs' : 'quiet'">{{ $kind === 'nhs' ? 'NHS' : 'Private' }}</x-chip>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="reveal lg:col-span-5">
                <div class="rounded-[20px] border border-brand-hairline bg-brand-ivory p-8">
                    <h3 class="text-2xl leading-tight">Repeat prescriptions, <span class="editorial-highlight">handled here.</span></h3>
                    <p class="mt-4 text-sm leading-relaxed text-brand-stone">
                        Nominate {{ $branch['name'] }} and your GP sends every repeat straight to this branch.
                        We dispense it and deliver free to your door.
                    </p>
                    <x-btn :href="route('repeat-prescriptions')" variant="outline" class="group mt-6 w-full">Order a repeat prescription</x-btn>
                </div>
            </div>
        </div>
    </section>

    {{-- Team --}}
    <section class="no-interact border-b border-brand-peach-line bg-brand-peach py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] items-center gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <div class="reveal clip-reveal overflow-hidden rounded-[28px] shadow-editorial-card">
                <img src="{{ asset('images/team.jpg') }}" alt="The pharmacy team behind the counter" class="h-80 w-full object-cover">
            </div>
            <div class="reveal">
                <x-eyebrow>Meet the team</x-eyebrow>
                <h2 class="mt-3 text-[32px] leading-tight md:text-[42px]">Familiar faces, <span class="editorial-highlight">every visit.</span></h2>
                <p class="mt-6 text-base leading-relaxed text-brand-stone">
                    The same pharmacists and dispensers work at {{ $branch['short'] }} week in, week out — so
                    you are not explaining your history from scratch every time you come in.
                </p>
                <p class="mt-6 text-sm text-brand-stone">Superintendent pharmacist: <span class="font-semibold text-brand-navy">{{ $branch['superintendent'] }}</span> <span class="text-brand-stone-light">(GPhC {{ $branch['superintendent_gphc'] }})</span></p>
                <p class="mt-2 text-xs text-brand-stone-light">GPhC registered premises {{ $branch['gphc_premises'] }} · Operated by {{ $branch['company'] }}, company no. {{ $branch['company_no'] }}</p>
            </div>
        </div>
    </section>

    {{-- Map & directions --}}
    <section class="no-interact bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] items-center gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <div class="reveal clip-reveal overflow-hidden rounded-[28px] border border-brand-hairline shadow-editorial-card">
                <img src="{{ asset('images/map.jpg') }}" alt="Map showing the location of {{ $branch['name'] }}" class="h-80 w-full object-cover">
            </div>
            <div class="reveal">
                <h2 class="text-[32px] leading-tight md:text-[42px]">Getting <span class="editorial-highlight">here.</span></h2>
                <div class="mt-8">
                    @foreach ([
                        ['By bus', 'Several routes stop within a few minutes’ walk of '.$branch['area'].'.'],
                        ['By car', $branch['parking'].'.'],
                        ['On foot', 'Step-free from the street, with a consultation room on the ground floor.'],
                    ] as $row)
                        <div class="border-b border-brand-hairline py-4 last:border-0">
                            <p class="text-sm font-semibold text-brand-navy">{{ $row[0] }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-brand-stone">{{ $row[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Nominate --}}
    <section class="border-t border-brand-hairline bg-white py-20">
        <div class="reveal mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="flex flex-col items-center justify-between gap-6 rounded-[20px] border border-brand-hairline bg-brand-ivory p-10 text-center md:flex-row md:text-left">
                <div>
                    <h2 class="text-[28px] leading-tight">Make {{ $branch['short'] }} <span class="editorial-highlight">your pharmacy.</span></h2>
                    <p class="mt-3 text-sm text-brand-stone">One nomination and every repeat comes here automatically.</p>
                </div>
                <x-btn :href="route('repeat-prescriptions').'#nominate'" class="shrink-0">Nominate this branch</x-btn>
            </div>
        </div>
    </section>

    <x-final-cta :href="route('repeat-prescriptions').'#nominate'" :action="'Nominate '.$branch['name']"
        sub="It takes less than a minute, and you can change it back at any time.">
        Nominate {{ $branch['short'] }} <span class="editorial-highlight">in under a minute.</span>
    </x-final-cta>
</x-layout>
