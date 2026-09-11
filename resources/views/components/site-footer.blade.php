@php
    $branches = config('sandwell.branches');
    $phone = config('sandwell.phone');

    $services = [
        ['Repeat prescriptions', route('repeat-prescriptions')],
        ['Nominate us', route('repeat-prescriptions').'#nominate'],
        ['Pharmacy First', route('pharmacy-first')],
        ['Minor ailments scheme', route('pharmacy-first')],
        ['Flu & Covid vaccinations', route('services.show', 'flu-covid-vaccinations')],
        ['Travel vaccinations', route('services.show', 'travel-vaccinations')],
        ['Weight loss clinic', route('weight-loss')],
        ['Blood pressure checks', route('services.show', 'hypertension')],
    ];

    $shopHelp = [
        ['Online shop', route('shop')],
        ['My account', route('sign-in')],
        ['Track an order', route('sign-in')],
        ['Health Hub', route('health-hub')],
        ['Medicines A–Z', route('health-hub').'#tools'],
        ['FAQs', rtrim(route('home'), '/').'/#faqs'],
        ['About us', route('terms').'#about-us'],
        ['Contact us', route('contact')],
    ];

    $link = 'group inline-flex items-center text-sm text-footer-link transition-colors duration-200 hover:text-white';
    $rule = 'ml-2 h-px w-0 bg-white transition-all duration-200 group-hover:w-4';
@endphp

<footer class="grain relative overflow-hidden bg-footer text-footer-text">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[520px] w-[900px] -translate-x-1/2 rounded-full bg-[radial-gradient(circle,rgba(26,31,78,0.6),transparent_70%)]"></div>

    <div class="relative mx-auto max-w-[1280px] px-6 sm:px-8">
        {{-- Brand + phone --}}
        <div class="flex flex-col gap-10 py-16 md:flex-row md:items-start md:justify-between">
            <div class="max-w-md">
                <div class="flex items-center gap-3.5">
                    <img src="{{ asset('images/logo-nav.png') }}" alt="" width="54" height="54" class="h-[54px] w-[54px] rounded-full bg-white/95 p-0.5">
                    <span class="wordmark text-[20px]">
                        <span class="wordmark__name text-white">SANDWELL</span>
                        <span class="wordmark__sub text-footer-link">Pharmacy Group</span>
                    </span>
                </div>
                <p class="mt-6 font-serif text-2xl leading-snug text-white">
                    NHS community pharmacy, <span class="text-footer-link">free local delivery.</span>
                </p>
                <p class="mt-3 text-sm leading-relaxed text-footer-tagline">
                    Six neighbourhood pharmacies across West Bromwich, Smethwick and Stourbridge — NHS and
                    private care, with a pharmacist you can actually talk to.
                </p>
                {{-- Official NHS England "Providing NHS services" mark, reversed for dark grounds. --}}
                <div class="mt-7 flex flex-wrap items-center gap-5">
                    <span class="inline-flex rounded-xl bg-white px-3.5 py-2.5">
                        <img src="{{ asset('images/nhs/providing-nhs-services-blue-700.png') }}"
                            srcset="{{ asset('images/nhs/providing-nhs-services-blue-700.png') }} 700w, {{ asset('images/nhs/providing-nhs-services-blue-1400.png') }} 1400w"
                            sizes="132px" width="132" height="35" alt="Providing NHS services" class="h-[35px] w-auto">
                    </span>
                    <x-chip tone="quiet" class="!bg-white/10 !text-footer-text">GPhC registered</x-chip>
                </div>
            </div>

            <div class="shrink-0 rounded-3xl border border-white/[0.14] bg-white/[0.08] p-7 backdrop-blur md:min-w-[300px]">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-footer-link">Talk to a pharmacist</p>
                <a href="{{ config('sandwell.phone_href') }}"
                    class="mt-3 block font-serif text-[34px] leading-none text-white transition-colors hover:text-footer-link">{{ $phone }}</a>
                <p class="mt-3 text-xs leading-relaxed text-footer-meta">
                    Mon–Fri 8:30–18:00 · Sat 9:00–13:00<br>Free delivery on orders over {{ config('sandwell.free_delivery_threshold') }}
                </p>
                <a href="{{ route('branches') }}"
                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border border-white/25 px-5 py-3 text-sm font-semibold text-white transition-all hover:bg-white hover:text-brand-navy">
                    Six branches — find yours
                </a>
            </div>
        </div>

        {{-- Link columns --}}
        <div class="grid gap-10 border-t border-white/[0.12] py-14 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <h2 class="font-serif text-xl text-white">Our branches</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($branches as $b)
                        <li>
                            <a href="{{ route('branches.show', $b['slug']) }}" class="{{ $link }}">
                                {{ $b['name'] }}@if ($b['hub'])<span class="ml-1.5 text-[10px] uppercase tracking-wide text-footer-text/70">Hub</span>@endif
                                <span class="{{ $rule }}"></span>
                            </a>
                        </li>
                    @endforeach
                    <li class="pt-1">
                        <a href="{{ route('branches') }}" class="text-sm font-semibold text-footer-link hover:underline">Compare all six →</a>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="font-serif text-xl text-white">Services</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($services as [$label, $href])
                        <li><a href="{{ $href }}" class="{{ $link }}">{{ $label }}<span class="{{ $rule }}"></span></a></li>
                    @endforeach
                    <li class="pt-1">
                        <a href="{{ route('services') }}" class="text-sm font-semibold text-footer-link hover:underline">All 19 services →</a>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="font-serif text-xl text-white">Shop &amp; help</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($shopHelp as [$label, $href])
                        <li><a href="{{ $href }}" class="{{ $link }}">{{ $label }}<span class="{{ $rule }}"></span></a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="font-serif text-xl text-white">Our regulation</h2>
                <dl class="mt-5 space-y-5">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.1em] text-white">Superintendent pharmacists</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-footer-meta">
                            One per operating company —
                            <a href="{{ route('terms') }}#companies" class="text-footer-link underline decoration-footer-link/40 underline-offset-4 transition hover:text-white hover:decoration-white">named on each branch page</a>.
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.1em] text-white">Registered pharmacies</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-footer-meta">
                            Six premises, each separately registered —
                            <a href="{{ route('terms') }}#companies" class="text-footer-link underline decoration-footer-link/40 underline-offset-4 transition hover:text-white hover:decoration-white">see the full list</a>.
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.1em] text-white">Regulated by</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-footer-meta">
                            <a href="https://www.pharmacyregulation.org/registers/pharmacy" target="_blank" rel="noopener"
                                class="text-footer-link underline decoration-footer-link/40 underline-offset-4 transition hover:text-white hover:decoration-white">General Pharmaceutical Council</a>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Legal --}}
        <div class="flex flex-col gap-5 border-t border-white/[0.12] py-8 lg:flex-row lg:items-center lg:justify-between">
            <ul class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-footer-text">
                <li><a href="{{ route('terms') }}" class="text-footer-link transition hover:text-white">Terms</a></li>
                <li><a href="{{ route('terms') }}#complaints" class="text-footer-link transition hover:text-white">Complaints</a></li>
                {{-- No policy pages exist yet, so these stay as plain text rather than dead links. --}}
                @foreach (['Privacy', 'Cookies', 'Data protection', 'Shop terms', 'Returns & cancellations'] as $item)
                    <li class="no-interact text-footer-text/45">{{ $item }}</li>
                @endforeach
            </ul>
            <p class="text-xs text-footer-copy">© {{ date('Y') }} Sandwell Pharmacy Group Ltd. All rights reserved.</p>
        </div>

        <p class="no-interact border-t border-white/[0.12] py-6 text-center text-[11px] leading-relaxed text-footer-text/60">
            Six separately registered NHS community pharmacies · Regulated by the General Pharmaceutical Council &amp; NHS England ·
            For urgent medical advice call 111, or 999 in an emergency
        </p>
    </div>
</footer>
