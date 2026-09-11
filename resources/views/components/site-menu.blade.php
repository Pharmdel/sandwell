@php
    // Service links come from the catalogue, so the menu can never list a service
    // whose page does not exist. The set matches the group's own menu.
    $cat = config('services.services');
    $ext = collect(config('services.external'))->keyBy('slug');

    $link = function (string $slug) use ($cat, $ext) {
        if (isset($cat[$slug])) {
            return [$cat[$slug]['menu']['title'] ?? $cat[$slug]['nav'], route('services.show', $slug)];
        }

        $s = $ext[$slug];

        return [$s['menu']['title'] ?? $s['nav_title'] ?? $s['nav'], route($s['route']).(isset($s['fragment']) ? '#'.$s['fragment'] : '')];
    };

    $nhs = array_map($link, ['pharmacy-first', 'repeat-prescriptions', 'flu-covid-vaccinations', 'contraception', 'mds-trays', 'hypertension']);

    // The scheme lives on the Pharmacy First page, so it links to that section.
    array_splice($nhs, 1, 0, [['Minor Ailments Scheme', route('pharmacy-first').'#minor-ailments']]);

    $private = array_map($link, ['weight-loss', 'hair-loss', 'period-delay', 'b12-injections', 'travel-vaccinations', 'acne', 'chickenpox-vaccination']);

    $groups = [
        ['no' => '01', 'title' => 'NHS services', 'links' => $nhs],
        ['no' => '02', 'title' => 'Private services', 'links' => $private],
        ['no' => '03', 'title' => 'Prescriptions', 'links' => [
            ['Repeat prescriptions', route('repeat-prescriptions')],
            ['Nominate us', route('repeat-prescriptions').'#nominate'],
            ['Track an order', route('repeat-prescriptions').'#delivery'],
            ['MDS blister packs', route('services.show', 'mds-trays')],
        ]],
        ['no' => '04', 'title' => 'Branches', 'links' => collect(config('sandwell.branches'))
            ->map(fn ($b) => [$b['short'].($b['hub'] ? ' (Dispensing hub)' : ''), route('branches.show', $b['slug'])])
            ->all()],
        ['no' => '05', 'title' => 'Health Hub', 'links' => [
            ['Conditions A–Z', route('pharmacy-first').'#a-z'],
            ['Articles', route('health-hub')],
            ['Medicines A–Z', route('health-hub').'#tools'],
            ['FAQs', rtrim(route('home'), '/').'/#faqs'],
            ['Contact us', route('contact')],
        ]],
        ['no' => '06', 'title' => 'Shop & account', 'links' => [
            ['Browse the shop', route('shop')],
            ['Featured products', route('shop').'#featured'],
            ['Sign in', route('sign-in')],
            ['Book an appointment', route('book')],
            ['All services', route('services')],
        ]],
    ];
@endphp

<div id="site-menu" hidden
    class="fixed inset-0 z-50 opacity-0 transition-opacity duration-[250ms] [&.is-open]:opacity-100">
    <div class="absolute inset-0 bg-brand-navy-deep/30 backdrop-blur-sm" data-menu-close aria-hidden="true"></div>

    <div role="dialog" aria-modal="true" aria-label="Site menu"
        class="grain absolute inset-0 overflow-y-auto bg-brand-ivory sm:inset-4 sm:rounded-3xl sm:shadow-editorial-hover">
        <div class="flex h-20 items-center justify-between border-b border-brand-hairline px-6 sm:px-10">
            <a href="{{ route('home') }}" class="flex items-center gap-3.5">
                <img src="{{ asset('images/logo-nav.png') }}" alt="" width="48" height="48" class="h-12 w-12">
                <span class="wordmark text-[18px]">
                    <span class="wordmark__name text-brand-navy">SANDWELL</span>
                    <span class="wordmark__sub text-brand-orange">Pharmacy Group</span>
                </span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ config('sandwell.phone_href') }}"
                    class="hidden items-center gap-2 rounded-full bg-brand-orange px-5 py-2.5 text-sm font-medium text-white transition-all hover:bg-brand-orange-hover hover:shadow-orange-glow sm:inline-flex">
                    Call {{ config('sandwell.phone') }}
                </a>
                <button type="button" data-menu-close aria-label="Close menu"
                    class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-navy text-white transition-transform hover:scale-105 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="mx-auto grid max-w-[1280px] gap-12 px-6 py-12 sm:px-10 lg:grid-cols-12">
            <div class="grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:col-span-8 lg:grid-cols-3">
                @foreach ($groups as $group)
                    <div>
                        <div class="mb-3 flex items-center gap-2">
                            <span class="text-[11px] font-bold tracking-[0.14em] text-brand-orange">{{ $group['no'] }}</span>
                            <span class="h-px w-6 bg-brand-orange/40"></span>
                        </div>
                        <h2 class="mb-4 text-3xl">{{ $group['title'] }}</h2>
                        <ul class="space-y-3 border-t border-brand-hairline pt-4">
                            @foreach ($group['links'] as [$label, $href])
                                <li>
                                    <a href="{{ $href }}"
                                        class="group inline-flex items-center text-[17px] text-brand-stone transition-all duration-200 hover:translate-x-1 hover:text-brand-navy">
                                        {{ $label }}
                                        <span class="ml-2 h-px w-0 bg-brand-orange transition-all duration-200 group-hover:w-4"></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <aside class="lg:col-span-4">
                <div class="overflow-hidden rounded-3xl border border-brand-hairline bg-white p-6 shadow-editorial-card">
                    <div class="relative overflow-hidden rounded-2xl">
                        <img src="{{ asset('images/flu-vaccine.jpg') }}" alt="A pharmacist preparing a flu vaccine" class="h-56 w-full object-cover">
                        <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-navy">
                            <span class="h-1.5 w-1.5 animate-pulsing-dot rounded-full bg-brand-orange"></span>
                            Now booking
                        </span>
                    </div>
                    <h2 class="mt-6 text-[28px] leading-tight">Flu &amp; Covid-19 jabs,<br><span class="editorial-highlight text-brand-orange">walk in or book.</span></h2>
                    <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                        Check your eligibility in under a minute, then book across all six branches.
                    </p>
                    <a href="{{ route('services.show', 'flu-covid-vaccinations') }}"
                        class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-orange px-6 py-3.5 text-sm font-semibold text-white transition-all hover:bg-brand-orange-hover hover:shadow-orange-glow">
                        Check eligibility
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5" />
                        </svg>
                    </a>
                </div>
            </aside>
        </div>

        <div class="no-interact mx-auto flex max-w-[1280px] flex-col items-center justify-between gap-3 border-t border-brand-hairline px-6 py-6 text-[11px] uppercase tracking-[0.1em] text-brand-stone-light sm:flex-row sm:px-10">
            <span>Open Mon–Sat · Free delivery over {{ config('sandwell.free_delivery_threshold') }}</span>
            <span class="text-sm font-semibold normal-case tracking-normal text-brand-navy">{{ config('sandwell.phone') }}</span>
            <span>Privacy · <a href="{{ route('terms') }}" class="underline decoration-brand-hairline underline-offset-4 hover:text-brand-navy hover:decoration-brand-orange">Terms</a> · Cookies</span>
        </div>
    </div>
</div>
