<x-layout title="Our branches" hero description="Six NHS community pharmacies across West Bromwich, Smethwick and Stourbridge — same team, same standards.">
    <x-page-hero image="team.jpg" alt="The pharmacy team behind the counter">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-orange">Where to find us</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[66px]" data-lines>
                <span class="line-mask"><span>Six branches.</span></span>
                <span class="line-mask"><span class="editorial-highlight">One standard of care.</span></span>
            </h1>

            <p class="reveal mt-7 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Every branch is a separately registered NHS community pharmacy, run by the same team to
                the same standards. Walk into whichever is closest.
            </p>

            <div class="no-interact reveal mt-12 flex flex-wrap gap-x-6 gap-y-2 border-t border-white/15 pt-7 text-[11px] font-semibold uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:440ms">
                <span>Open Mon–Sat</span>
                <span>Free parking at most branches</span>
                <span>Free delivery</span>
            </div>
        </div>
    </x-page-hero>

    <section class="border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Choose a branch</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Find <span class="editorial-highlight">your</span> pharmacy.</h2>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach (config('sandwell.branches') as $branch)
                    <a href="{{ route('branches.show', $branch['slug']) }}"
                        class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover {{ $branch['hub'] ? 'border-brand-orange/40' : 'border-brand-hairline' }}">
                        <img src="{{ asset('images/hero-consult.jpg') }}" alt="" class="h-40 w-full rounded-2xl object-cover">
                        <div class="mt-5 flex flex-1 flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-[26px] leading-tight">{{ $branch['name'] }}</h3>
                                @if ($branch['hub'])<x-chip tone="peach">Dispensing hub</x-chip>@endif
                            </div>
                            <p class="mt-2 text-sm text-brand-stone">{{ $branch['address'] }}</p>
                            <p class="mt-1 text-sm text-brand-stone-light">{{ $branch['postcode'] }}</p>
                            <p class="mt-3 flex items-center gap-1.5 text-xs font-medium text-brand-orange">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Open until {{ $branch['closes'] }}
                            </p>
                            <div class="mt-4 flex flex-wrap gap-1.5 border-t border-brand-hairline pt-4">
                                @foreach ($branch['services'] as $service)
                                    <x-chip tone="quiet">{{ $service }}</x-chip>
                                @endforeach
                            </div>
                            <span class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-brand-orange">
                                View branch
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="no-interact border-y border-brand-peach-line bg-brand-peach py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>At every branch</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">The same care, <span class="editorial-highlight">wherever you are.</span></h2>
            </div>
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                @foreach ([
                    ['Pharmacy First consultations', 'Same-day NHS treatment for seven conditions.'],
                    ['Repeat prescriptions & delivery', 'Nominate any branch and we handle the rest.'],
                    ['NHS & private vaccinations', 'Flu, Covid-19, travel and childhood jabs.'],
                    ['Health checks', 'Blood pressure, weight and medicine reviews.'],
                ] as $item)
                    <div class="reveal">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white">
                            <svg class="h-5 w-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg leading-snug">{{ $item[0] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $item[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="no-interact bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <div class="reveal">
                <h2 class="text-[28px] leading-tight">Typical opening <span class="editorial-highlight">hours.</span></h2>
                <div class="mt-6">
                    @foreach (config('sandwell.hours') as $row)
                        <div class="flex items-center justify-between border-b border-brand-hairline py-4">
                            <span class="text-[15px] text-brand-navy">{{ $row['day'] }}</span>
                            <span class="text-sm text-brand-stone">{{ $row['time'] }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="mt-5 text-sm text-brand-stone-light">Hours vary slightly by branch — see each branch page.</p>
            </div>

            <div class="reveal rounded-[20px] border border-brand-hairline bg-white p-8 shadow-editorial-card">
                <h3 class="text-[22px]">General enquiries</h3>
                <p class="mt-4 font-serif text-3xl text-brand-navy">{{ config('sandwell.phone') }}</p>
                <p class="mt-2 text-sm text-brand-stone">Monday to Friday, 9:00 – 18:00</p>
                <p class="mt-6 border-t border-brand-hairline pt-6 text-sm text-brand-stone">Prefer to talk in person? Ask in branch — there is always a pharmacist available.</p>
            </div>
        </div>
    </section>

    <x-final-cta :href="route('repeat-prescriptions').'#nominate'" action="Nominate us as your pharmacy"
        sub="Pick the branch that suits your route home — the service is identical at all six.">
        Make your nearest branch <span class="editorial-highlight">your pharmacy.</span>
    </x-final-cta>
</x-layout>
