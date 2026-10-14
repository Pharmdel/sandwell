@props(['tone' => 'soft'])

@php
    $tones = [
        'nhs' => 'bg-brand-nhs-blue text-white',
        'soft' => 'bg-brand-pistachio text-brand-moss',
        'ink' => 'bg-brand-forest text-white',
        'primary' => 'bg-brand-primary text-brand-forest',
        'quiet' => 'bg-brand-forest/5 text-brand-forest',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.08em]', $tones[$tone]]) }}>
    {{ $slot }}
</span>
