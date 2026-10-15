@props(['image', 'alt' => '', 'align' => 'center', 'size' => 'full'])

@php
    // Form-led pages use the short variant so the form is not pushed off screen.
    $heights = ['full' => 'min-h-[100svh]', 'short' => 'min-h-[62svh]'];
@endphp

<section data-hero {{ $attributes->class(['relative flex flex-col overflow-hidden bg-brand-forest-deep', $heights[$size]]) }}>
    {{-- The photo covers the viewport, not the section's full height: this hero can run
         well past 100svh, and covering all of it would magnify the image enormously.
         Anything below is the navy ground the bottom gradient already fades into.

         `sizes` is deliberately larger than the box. object-cover scales by whichever
         axis crops most — here height — so the rendered image is ~1.5x the box width,
         and a plain 100vw would make the browser pick a file that is too small. --}}
    @php $base = str($image)->beforeLast('.'); @endphp
    <div class="hero-media absolute inset-x-0 -top-[6%] h-[min(112%,118svh)]" data-parallax="0.05">
        <img src="{{ asset('images/hero/'.$base.'-2600.jpg') }}"
            srcset="{{ asset('images/hero/'.$base.'-1600.jpg') }} 1600w, {{ asset('images/hero/'.$base.'-2600.jpg') }} 2600w, {{ asset('images/hero/'.$base.'-3400.jpg') }} 3400w"
            sizes="(min-width: 768px) 150vw, 280vw" alt="{{ $alt }}"
            class="h-full w-full object-cover" fetchpriority="high" decoding="async">
    </div>

    <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-brand-forest-deep/95 via-brand-forest-deep/80 to-brand-forest-deep/35"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-[45%] bg-gradient-to-t from-brand-forest-deep via-brand-forest-deep/85 to-transparent"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-brand-forest-deep/70 to-transparent"></div>

    <div @class(['relative z-10 flex flex-1', $align === 'end' ? 'items-end' : 'items-center'])>
        <div class="mx-auto w-full max-w-[1280px] px-6 pb-16 pt-32 sm:px-8 md:pt-36">
            {{ $slot }}
        </div>
    </div>

    @isset($foot)
        <div class="relative z-10 w-full pb-10 md:pb-14">{{ $foot }}</div>
    @endisset

</section>
