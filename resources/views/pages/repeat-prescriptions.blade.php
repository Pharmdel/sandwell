@push('scripts') @vite('resources/js/services.js') @endpush

<x-layout title="Repeat prescriptions & nominate us" hero description="Nominate any Hollytree Pharmacy branch and we'll prepare your repeat prescriptions and deliver them free to your door.">
    <x-page-hero image="delivery-door.jpg" alt="A delivery driver handing a pharmacy bag to a customer at their door">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">NHS repeat prescriptions</p>

            <h1 class="mt-5 text-5xl leading-[1.02] text-white md:text-[76px]" data-lines>
                <span class="line-mask"><span>Getting your medicines,</span></span>
                <span class="line-mask"><span class="editorial-highlight">sorted.</span></span>
            </h1>

            <p class="reveal mt-8 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Nominate any Hollytree Pharmacy branch and we'll prepare your repeat prescriptions,
                text you when they're ready, and deliver them free to your door.
            </p>

            <div class="reveal mt-10 flex flex-wrap gap-3" style="--reveal-delay:440ms">
                <x-btn href="#nominate" data-magnetic>Nominate us now</x-btn>
                <x-btn :href="route('book')" variant="light" data-magnetic>Order a repeat prescription</x-btn>
            </div>

            <div class="no-interact reveal mt-12 flex flex-wrap gap-x-6 gap-y-2 border-t border-white/15 pt-7 text-[11px] font-semibold uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:540ms">
                <span>Free delivery over {{ config('pharmacy.free_delivery_threshold') }}</span>
                <span>Text reminders</span>
                <span>Six branches</span>
            </div>
        </div>
    </x-page-hero>

    <section class="no-interact border-y border-brand-pistachio-line bg-brand-pistachio py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 text-center sm:px-8">
            <div class="reveal">
                <x-eyebrow>Simple by design</x-eyebrow>
                <h2 class="mx-auto mt-3 max-w-2xl text-4xl leading-tight md:text-[42px]">Three steps, <span class="editorial-highlight">then it just happens.</span></h2>
            </div>
            <x-steps :items="[
                ['title' => 'Nominate us', 'body' => 'Choose any Hollytree branch in the NHS App, or let us set it up for you.'],
                ['title' => 'We prepare it', 'body' => 'Your GP sends the script straight to us and our team dispenses it.'],
                ['title' => 'Delivered to your door', 'body' => 'Tracked, free, and with a text when it is on its way.'],
            ]" />
        </div>
    </section>

    <section id="nominate" class="scroll-mt-24 bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Choose what suits you</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Nominate us <span class="editorial-highlight">your way.</span></h2>
            </div>

            <div class="no-interact mt-14 grid gap-6 lg:grid-cols-3" data-reveal-group>
                <div class="reveal overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card lg:col-span-2 lg:row-span-2">
                    <img src="{{ asset('images/nhs-app.jpg') }}" alt="" class="h-64 w-full rounded-2xl object-cover">
                    <h3 class="mt-6 text-[26px] leading-tight">In the NHS App</h3>
                    <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                        Open the NHS App, go to “Your nominated pharmacy” and choose any Sandwell branch.
                        It takes about thirty seconds and updates straight away.
                    </p>
                </div>

                @foreach ([
                    ['At your GP surgery', 'Tell reception you would like Hollytree Pharmacy as your nominated pharmacy.'],
                    ['Let us do it', 'Pop in or call and we will set it up for you in under a minute.'],
                ] as $way)
                    <div class="reveal rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card">
                        <h3 class="text-[22px] leading-tight">{{ $way[0] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $way[1] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="reveal mt-12 text-center">
                <x-btn :href="route('contact')">Ask us to set it up</x-btn>
            </div>
        </div>
    </section>

    <section id="delivery" class="scroll-mt-24 border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] items-center gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <div class="no-interact reveal">
                <x-eyebrow>Delivery</x-eyebrow>
                <h2 class="mt-3 text-[32px] leading-tight md:text-[42px]">Delivered by <span class="editorial-highlight">our own drivers.</span></h2>
                <div class="mt-8">
                    @foreach ([
                        'Free delivery on orders over '.config('pharmacy.free_delivery_threshold'),
                        'Tracked, with a text when it is on its way',
                        'Covering West Bromwich, Smethwick, Oldbury and Stourbridge',
                    ] as $row)
                        <p class="border-b border-brand-hairline py-4 text-[15px] text-brand-forest last:border-0">{{ $row }}</p>
                    @endforeach
                </div>
            </div>
            <div class="reveal">
                <div class="overflow-hidden rounded-3xl border border-brand-hairline shadow-editorial-card">
                    <img src="{{ asset('images/map.jpg') }}" alt="Map of the delivery area across Sandwell and Stourbridge" class="h-72 w-full object-cover">
                </div>
                <div class="mt-8 text-center">
                    <x-btn :href="route('sign-in')" variant="outline" class="group">Track my order</x-btn>
                </div>
            </div>
        </div>
    </section>

    <section id="blister-packs" class="no-interact scroll-mt-24 border-b border-brand-pistachio-line bg-brand-pistachio py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] items-center gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <div class="reveal clip-reveal overflow-hidden rounded-[28px] shadow-editorial-card">
                <img src="{{ asset('images/blister-pack.jpg') }}" alt="A weekly blister pack on a kitchen table" class="h-80 w-full object-cover">
            </div>
            <div class="reveal">
                <x-eyebrow>MDS blister packs</x-eyebrow>
                <h2 class="mt-3 text-[32px] leading-tight md:text-[42px]">Every dose, <span class="editorial-highlight">in its place.</span></h2>
                <p class="mt-6 text-base leading-relaxed text-brand-stone">
                    If you or someone you care for manages several medicines a day, our team can pack them
                    into clearly labelled weekly trays — morning, noon, evening and night — so there's never
                    any doubt about what has been taken.
                </p>
                <p class="mt-6 text-sm font-semibold text-brand-forest">Ask in branch to get started.</p>
            </div>
        </div>
    </section>

    {{-- Do we deliver to you? --}}
    <section id="check-delivery" class="scroll-mt-24 border-t border-brand-hairline bg-white py-20 md:py-24">
        <div class="mx-auto max-w-[620px] px-6 text-center sm:px-8">
            <x-eyebrow class="reveal">Check your delivery</x-eyebrow>
            <h2 class="reveal mt-3 text-4xl leading-tight md:text-[40px]">Do we deliver <span class="editorial-highlight">to you?</span></h2>
            <p class="reveal mt-4 text-base text-brand-stone">Enter your postcode — NHS prescription delivery is free everywhere we cover.</p>

            <div data-postcode-check class="reveal mx-auto mt-8 max-w-md">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <label for="delivery-postcode" class="sr-only">Your postcode</label>
                    <input id="delivery-postcode" data-postcode-input type="text" inputmode="text" autocomplete="postal-code" placeholder="e.g. B70 7RW"
                        class="w-full rounded-full border border-brand-hairline bg-white px-6 py-3.5 text-[15px] uppercase text-brand-forest placeholder:normal-case placeholder:text-brand-stone-light focus:border-brand-moss focus:outline-none focus:ring-2 focus:ring-brand-moss/25">
                    <button type="button" data-postcode-run
                        class="shrink-0 rounded-full bg-brand-forest px-7 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-brand-moss active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                        Check
                    </button>
                </div>
                <div data-postcode-result class="mt-5 text-left"></div>
            </div>
        </div>
    </section>

    <x-service-form slug="repeat-prescriptions" :form="config('services.forms.repeat-prescriptions')" />

    <section id="faqs" class="no-interact scroll-mt-24 bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>FAQs</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Questions, <span class="editorial-highlight">answered.</span></h2>
            </div>
            <div class="reveal mt-14 grid gap-x-12 sm:grid-cols-2">
                @foreach ([
                    ['How long does a repeat take?', 'Most repeats are ready within 48 hours of your GP approving them. We will text you the moment yours is dispensed.'],
                    ['Can I still collect in branch?', 'Of course. Nominating us does not commit you to delivery — collect whenever it suits you.'],
                    ['What if I am away?', 'Let us know your dates and we will hold the delivery or send it to another address.'],
                    ['Do you deliver controlled medicines?', 'Some controlled drugs must be collected in person with ID. We will tell you if yours is one of them.'],
                ] as $faq)
                    <div class="border-b border-brand-hairline py-6">
                        <h3 class="text-xl leading-snug">{{ $faq[0] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $faq[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-final-cta href="#nominate" action="Nominate us now"
        sub="Register online or speak to our team — we will handle the paperwork.">
        Make us your pharmacy <span class="editorial-highlight">in under a minute.</span>
    </x-final-cta>
</x-layout>
