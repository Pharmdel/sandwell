<x-layout title="Contact" hero description="Call, message or drop into any of our six branches across Sandwell and Stourbridge — a pharmacist is always on hand.">
    <x-page-hero image="team.jpg" alt="The pharmacy team behind the counter" size="short">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-orange">Contact</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[62px]" data-lines>
                <span class="line-mask"><span>We're <span class="editorial-highlight">here to help.</span></span></span>
            </h1>

            <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:300ms">
                Questions about a prescription, a service or an order? Call, message, or drop into any
                branch — a pharmacist is always on hand.
            </p>
        </div>
    </x-page-hero>

    <section class="py-16 md:py-24">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="reveal lg:col-span-7">
                    <div class="rounded-3xl border border-brand-hairline bg-white p-8 shadow-editorial-card md:p-10">
                        <h2 class="text-[26px] leading-tight">Send us a <span class="editorial-highlight">message.</span></h2>

                        @if (session('sent'))
                            <p class="mt-6 rounded-2xl bg-brand-peach px-5 py-4 text-sm text-brand-orange-hover">
                                Thanks — your message is with our team. We reply within one working day.
                            </p>
                        @endif

                        <form action="{{ route('contact.send') }}" method="post" class="mt-8 space-y-5">
                            @csrf

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Full name</label>
                                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                                        class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-navy transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
                                    @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Email address</label>
                                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                        class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-navy transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
                                    @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Phone <span class="normal-case tracking-normal text-brand-stone-light">(optional)</span></label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                                        class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-navy transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
                                </div>
                                <div>
                                    <label for="branch" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Branch</label>
                                    <select id="branch" name="branch"
                                        class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-navy transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
                                        <option value="">Choose a branch</option>
                                        @foreach (config('sandwell.branches') as $b)
                                            <option value="{{ $b['slug'] }}" @selected(old('branch') === $b['slug'])>{{ $b['name'] }} — {{ $b['town'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="message" class="mb-2 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">Your message</label>
                                <textarea id="message" name="message" rows="5" required
                                    class="w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-navy transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">{{ old('message') }}</textarea>
                                @error('message')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col items-start gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-xs leading-relaxed text-brand-stone">
                                    We reply within one working day.<br>For urgent medical advice call 111.
                                </p>
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-orange px-7 py-3.5 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-orange-hover hover:shadow-orange-glow active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
                                    Send message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <aside class="reveal lg:col-span-5">
                    <div class="no-interact">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Call us</p>
                        <p class="mt-3 font-serif text-4xl text-brand-navy">{{ config('sandwell.phone') }}</p>
                        <p class="mt-2 text-sm text-brand-stone">Mon–Fri 8:30–18:00 · Sat 9:00–13:00</p>
                    </div>

                    <div class="no-interact mt-10 border-t border-brand-hairline pt-8">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Visit a branch</p>
                        <div class="mt-4">
                            @foreach (config('sandwell.branches') as $b)
                                <div class="flex items-center justify-between gap-3 border-b border-brand-hairline py-3">
                                    <span class="text-[15px] text-brand-navy">{{ $b['short'] }}</span>
                                    <span class="text-xs text-brand-stone-light">{{ $b['town'] }}{{ $b['hub'] ? ' · Dispensing hub' : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8">
                        <x-btn :href="route('branches')" variant="outline" class="group w-full">Find your nearest branch</x-btn>
                    </div>

                    <div class="no-interact mt-10 border-t border-brand-hairline pt-8">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Feedback &amp; complaints</p>
                        <p class="mt-4 text-sm leading-relaxed text-brand-stone">
                            Ask to speak to the superintendent pharmacist at any branch — most things are
                            resolved on the spot.
                        </p>
                        <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                            You can also raise concerns with the General Pharmaceutical Council.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="no-interact border-t border-brand-hairline bg-brand-ivory pb-24">
        <div class="mx-auto max-w-[1280px] px-6 pt-16 sm:px-8">
            <div class="reveal relative overflow-hidden rounded-[28px] border border-brand-hairline shadow-editorial-card">
                <img src="{{ asset('images/map.jpg') }}" alt="Map of all six branches across Sandwell and Stourbridge" class="h-[360px] w-full object-cover">
                <div class="absolute bottom-6 left-6 rounded-2xl bg-white/95 px-5 py-4 shadow-editorial-card backdrop-blur">
                    <p class="text-sm font-semibold text-brand-navy">Six branches across Sandwell &amp; Stourbridge</p>
                </div>
            </div>
        </div>
    </section>
</x-layout>
