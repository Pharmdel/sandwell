@props([
    'title' => null,
    'description' => 'Six NHS community pharmacies across Sandwell and Stourbridge. NHS and private care, same-day pharmacist consultations, and free prescription delivery.',
    'hero' => false,
])

<!DOCTYPE html>
<html lang="en-GB" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' — Sandwell Pharmacy Group' : 'Sandwell Pharmacy Group — Your local pharmacy, without the wait' }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="icon" href="{{ asset('images/logo-nav.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500;1,6..72,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Without JS the scroll-reveal never runs, so every animated element would stay invisible. --}}
    <noscript>
        <style>
            .reveal, .line-mask > span, .clip-reveal > img {
                opacity: 1 !important;
                transform: none !important;
                clip-path: none !important;
            }
            .editorial-highlight::after { --underline-scale: 1; }
        </style>
    </noscript>
</head>
<body class="bg-brand-ivory font-sans antialiased">
    <div id="scroll-progress"></div>

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-[70] focus:m-4 focus:rounded-full focus:bg-brand-navy focus:px-5 focus:py-2.5 focus:text-sm focus:text-white">Skip to content</a>

    <x-site-header :overlay="$hero === true" :overlay-light="$hero === 'light'" />
    <x-site-menu />

    {{-- The Menu button needs JS to open, so give non-JS visitors a plain nav. --}}
    <noscript>
        <nav aria-label="Site navigation" class="relative z-30 border-b border-brand-hairline bg-white px-6 py-5 pt-24 sm:px-8">
            <ul class="mx-auto flex max-w-[1280px] flex-wrap gap-x-6 gap-y-2 text-sm text-brand-navy">
                @foreach ([
                    ['Home', route('home')],
                    ['NHS services', route('nhs-services')],
                    ['Private services', route('private-services')],
                    ['Pharmacy First', route('pharmacy-first')],
                    ['Weight loss clinic', route('weight-loss')],
                    ['Repeat prescriptions', route('repeat-prescriptions')],
                    ['Branches', route('branches')],
                    ['Shop', route('shop')],
                    ['Health Hub', route('health-hub')],
                    ['Book', route('book')],
                    ['Contact', route('contact')],
                    ['Sign in', route('sign-in')],
                ] as [$label, $href])
                    <li><a href="{{ $href }}" class="underline decoration-brand-hairline underline-offset-4 hover:decoration-brand-orange">{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>
    </noscript>

    <main id="main" @class(['pt-20' => ! $hero])>
        {{ $slot }}
    </main>

    <x-site-footer />
    @stack('scripts')
</body>
</html>
