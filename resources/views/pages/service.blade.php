@php
    $isNhs = $service['group'] === 'nhs';
    $ctaRoute = $service['cta']['route'] ?? 'contact';
@endphp

<x-layout :title="$service['title']" :description="$service['description']" hero>
    @push('scripts') @vite('resources/js/services.js') @endpush
    <x-page-hero :image="$service['image']" alt="" size="short">
        <div class="max-w-3xl">
            <span class="reveal inline-flex items-center gap-2.5 rounded-full bg-white/95 py-1.5 pl-1.5 pr-4 shadow-editorial-card backdrop-blur">
                <span @class([
                    'rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.08em] text-white',
                    'bg-brand-nhs-blue' => $isNhs,
                    'bg-brand-moss' => ! $isNhs,
                ])>{{ $isNhs ? 'NHS service' : 'Private service' }}</span>
                <a href="{{ route('services') }}" class="text-xs font-semibold text-brand-forest hover:text-brand-moss sm:text-[13px]">All services</a>
            </span>

            <h1 class="mt-6 text-4xl leading-[1.06] text-white md:text-[62px]" data-lines>
                @php $h1 = $service['h1']; $hl = $service['highlight'] ?? null; @endphp
                @if ($hl && str_contains($h1, $hl))
                    <span class="line-mask"><span>{{ str($h1)->before($hl) }}<span class="editorial-highlight">{{ $hl }}</span></span></span>
                @else
                    <span class="line-mask"><span>{{ $h1 }}</span></span>
                @endif
            </h1>

            <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:280ms">{{ $service['intro'] }}</p>

            <ul class="no-interact reveal mt-8 flex flex-wrap gap-x-6 gap-y-2" style="--reveal-delay:380ms">
                @foreach ($service['badges'] as $badge)
                    <li class="flex items-center gap-2 text-sm text-white/75">
                        <svg class="h-4 w-4 shrink-0 text-brand-moss" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $badge }}
                    </li>
                @endforeach
            </ul>
        </div>
    </x-page-hero>

    @isset($service['how'])
        <section class="no-interact border-b border-brand-hairline bg-brand-ivory py-24 md:py-28">
            <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
                <div class="reveal max-w-2xl">
                    <x-eyebrow>Step by step</x-eyebrow>
                    <h2 class="mt-3 text-4xl leading-tight md:text-[40px]">{{ $service['how']['heading'] }}</h2>
                    <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $service['how']['lead'] }}</p>
                </div>
                <div class="mt-12 grid gap-6 md:grid-cols-3" data-reveal-group>
                    @foreach ($service['how']['steps'] as $i => [$title, $body])
                        <div class="reveal rounded-[20px] border border-brand-hairline bg-white p-7 shadow-editorial-card">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-forest font-serif text-lg text-white">{{ $i + 1 }}</span>
                            <h3 class="mt-5 text-xl leading-tight">{{ $title }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $body }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endisset

    @isset($service['items'])
        <section id="options" class="no-interact scroll-mt-24 border-b border-brand-hairline bg-white py-24 md:py-28">
            <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
                <div class="reveal max-w-2xl">
                    <x-eyebrow>{{ $service['items']['label'] }}</x-eyebrow>
                    <h2 class="mt-3 text-4xl leading-tight md:text-[40px]">{{ $service['items']['heading'] }}</h2>
                    <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $service['items']['lead'] }}</p>
                </div>
                <div class="mt-12 grid gap-5 md:grid-cols-2" data-reveal-group>
                    @foreach ($service['items']['list'] as $item)
                        <div class="reveal flex flex-col rounded-[20px] border border-brand-hairline bg-brand-ivory p-7">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-xl leading-tight">{{ $item['name'] }}</h3>
                                @isset($item['price'])
                                    <span class="shrink-0 rounded-full bg-white px-3.5 py-1.5 text-xs font-semibold text-brand-forest shadow-editorial-card">{{ $item['price'] }}</span>
                                @endisset
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $item['body'] }}</p>
                            @isset($item['bullets'])
                                <ul class="mt-4 space-y-2 border-t border-brand-hairline pt-4">
                                    @foreach ($item['bullets'] as $b)
                                        <li class="flex gap-2.5 text-sm leading-relaxed text-brand-stone">
                                            <span class="mt-[7px] h-1.5 w-1.5 shrink-0 rounded-full bg-brand-moss"></span>{{ $b }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endisset
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endisset

    @isset($service['destinations'])
        <section class="no-interact border-b border-brand-pistachio-line bg-brand-pistachio py-24 md:py-28">
            <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
                <div class="reveal max-w-2xl">
                    <x-eyebrow>Destinations</x-eyebrow>
                    <h2 class="mt-3 text-4xl leading-tight md:text-[40px]">{{ $service['destinations']['heading'] }}</h2>
                    <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $service['destinations']['lead'] }}</p>
                </div>
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                    @foreach ($service['destinations']['list'] as $d)
                        <div class="reveal flex flex-col rounded-[20px] border border-brand-pistachio-line bg-white p-6">
                            <h3 class="text-[17px] leading-snug">{{ $d['name'] }}</h3>
                            <p class="mt-2.5 text-sm leading-relaxed text-brand-stone">{{ $d['body'] }}</p>
                            <ul class="mt-4 space-y-1.5 border-t border-brand-hairline pt-4">
                                @foreach ($d['bullets'] as $b)
                                    <li class="text-[13px] text-brand-forest">{{ $b }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endisset

    @isset($service['about'])
        <section class="no-interact border-b border-brand-hairline bg-brand-ivory py-24 md:py-28">
            <div class="mx-auto max-w-[760px] px-6 sm:px-8">
                <h2 class="reveal text-4xl leading-tight md:text-[40px]">{{ $service['about']['heading'] }}</h2>
                <div class="mt-7 space-y-5" data-reveal-group>
                    @foreach ($service['about']['paras'] as $para)
                        <p class="reveal text-base leading-relaxed text-brand-stone">{{ $para }}</p>
                    @endforeach
                </div>
            </div>
        </section>
    @endisset

    @if (isset($service['notes']) || isset($service['eligibility']))
        <section class="no-interact border-b border-brand-hairline bg-white py-24 md:py-28">
            <div class="mx-auto grid max-w-[1280px] gap-10 px-6 sm:px-8 lg:grid-cols-2 lg:gap-16">
                @foreach (array_filter([$service['notes'] ?? null, $service['eligibility'] ?? null]) as $panel)
                    <div class="reveal rounded-[20px] border border-brand-hairline bg-brand-ivory p-8">
                        <h2 class="text-2xl leading-tight">{{ $panel['heading'] }}</h2>
                        <ul class="mt-5 space-y-3">
                            @foreach ($panel['list'] as $line)
                                <li class="flex gap-3 text-[15px] leading-relaxed text-brand-stone">
                                    <span class="mt-[9px] h-1.5 w-1.5 shrink-0 rounded-full bg-brand-moss"></span>{{ $line }}
                                </li>
                            @endforeach
                        </ul>
                        @isset($panel['foot'])
                            <p class="mt-5 border-t border-brand-hairline pt-5 text-sm italic leading-relaxed text-brand-stone-light">{{ $panel['foot'] }}</p>
                        @endisset
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @isset($service['related'])
        <section class="border-b border-brand-hairline bg-brand-ivory py-16">
            <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
                <x-eyebrow class="reveal">Related</x-eyebrow>
                <div class="mt-5 flex flex-wrap gap-3" data-reveal-group>
                    @foreach ($service['related'] as [$label, $routeName, $param])
                        <a href="{{ $param ? route($routeName, $param) : route($routeName) }}"
                            class="reveal group inline-flex items-center gap-2 rounded-full border border-brand-hairline bg-white px-5 py-2.5 text-sm font-medium text-brand-forest transition-all duration-200 hover:border-brand-forest hover:bg-brand-forest hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                            {{ $label }}
                            <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endisset

    @isset($service['checker'])
        @if (($service['checker']['type'] ?? 'quiz') === 'eligibility')
            <section id="check" class="scroll-mt-24 border-b border-brand-hairline bg-white py-24 md:py-28">
                <div class="mx-auto max-w-[680px] px-6 sm:px-8">
                    <div class="reveal text-center">
                        <x-eyebrow>Quick check</x-eyebrow>
                        <h2 class="mt-3 text-4xl leading-tight md:text-[40px]">{{ $service['checker']['heading'] }}</h2>
                        <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $service['checker']['lead'] }}</p>
                    </div>

                    <div data-flu-check class="reveal mt-10 rounded-[24px] border border-brand-hairline bg-brand-ivory p-7 shadow-editorial-card sm:p-9">
                        <label for="flu-age" class="block text-[15px] font-semibold text-brand-forest">{{ $service['checker']['age_label'] }}</label>
                        <input id="flu-age" data-flu-age type="number" min="0" max="120" inputmode="numeric" placeholder="Age in years"
                            class="mt-2 w-full rounded-xl border border-brand-hairline bg-white px-4 py-3 text-[15px] text-brand-forest placeholder:text-brand-stone-light focus:border-brand-moss focus:outline-none focus:ring-2 focus:ring-brand-moss/25">

                        <p class="mt-7 text-[15px] font-semibold text-brand-forest">{{ $service['checker']['reasons_label'] }}</p>
                        <div class="mt-3 space-y-2">
                            @foreach ($service['checker']['reasons'] as $reason)
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-brand-hairline bg-white p-4 transition-colors duration-200 hover:border-brand-moss">
                                    <input type="checkbox" data-flu-reason="{{ $reason['key'] }}"
                                        class="mt-0.5 h-4 w-4 shrink-0 rounded border-brand-hairline text-brand-moss focus:ring-brand-moss">
                                    <span class="text-[14px] leading-snug text-brand-forest">{{ $reason['label'] }}</span>
                                </label>
                            @endforeach
                        </div>

                        <button type="button" data-flu-run
                            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-moss px-6 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-brand-moss-hover hover:shadow-moss-glow active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                            Check my eligibility
                        </button>

                        <div data-flu-result class="mt-6"></div>
                    </div>
                </div>
            </section>
        @else
        <section id="check" class="scroll-mt-24 border-b border-brand-hairline bg-white py-24 md:py-28">
            <div class="mx-auto max-w-[640px] px-6 sm:px-8">
                <div class="reveal text-center">
                    <x-eyebrow>Quick check</x-eyebrow>
                    <h2 class="mt-3 text-4xl leading-tight md:text-[40px]">{{ $service['checker']['heading'] }}</h2>
                    <p class="mt-4 text-base text-brand-stone">{{ $service['checker']['lead'] }}</p>
                </div>

                @php $js = $service['checker']['js']; @endphp
                <div data-{{ $js }}-check class="reveal mt-10 rounded-[24px] border border-brand-hairline bg-brand-ivory p-7 shadow-editorial-card sm:p-9">
                    <div class="mb-7 flex justify-center gap-2">
                        @foreach (range(0, count($service['checker']['steps'])) as $i)
                            <span data-{{ $js }}-dot class="bp-dot h-1.5 w-8 rounded-full bg-brand-hairline transition-colors duration-300"></span>
                        @endforeach
                    </div>

                    @foreach ($service['checker']['steps'] as $step)
                        <div data-{{ $js }}-step="{{ $step['key'] }}" hidden>
                            <p class="text-lg font-semibold leading-snug text-brand-forest">{{ $step['question'] }}</p>
                            <div class="mt-5 space-y-3">
                                @foreach ($step['options'] as $option)
                                    <button type="button" data-{{ $js }}-option="{{ $option['value'] }}"
                                        class="bp-option w-full rounded-2xl border border-brand-hairline bg-white px-5 py-4 text-left transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-moss hover:shadow-editorial-card focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                                        <span class="block text-[15px] font-medium text-brand-forest">{{ $option['label'] }}</span>
                                        @isset($option['note'])
                                            <span class="mt-0.5 block text-[13px] text-brand-stone">{{ $option['note'] }}</span>
                                        @endisset
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div data-{{ $js }}-step="result" hidden>
                        <div data-{{ $js }}-result></div>
                        <button type="button" data-{{ $js }}-restart
                            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss hover:underline">
                            Start again
                        </button>
                    </div>

                    <button type="button" data-{{ $js }}-back
                        class="mt-6 inline-flex items-center gap-2 text-sm font-medium text-brand-stone transition hover:text-brand-forest">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                </div>
            </div>
        </section>
        @endif
    @endisset

    @isset($service['booking_form'])
        <x-service-form :slug="$service['booking_form']" :form="config('services.forms.'.$service['booking_form'])" />
    @endisset

    @isset($service['form'])
        <x-service-form :slug="$slug" :form="$service['form']" />
    @endisset

    <x-final-cta :href="route($ctaRoute)" :action="$service['cta']['label']" :sub="$service['cta']['body']">
        {{ $service['cta']['heading'] }}
    </x-final-cta>
</x-layout>
