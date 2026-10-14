@php
    $branches = config('pharmacy.branches');
    $phone = config('pharmacy.phone');

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

    $link = 'group inline-flex items-center text-sm text-footer-text transition-colors duration-200 hover:text-footer-ink';
    $rule = 'ml-2 h-px w-0 bg-footer-ink transition-all duration-200 group-hover:w-4';
@endphp

<footer class="grain relative overflow-hidden bg-footer text-footer-text [--highlight:#fff]">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[520px] w-[900px] -translate-x-1/2 rounded-full bg-[radial-gradient(circle,rgba(255,255,255,0.45),transparent_70%)]"></div>

    <div class="relative mx-auto max-w-[1280px] px-6 sm:px-8">
        {{-- Brand + phone --}}
        <div class="flex flex-col gap-10 py-16 md:flex-row md:items-start md:justify-between">
            <div class="max-w-md">
                <div class="flex items-center gap-3.5">
                    <img src="{{ asset('images/logo-nav.png') }}" alt="" width="54" height="54" class="h-[54px] w-[54px] rounded-full bg-white/95 p-0.5">
                    <span class="wordmark text-[20px]">
                        <span class="wordmark__name text-footer-ink">HOLLYTREE</span>
                        <span class="wordmark__sub text-footer-link">Pharmacy</span>
                    </span>
                </div>
                <p class="mt-6 font-serif text-2xl leading-snug text-footer-ink">
                    NHS community pharmacy, <span class="editorial-highlight">free local delivery.</span>
                </p>
                <p class="mt-3 text-sm leading-relaxed text-footer-text">
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
                    <x-chip tone="quiet" class="!bg-white/60 !text-brand-forest">GPhC registered</x-chip>
                </div>
            </div>

            <div class="shrink-0 rounded-3xl border border-brand-forest/15 bg-white/55 p-7 backdrop-blur md:min-w-[300px]">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-footer-text">Talk to a pharmacist</p>
                <a href="{{ config('pharmacy.phone_href') }}"
                    class="mt-3 block font-serif text-[34px] leading-none text-footer-ink transition-colors hover:text-brand-moss-hover">{{ $phone }}</a>
                <p class="mt-3 text-xs leading-relaxed text-footer-meta">
                    Mon–Fri 8:30–18:00 · Sat 9:00–13:00<br>Free delivery on orders over {{ config('pharmacy.free_delivery_threshold') }}
                </p>
                <a href="{{ route('branches') }}"
                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border border-brand-forest/40 px-5 py-3 text-sm font-semibold text-brand-forest transition-all hover:bg-brand-forest hover:text-white">
                    Six branches — find yours
                </a>
            </div>
        </div>

        {{-- Link columns --}}
        <div class="grid gap-10 border-t border-brand-forest/15 py-14 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <h2 class="font-serif text-xl text-footer-ink">Our branches</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($branches as $b)
                        <li>
                            <a href="{{ route('branches.show', $b['slug']) }}" class="{{ $link }}">
                                {{ $b['name'] }}@if ($b['hub'])<span class="ml-1.5 text-[10px] uppercase tracking-wide text-footer-meta">Hub</span>@endif
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
                <h2 class="font-serif text-xl text-footer-ink">Services</h2>
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
                <h2 class="font-serif text-xl text-footer-ink">Shop &amp; help</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($shopHelp as [$label, $href])
                        <li><a href="{{ $href }}" class="{{ $link }}">{{ $label }}<span class="{{ $rule }}"></span></a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="font-serif text-xl text-footer-ink">Our regulation</h2>
                <dl class="mt-5 space-y-5">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.1em] text-footer-ink">Superintendent pharmacists</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-footer-meta">
                            One per operating company —
                            <a href="{{ route('terms') }}#companies" class="text-footer-ink underline decoration-footer-ink/40 underline-offset-4 transition hover:text-brand-moss-hover hover:decoration-brand-moss-hover">named on each branch page</a>.
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.1em] text-footer-ink">Registered pharmacies</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-footer-meta">
                            Six premises, each separately registered —
                            <a href="{{ route('terms') }}#companies" class="text-footer-ink underline decoration-footer-ink/40 underline-offset-4 transition hover:text-brand-moss-hover hover:decoration-brand-moss-hover">see the full list</a>.
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.1em] text-footer-ink">Regulated by</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-footer-meta">
                            <a href="https://www.pharmacyregulation.org/registers/pharmacy" target="_blank" rel="noopener"
                                class="text-footer-ink underline decoration-footer-ink/40 underline-offset-4 transition hover:text-brand-moss-hover hover:decoration-brand-moss-hover">General Pharmaceutical Council</a>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Legal --}}
        <div class="flex flex-col gap-5 border-t border-brand-forest/15 py-8 lg:flex-row lg:items-center lg:justify-between">
            <ul class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-footer-text">
                <li><a href="{{ route('terms') }}" class="text-footer-ink transition hover:text-brand-moss-hover">Terms</a></li>
                <li><a href="{{ route('terms') }}#complaints" class="text-footer-ink transition hover:text-brand-moss-hover">Complaints</a></li>
                {{-- No policy pages exist yet, so these stay as plain text rather than dead links. --}}
                @foreach (['Privacy', 'Cookies', 'Data protection', 'Shop terms', 'Returns & cancellations'] as $item)
                    <li class="no-interact text-footer-meta/80">{{ $item }}</li>
                @endforeach
            </ul>
            <p class="text-xs text-footer-meta">© {{ date('Y') }} Hollytree Pharmacy Ltd. All rights reserved.</p>
        </div>

        <p class="no-interact border-t border-brand-forest/15 py-6 text-center text-[11px] leading-relaxed text-footer-meta/80">
            Six separately registered NHS community pharmacies · Regulated by the General Pharmaceutical Council &amp; NHS England ·
            For urgent medical advice call 111, or 999 in an emergency
        </p>
    </div>
</footer>
