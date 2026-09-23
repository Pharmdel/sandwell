@php
    $related = collect(config('pharmacy.articles'))->merge(config('weightloss.guides'))->where('slug', '!==', $article['slug'])->shuffle()->take(3);
    $isGuide = collect(config('weightloss.guides'))->contains('slug', $article['slug']);
@endphp

<x-layout :title="$article['title']" hero :description="$article['blurb']">
    <article>
        <x-page-hero :image="$article['image']" alt="">
            <div class="max-w-3xl">
                <div class="reveal"><x-chip tone="orange">{{ $article['chip'] }}</x-chip></div>

                <h1 class="mt-6 text-4xl leading-[1.08] text-white md:text-[58px]" data-lines>
                    <span class="line-mask"><span>{{ $article['title'] }}</span></span>
                </h1>

                <p class="reveal mt-7 max-w-xl text-xl leading-relaxed text-white/80" style="--reveal-delay:300ms">{{ $article['blurb'] }}</p>

                <p class="no-interact reveal mt-9 border-t border-white/15 pt-6 text-xs uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:400ms">
                    By our pharmacy team
                    @if (! empty($article['reviewed'])) · {{ $article['reviewed'] }} @endif
                    @if (! empty($article['updated'])) · Updated {{ $article['updated'] }} @endif
                    · {{ $article['read'] }}
                </p>
            </div>
        </x-page-hero>

        <div class="mx-auto max-w-3xl px-6 py-20 md:py-24">
            @foreach ($article['lead'] ?? [] as $para)
                <p class="reveal mb-6 text-xl leading-[1.7] text-brand-forest">{{ $para }}</p>
            @endforeach

            @foreach ($article['body'] as $section)
                <section class="reveal mb-12 mt-14 first:mt-0 last:mb-0">
                    <h2 class="text-[28px] leading-snug">{{ $section[0] }}</h2>
                    @foreach (preg_split('/\n\s*\n/', $section[1]) as $para)
                        <p class="mt-4 text-lg leading-[1.7] text-brand-stone">{{ $para }}</p>
                    @endforeach
                </section>
            @endforeach

            @if (! empty($article['cta']))
                <aside class="reveal mt-14 rounded-[24px] bg-brand-forest p-8 text-white md:p-10">
                    <h2 class="text-[26px] leading-snug text-white">{{ $article['cta'][0] }}</h2>
                    <p class="mt-3 text-sm text-white/75">Free suitability check · Free consultation call · Nothing to pay until you begin treatment · {{ config('pharmacy.phone') }}</p>
                    <x-btn :href="route('weight-loss')" class="mt-6">{{ $article['cta'][1] }}</x-btn>
                </aside>
            @endif

            @if (! empty($article['faqs']))
                <section class="reveal mt-16">
                    <h2 class="text-[28px] leading-snug">Frequently asked questions</h2>
                    <div class="mt-6 divide-y divide-brand-hairline border-y border-brand-hairline">
                        @foreach ($article['faqs'] as [$q, $a])
                            <details class="wl-faq group py-1">
                                <summary class="flex items-center justify-between gap-6 py-4 text-lg font-medium text-brand-forest">
                                    {{ $q }}
                                    <span class="wl-faq__icon flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-brand-hairline text-brand-moss transition-transform duration-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg>
                                    </span>
                                </summary>
                                <div class="pb-5 pr-14 text-[15px] leading-relaxed text-brand-stone">{{ $a }}</div>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endif

            <aside class="no-interact reveal mt-16 rounded-[20px] border border-brand-hairline bg-white p-8 shadow-editorial-card">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">A note on this article</p>
                <p class="mt-4 text-sm leading-relaxed text-brand-stone">
                    This is general information, not personal medical advice. If something about your own
                    health is worrying you, speak to a pharmacist at any branch or call
                    <span class="font-semibold text-brand-forest">{{ config('pharmacy.phone') }}</span>.
                    For urgent medical advice, call 111.
                </p>
            </aside>
        </div>
    </article>

    <section class="border-y border-brand-hairline bg-brand-ivory py-20 md:py-24">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal">
                <x-eyebrow>Keep reading</x-eyebrow>
                <h2 class="mt-3 text-[32px] leading-tight md:text-[38px]">More from <span class="editorial-highlight">the Health Hub.</span></h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3" data-reveal-group>
                @foreach ($related as $other)
                    <a href="{{ route('health-hub.show', $other['slug']) }}"
                        class="reveal tilt group flex flex-col rounded-[20px] border border-brand-hairline bg-white p-6 shadow-editorial-card transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover">
                        <img src="{{ asset('images/'.$other['image']) }}" alt="" class="h-36 w-full rounded-2xl object-cover">
                        <x-chip tone="quiet" class="mt-5 self-start">{{ $other['chip'] }}</x-chip>
                        <h3 class="mt-3 text-xl leading-snug">{{ $other['title'] }}</h3>
                        <p class="mt-auto pt-5 text-xs text-brand-stone-light">{{ $other['read'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <x-final-cta :href="$isGuide ? route('weight-loss') : route('book')" :action="$isGuide ? 'Start your free suitability check' : 'Book an appointment'"
        sub="Our pharmacists can assess you properly, usually the same day.">
        Advice is good. <span class="editorial-highlight">A pharmacist is better.</span>
    </x-final-cta>
</x-layout>
