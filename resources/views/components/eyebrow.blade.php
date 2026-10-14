@props(['tone' => 'accent'])

<p {{ $attributes->class(['text-[11px] font-bold uppercase tracking-[0.14em]', $tone === 'accent' ? 'text-brand-moss' : 'text-brand-stone-light']) }}>
    {{ $slot }}
</p>
