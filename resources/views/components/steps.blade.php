@props(['items' => []])

<div class="relative mt-16">
    <svg class="pointer-events-none absolute left-[12%] right-[12%] top-8 hidden h-px w-[76%] text-brand-moss md:block" aria-hidden="true">
        <line x1="0" y1="0.5" x2="100%" y2="0.5" stroke="currentColor" stroke-width="1.5" class="animate-connector" opacity="0.5" />
    </svg>

    @php $cols = ['3' => 'md:grid-cols-3', '4' => 'md:grid-cols-4'][count($items)] ?? 'md:grid-cols-3'; @endphp
    <div class="grid gap-10 {{ $cols }}" data-reveal-group>
        @foreach ($items as $i => $item)
            <div class="reveal relative text-center">
                <p class="mb-4 font-serif text-5xl text-brand-moss">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</p>
                <h3 class="mb-2 text-xl">{{ $item['title'] }}</h3>
                <p class="mx-auto max-w-xs text-sm leading-relaxed text-brand-stone">{{ $item['body'] }}</p>
            </div>
        @endforeach
    </div>
</div>
