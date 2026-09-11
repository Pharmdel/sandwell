@props(['href' => '#', 'variant' => 'primary', 'arrow' => false])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-full px-7 py-3.5 text-sm font-semibold transition-all duration-200 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $variants = [
        'primary' => 'bg-brand-orange text-white hover:bg-brand-orange-hover hover:-translate-y-0.5 hover:shadow-orange-glow focus-visible:ring-brand-orange',
        'outline' => 'border border-brand-navy text-brand-navy hover:bg-brand-navy hover:text-white focus-visible:ring-brand-navy',
        'light' => 'border border-white/25 text-white hover:bg-white hover:text-brand-navy focus-visible:ring-white',
        'white' => 'border border-brand-hairline bg-white text-brand-navy hover:border-brand-navy focus-visible:ring-brand-navy',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->class([$base, $variants[$variant]]) }}>
    {{ $slot }}
    @if ($arrow)
        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5" />
        </svg>
    @endif
</a>
