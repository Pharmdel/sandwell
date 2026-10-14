{{--
    Shared shell for every error page.

    Deliberately standalone: it does not extend the site layout, because the layout
    reads the CSRF token and the session, and an error page has to render even when
    the thing that broke is the session store or the database. It needs only the
    compiled stylesheet and the static config.
--}}
@php
    $phone = config('pharmacy.phone', '0121 500 5756');
    $phoneHref = config('pharmacy.phone_href', 'tel:01215005756');
@endphp
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#88e788">
    <title>{{ trim($__env->yieldContent('title')) }} — Hollytree Pharmacy</title>
    <link rel="icon" href="{{ asset('images/logo-nav.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400;1,6..72,500&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-brand-primary font-sans antialiased">
    <main class="grain relative flex min-h-screen flex-col bg-brand-primary [--highlight:#fff]">
        <header class="mx-auto flex w-full max-w-[1280px] items-center px-6 py-6 sm:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3.5 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-forest" aria-label="Hollytree Pharmacy — home">
                <img src="{{ asset('images/logo-nav.png') }}" alt="" width="48" height="48" class="h-12 w-12">
                <span class="wordmark text-[18px]">
                    <span class="wordmark__name text-brand-forest">HOLLYTREE</span>
                    <span class="wordmark__sub text-brand-moss-hover">Pharmacy</span>
                </span>
            </a>
        </header>

        <section class="mx-auto flex w-full max-w-[1280px] flex-1 flex-col justify-center px-6 pb-20 sm:px-8">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-moss-hover">Error {{ trim($__env->yieldContent('code')) }}</p>
            <h1 class="mt-4 max-w-3xl text-5xl leading-[1.04] text-brand-forest md:text-[68px]">
                @yield('heading')
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-brand-stone-dark">
                @yield('message')
            </p>

            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center rounded-full bg-brand-forest px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-brand-moss focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-forest focus-visible:ring-offset-2 focus-visible:ring-offset-brand-primary">
                    Back to the homepage
                </a>
                <a href="{{ $phoneHref }}"
                    class="inline-flex items-center justify-center rounded-full border border-brand-forest/40 bg-white/55 px-7 py-3.5 text-sm font-semibold text-brand-forest backdrop-blur transition hover:border-brand-forest hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-forest">
                    Call {{ $phone }}
                </a>
            </div>

            @hasSection('links')
                <ul class="mt-12 flex flex-wrap gap-x-6 gap-y-2 border-t border-brand-forest/20 pt-6 text-sm font-semibold text-brand-forest">
                    @yield('links')
                </ul>
            @endif
        </section>
    </main>
</body>
</html>
