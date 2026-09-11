{{-- No overlay header here: the right half is white, so white header text would disappear. --}}
<x-layout title="Sign in" description="Track deliveries, manage repeat prescriptions and keep your basket in one place.">
    <section data-hero class="grid min-h-[calc(100svh-5rem)] lg:grid-cols-2">
        {{-- Left editorial panel, full-bleed image behind --}}
        <div class="grain relative flex items-center overflow-hidden px-6 py-24 sm:px-12 lg:px-16">
            <div class="hero-media absolute inset-x-0 -top-[10%] -bottom-[10%]" data-parallax="0.08">
                <img src="{{ asset('images/hero/nhs-app-1600.jpg') }}"
                    srcset="{{ asset('images/hero/nhs-app-1600.jpg') }} 1600w, {{ asset('images/hero/nhs-app-2600.jpg') }} 2600w"
                    sizes="(min-width: 1024px) 50vw, 100vw" alt="" class="h-full w-full object-cover">
            </div>
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-navy-deep/95 via-brand-navy-deep/85 to-brand-navy-deep/70"></div>

            <div class="relative w-full max-w-lg">
                <span class="reveal inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.14em] text-brand-orange">
                    <span class="h-1.5 w-1.5 animate-pulsing-dot rounded-full bg-brand-orange"></span>
                    My account
                </span>

                <h1 class="mt-5 text-5xl leading-[1.02] text-white md:text-[68px]" data-lines>
                    <span class="line-mask"><span>Welcome <span class="editorial-highlight">back.</span></span></span>
                </h1>

                <p class="reveal mt-6 text-lg leading-relaxed text-white/80" style="--reveal-delay:300ms">
                    Track deliveries, manage repeat prescriptions and keep your basket in one place.
                </p>

                <div class="no-interact mt-12" data-reveal-group>
                    @foreach ([
                        ['Track every order to your door', 'M3 7h11v8H3zM14 10h4l3 3v2h-7z'],
                        ['Reorder repeats in one tap', 'M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006 5.3M4 15a8 8 0 0014 3.7'],
                        ['Your basket and receipts, saved', 'M6 7h12l-1 13H7zM9 7a3 3 0 016 0'],
                    ] as $row)
                        <div class="reveal flex items-center gap-4 border-b border-white/15 py-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 backdrop-blur">
                                <svg class="h-5 w-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $row[1] }}"/></svg>
                            </span>
                            <span class="text-[15px] font-medium text-white">{{ $row[0] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="no-interact reveal mt-8 flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange text-xs font-semibold text-white">LP</span>
                    <span>
                        <span class="block text-xs text-white/60">Nominated NHS dispensing hub</span>
                        <span class="block text-sm font-semibold text-white">Signed in as a patient of Lyng Pharmacy</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Right auth card --}}
        <div class="flex items-center justify-center bg-white px-6 py-20 sm:px-12">
            <div class="reveal w-full max-w-[440px]">
                <div class="rounded-[28px] border border-brand-hairline bg-white p-8 shadow-editorial-card sm:p-12">
                    <h2 class="text-[28px] leading-tight">Sign <span class="editorial-highlight">in.</span></h2>

                    <form method="get" action="{{ route('sign-in') }}" class="mt-8 space-y-5">
                        <div>
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Email address</label>
                            <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.co.uk"
                                class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-navy transition placeholder:text-brand-stone-light focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Password</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 pr-12 text-[15px] text-brand-navy transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-brand-stone-light" aria-hidden="true">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.6"/></svg>
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('contact') }}" class="text-sm text-brand-stone underline decoration-brand-hairline underline-offset-4 transition hover:text-brand-navy hover:decoration-brand-orange">
                                Forgot your password?
                            </a>
                        </div>

                        <button type="submit"
                            class="w-full rounded-full bg-brand-orange px-7 py-3.5 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-orange-hover hover:shadow-orange-glow active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
                            Sign in
                        </button>
                    </form>

                    <div class="my-6 flex items-center gap-4">
                        <span class="h-px flex-1 bg-brand-hairline"></span>
                        <span class="text-xs uppercase tracking-[0.1em] text-brand-stone-light">or</span>
                        <span class="h-px flex-1 bg-brand-hairline"></span>
                    </div>

                    <a href="{{ route('sign-in') }}"
                        class="flex w-full items-center justify-center gap-3 rounded-full border border-brand-hairline bg-white px-7 py-3.5 text-sm font-semibold text-brand-navy transition hover:border-brand-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-navy">
                        <span class="rounded bg-brand-nhs-blue px-1.5 py-0.5 text-[10px] font-bold tracking-wide text-white">NHS</span>
                        Continue with NHS login
                    </a>

                    <p class="mt-8 text-center text-sm text-brand-stone">New to Sandwell Pharmacy Group?</p>
                    <a href="{{ route('contact') }}"
                        class="mt-3 flex w-full items-center justify-center rounded-full border border-brand-navy px-7 py-3.5 text-sm font-semibold text-brand-navy transition hover:bg-brand-navy hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-navy">
                        Create an account
                    </a>
                </div>

                <p class="no-interact mt-6 text-center text-xs leading-relaxed text-brand-stone-light">
                    Your data is stored securely in the UK and never shared without consent.
                </p>
            </div>
        </div>
    </section>
</x-layout>
