@props(['tone' => 'peach'])

@php
    $tones = [
        'nhs' => 'bg-brand-nhs-blue text-white',
        'peach' => 'bg-brand-pistachio text-brand-moss-hover',
        'navy' => 'bg-brand-forest text-white',
        'orange' => 'bg-brand-moss text-white',
        'quiet' => 'bg-brand-forest/5 text-brand-forest',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.08em]', $tones[$tone]]) }}>
    {{ $slot }}
</span>
