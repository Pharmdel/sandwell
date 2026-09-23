@props(['overlay' => false, 'overlayLight' => false])

<header data-header @class([
    'fixed inset-x-0 top-0 z-40 border-b transition-all duration-500',
    'border-brand-hairline bg-brand-ivory/80 backdrop-blur-md',
    'is-overlay' => $overlay,
    'is-overlay-light' => $overlayLight,
    '[&.is-scrolled]:shadow-[0_1px_24px_-4px_rgb(37_41_85/0.1)]',
])>
    <div class="mx-auto flex h-20 max-w-[1280px] items-center justify-between px-6 sm:px-8">
        <a href="{{ route('home') }}"
            class="group flex items-center gap-3.5 rounded-full transition-transform duration-300 hover:scale-[1.03] active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss"
            aria-label="Hollytree Pharmacy — home">
            <span class="header-logo flex h-11 w-11 items-center justify-center rounded-full bg-white/0 transition-colors duration-500 sm:h-[52px] sm:w-[52px]">
                <img src="{{ asset('images/logo-nav.png') }}" alt="" width="52" height="52" class="h-11 w-11 sm:h-[52px] sm:w-[52px]">
            </span>
            <span class="wordmark text-[16px] sm:text-[19px]">
                <span class="wordmark__name header-word text-brand-forest transition-colors duration-500">HOLLYTREE</span>
                <span class="wordmark__sub text-brand-moss">Pharmacy</span>
            </span>
        </a>

        <div class="flex items-center gap-3">
            <button type="button" data-menu-open aria-controls="site-menu" aria-label="Menu" data-magnetic
                class="header-menu inline-flex items-center gap-2 rounded-full border border-brand-forest px-3.5 py-2.5 text-sm font-medium text-brand-forest transition-all duration-300 hover:bg-brand-forest hover:text-white active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss sm:px-5">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <span class="hidden sm:inline">Menu</span>
            </button>

            <a href="{{ config('pharmacy.phone_href') }}" data-magnetic
                class="inline-flex items-center gap-2 rounded-full bg-brand-moss px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:bg-brand-moss-hover hover:shadow-moss-glow active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="hidden sm:inline">Call {{ config('pharmacy.phone') }}</span>
                <span class="sm:hidden">Call</span>
            </a>
        </div>
    </div>
</header>
