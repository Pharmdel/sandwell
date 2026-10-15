@php
    $articles = collect(config('pharmacy.articles'));
    $featured = $articles->firstWhere('featured', true);
    $rest = $articles->where('featured', '!==', true)->values();
@endphp

<x-layout title="Health Hub" hero description="Plain-English health guidance from our pharmacists — seasonal, practical and free.">
    <x-page-hero image="consult-room.jpg" alt="A pharmacist talking with a patient">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">Health Hub</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[66px]" data-lines>
                <span class="line-mask"><span>Health advice you can</span></span>
                <span class="line-mask"><span class="editorial-highlight">actually use.</span></span>
            </h1>

            <p class="reveal mt-7 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:340ms">
                Plain-English guidance from our pharmacists — seasonal, practical and free.
            </p>
        </div>
    </x-page-hero>

    <section class="py-16 md:py-24">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            {{-- Featured --}}
            <a href="{{ route('health-hub.show', $featured['slug']) }}"
                class="reveal tilt group grid overflow-hidden rounded-[28px] border border-brand-hairline bg-white shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover lg:grid-cols-2">
                <img src="{{ asset('images/flu-vaccine.jpg') }}" alt="" class="h-72 w-full object-cover lg:h-full">
                <div class="flex flex-col justify-center p-8 md:p-12">
                    <x-chip tone="peach" class="self-start">{{ $featured['chip'] }}</x-chip>
                    <h2 class="mt-4 text-3xl leading-tight md:text-[36px]">
                        Flu jabs are back. <span class="editorial-highlight">Who should get one, and when.</span>
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $featured['blurb'] }}</p>
                    <p class="mt-5 text-xs text-brand-stone-light">By our pharmacy team · {{ $featured['read'] }}</p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss">
                        Read article
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                    </span>
                </div>
            </a>
        </div>
    </section>

    <section id="latest" class="scroll-mt-24 border-t border-brand-hairline bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Latest</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Fresh from <span class="editorial-highlight">the pharmacy.</span></h2>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach ($rest as $i => $article)
                    <a href="{{ route('health-hub.show', $article['slug']) }}"
                        class="reveal tilt group flex flex-col overflow-hidden rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover {{ $i === 0 ? 'lg:row-span-2' : '' }}">
                        <img src="{{ asset('images/'.['weightloss-outdoor.jpg','consult-room.jpg','products.jpg','delivery-van.jpg','mother-toddler.jpg'][$i]) }}"
                            alt="" class="w-full rounded-2xl object-cover {{ $i === 0 ? 'h-56' : 'h-36' }}">
                        <div class="mt-5 flex flex-1 flex-col">
                            <x-chip tone="quiet" class="self-start">{{ $article['chip'] }}</x-chip>
                            <h3 class="mt-3 text-2xl leading-snug">{{ $article['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $article['blurb'] }}</p>
                            <p class="mt-auto pt-5 text-xs text-brand-stone-light">{{ $article['read'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="weight-loss" class="scroll-mt-24 border-t border-brand-hairline bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="max-w-2xl">
                    <x-eyebrow>Weight loss guides</x-eyebrow>
                    <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">Everything about <span class="editorial-highlight">weight loss treatment.</span></h2>
                    <p class="mt-4 text-base leading-relaxed text-brand-stone">Pharmacist-written guides to every treatment we offer — how each one works, what it costs, and how to handle the side effects.</p>
                </div>
                <a href="{{ route('weight-loss') }}" class="shrink-0 text-sm font-semibold text-brand-moss hover:underline">Visit the weight loss clinic →</a>
            </div>
            <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach (config('weightloss.guides') as $g)
                    <a href="{{ route('health-hub.show', $g['slug']) }}" class="reveal tilt group flex flex-col rounded-[20px] border border-brand-hairline bg-brand-ivory p-6 transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover">
                        <x-chip tone="quiet" class="self-start">{{ $g['chip'] }}</x-chip>
                        <h3 class="mt-3 text-lg leading-snug">{{ $g['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $g['blurb'] }}</p>
                        <p class="mt-auto pt-5 text-xs text-brand-stone-light">{{ $g['read'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="tools" class="no-interact scroll-mt-24 border-y border-brand-pistachio-line bg-brand-pistachio py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="grid gap-6 md:grid-cols-2" data-reveal-group>
                <div class="reveal rounded-[20px] border border-brand-pistachio-line bg-white p-8">
                    <h2 class="text-[26px] leading-tight">Medicines A–Z</h2>
                    <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                        Plain-English guides to common medicines, side effects and how to take them.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach (range('A', 'H') as $letter)
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-forest/5 text-sm font-medium text-brand-forest">{{ $letter }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="reveal rounded-[20px] border border-brand-pistachio-line bg-white p-8">
                    <h2 class="text-[26px] leading-tight">FAQs</h2>
                    <p class="mt-3 text-sm leading-relaxed text-brand-stone">
                        Prescriptions, delivery, nominations and more.
                    </p>
                    <div class="mt-6 space-y-3">
                        @foreach (['How long does a repeat take?', 'Can I still collect in branch?', 'Do you deliver controlled medicines?'] as $q)
                            <p class="border-b border-brand-hairline pb-3 text-sm text-brand-stone-light last:border-0">{{ $q }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <p class="reveal mt-8 text-center font-serif text-sm italic text-brand-stone-light">Open from the menu.</p>
        </div>
    </section>

    <x-final-cta :href="route('book')" action="Book an appointment"
        sub="Reading only gets you so far — our pharmacists can assess you properly, usually the same day.">
        Advice is good. <span class="editorial-highlight">A pharmacist is better.</span>
    </x-final-cta>
</x-layout>
