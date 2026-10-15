@php
    // The catalogue plus the services that live on their own bespoke pages, so the
    // hub shows the complete set rather than only the config-driven ones.
    $all = collect(config('services.services'))
        ->map(fn ($s, $slug) => [
            'name' => $s['name'],
            'summary' => $s['summary'],
            'group' => $s['group'],
            'href' => route('services.show', $slug),
        ])
        ->values()
        ->merge(collect(config('services.external'))->map(fn ($s) => [
            'name' => $s['name'],
            'summary' => $s['summary'],
            'group' => $s['group'],
            'href' => route($s['route']).(isset($s['fragment']) ? '#'.$s['fragment'] : ''),
        ]))
        ->groupBy('group');
@endphp

<x-layout title="All services" description="Every NHS and private service across Hollytree Pharmacy — Pharmacy First, vaccinations, contraception, blood pressure, weight loss and more." hero>
    <x-page-hero image="team.jpg" alt="The pharmacy team at the counter" size="short">
        <div class="max-w-3xl">
            <x-eyebrow class="reveal !text-brand-moss-soft">Everything we offer</x-eyebrow>
            <h1 class="mt-4 text-4xl leading-[1.06] text-white md:text-[62px]" data-lines>
                <span class="line-mask"><span>All our services,</span></span>
                <span class="line-mask"><span class="editorial-highlight">in one place.</span></span>
            </h1>
            <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:280ms">
                NHS services free at the point of use, and private services booked with our pharmacist —
                across all six branches.
            </p>
        </div>
    </x-page-hero>

    @foreach ([['nhs', 'NHS services', 'Free at the point of use to eligible patients.'], ['private', 'Private services', 'Booked with our pharmacist, priced up front.']] as [$group, $heading, $lead])
        <section id="{{ $group }}" @class([
            'scroll-mt-24 border-b py-24 md:py-28',
            'border-brand-hairline bg-brand-ivory' => $group === 'nhs',
            'border-brand-hairline bg-white' => $group !== 'nhs',
        ])>
            <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
                <div class="reveal max-w-2xl">
                    <x-eyebrow>{{ $group === 'nhs' ? 'On the NHS' : 'Private clinic' }}</x-eyebrow>
                    <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">{{ $heading }}</h2>
                    <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $lead }}</p>
                </div>

                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                    @foreach ($all[$group] ?? [] as $s)
                        <a href="{{ $s['href'] }}"
                            class="reveal group flex flex-col rounded-[20px] border border-brand-hairline bg-white p-7 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss {{ $group === 'nhs' ? '' : 'bg-brand-ivory' }}">
                            <h3 class="text-[22px] leading-tight">{{ $s['name'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $s['summary'] }}</p>
                            <span class="mt-auto inline-flex items-center gap-2 pt-6 text-sm font-semibold text-brand-moss">
                                Learn more
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    <x-final-cta :href="route('contact')" action="Talk to a pharmacist"
        sub="Not sure which service you need? Our team will point you to the right one — call or send us a message.">
        Not sure where <span class="editorial-highlight">to start?</span>
    </x-final-cta>
</x-layout>
