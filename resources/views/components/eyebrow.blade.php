@props(['tone' => 'orange'])

<p {{ $attributes->class(['text-[11px] font-bold uppercase tracking-[0.14em]', $tone === 'orange' ? 'text-brand-orange' : 'text-brand-stone-light']) }}>
    {{ $slot }}
</p>
