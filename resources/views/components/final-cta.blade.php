@props(['sub' => null, 'action' => null, 'href' => '#'])

<section class="grain relative overflow-hidden bg-brand-navy-deep py-24 text-center md:py-32">
    <div class="pointer-events-none absolute left-1/2 top-1/2 h-[600px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[radial-gradient(circle,rgba(42,49,112,0.55),transparent_70%)]"></div>

    <div class="reveal relative mx-auto max-w-3xl px-6">
        <h2 class="text-4xl leading-tight text-white md:text-[52px]">{{ $slot }}</h2>
        @if ($sub)
            <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-slate-300">{{ $sub }}</p>
        @endif
        @if ($action)
            <x-btn :href="$href" class="group mt-9">{{ $action }}</x-btn>
        @endif
    </div>
</section>
