<x-layout title="Online shop" hero description="Pharmacy essentials delivered by our own drivers — free on orders over £15, dispatched from our dispensing hub.">
    <x-page-hero image="products.jpg" alt="Pharmacy products arranged on a pale oak surface">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">Online shop · Delivered by our own drivers</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[66px]" data-lines>
                <span class="line-mask"><span>Pharmacy essentials,</span></span>
                <span class="line-mask"><span class="editorial-highlight">delivered to your door.</span></span>
            </h1>

            <p class="reveal mt-7 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Everything you'd find on our shelves — pharmacist-approved, dispatched from our dispensing
                hub, and free to deliver on orders over {{ config('pharmacy.free_delivery_threshold') }}.
            </p>

            <div class="reveal mt-10" style="--reveal-delay:440ms">
                <x-btn href="#categories" data-magnetic>Browse all products</x-btn>
            </div>

            <div class="no-interact reveal mt-12 flex flex-wrap gap-x-6 gap-y-2 border-t border-white/15 pt-7 text-[11px] font-semibold uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:540ms">
                <span>Free delivery over {{ config('pharmacy.free_delivery_threshold') }}</span>
                <span>Same-day dispatch before 2pm</span>
                <span>Pharmacist advice on every order</span>
            </div>
        </div>
    </x-page-hero>

    <section id="categories" class="scroll-mt-24 border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Shop by category</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Find what <span class="editorial-highlight">you need.</span></h2>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3" data-reveal-group>
                @foreach (config('pharmacy.shop_categories') as $i => $cat)
                    <a href="{{ route('contact') }}"
                        class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover {{ $i === 0 ? 'lg:row-span-2' : '' }} {{ $i === 1 ? 'lg:col-span-2' : '' }}">
                        <img src="{{ asset('images/products.jpg') }}" alt=""
                            class="w-full rounded-2xl object-cover {{ $i === 0 ? 'h-56' : 'h-36' }}">
                        <div class="mt-5 flex flex-1 flex-col">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">{{ $cat['index'] }} · {{ $cat['eyebrow'] }}</p>
                            <h3 class="mt-2 text-2xl leading-tight">{{ $cat['name'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $cat['blurb'] }}</p>
                            <span class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-brand-moss">
                                Shop
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <p class="no-interact reveal mt-10 text-sm text-brand-stone">
                Also: {{ implode(' · ', config('pharmacy.shop_more')) }}
            </p>
        </div>
    </section>

    <section id="featured" class="no-interact scroll-mt-24 border-y border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Popular right now</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">What people are <span class="editorial-highlight">stocking up on.</span></h2>
            </div>

            <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach (config('pharmacy.featured_products') as $product)
                    <div class="reveal flex items-center gap-4 rounded-[20px] border border-brand-hairline bg-brand-ivory p-4">
                        <img src="{{ asset('images/products.jpg') }}" alt="" class="h-16 w-16 shrink-0 rounded-xl object-cover">
                        <div>
                            <p class="text-[15px] font-medium text-brand-forest">{{ $product }}</p>
                            <p class="mt-1 text-xs text-brand-stone-light">See price in shop</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="reveal mt-10 font-serif text-sm italic text-brand-stone-light">Tap Browse all products to shop.</p>
        </div>
    </section>

    <section id="delivery" class="scroll-mt-24 border-b border-brand-pistachio-line bg-brand-pistachio py-24 md:py-32">
        <div class="mx-auto grid max-w-[1280px] items-center gap-14 px-6 sm:px-8 lg:grid-cols-2">
            <div class="no-interact reveal">
                <x-eyebrow>Delivery promise</x-eyebrow>
                <h2 class="mt-3 text-[32px] leading-tight md:text-[42px]">Delivered by <span class="editorial-highlight">people you know.</span></h2>
                <div class="mt-8">
                    @foreach ([
                        'Our own drivers, not a third-party courier',
                        'Text updates from dispatch to door',
                        'A pharmacist on hand for advice about anything you order',
                    ] as $row)
                        <p class="border-b border-brand-pistachio-line py-4 text-[15px] text-brand-forest last:border-0">{{ $row }}</p>
                    @endforeach
                </div>
            </div>
            <div class="reveal">
                <div class="overflow-hidden rounded-3xl shadow-editorial-card">
                    <img src="{{ asset('images/delivery-van.jpg') }}" alt="A pharmacy delivery van outside a terraced house" class="h-72 w-full object-cover">
                </div>
                <div class="mt-8 text-center">
                    <x-btn :href="route('sign-in')" variant="outline" class="group">Track an order</x-btn>
                </div>
            </div>
        </div>
    </section>

    <section class="no-interact bg-brand-ivory py-24 md:py-32">
        <figure class="reveal mx-auto max-w-3xl px-6 text-center">
            <blockquote class="font-serif text-3xl italic leading-snug text-brand-forest">
                “Ordered on a Tuesday morning, on my doorstep the same afternoon. Their own driver, too — not a courier who leaves it in a bin.”
            </blockquote>
            <figcaption class="mt-8 flex items-center justify-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-forest text-sm font-semibold text-white">R</span>
                <span class="text-left">
                    <span class="block text-sm font-semibold text-brand-forest">Ruth B.</span>
                    <span class="block text-xs text-brand-stone-light">Khaira branch</span>
                </span>
            </figcaption>
            <p class="mt-10 font-serif text-2xl text-brand-forest">{{ config('pharmacy.reviews.rating') }} · {{ config('pharmacy.reviews.count') }} reviews</p>
        </figure>
    </section>
</x-layout>
