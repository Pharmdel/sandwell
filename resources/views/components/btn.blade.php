@props(['href' => '#', 'variant' => 'primary', 'arrow' => false])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-full px-7 py-3.5 text-sm font-semibold transition-all duration-200 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $variants = [
        'primary' => 'bg-brand-moss text-white hover:bg-brand-moss-hover hover:-translate-y-0.5 hover:shadow-moss-glow focus-visible:ring-brand-moss',
        'outline' => 'border border-brand-forest text-brand-forest hover:bg-brand-forest hover:text-white focus-visible:ring-brand-forest',
        'light' => 'border border-white/25 text-white hover:bg-white hover:text-brand-forest focus-visible:ring-white',
        'white' => 'border border-brand-hairline bg-white text-brand-forest hover:border-brand-forest focus-visible:ring-brand-forest',
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
