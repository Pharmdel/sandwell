@props(['tone' => 'peach'])

@php
    $tones = [
        'nhs' => 'bg-brand-nhs-blue text-white',
        'peach' => 'bg-brand-peach text-brand-orange-hover',
        'navy' => 'bg-brand-navy text-white',
        'orange' => 'bg-brand-orange text-white',
        'quiet' => 'bg-brand-navy/5 text-brand-navy',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.08em]', $tones[$tone]]) }}>
    {{ $slot }}
</span>
