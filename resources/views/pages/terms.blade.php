@php $branches = config('pharmacy.branches'); @endphp

<x-layout title="Terms of Service" hero description="The terms that apply when you use Hollytree Pharmacy website and services, with our registered premises and superintendent pharmacists.">
    <x-page-hero image="team.jpg" alt="The pharmacy team" size="short">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-moss">Legal</p>
            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[62px]" data-lines>
                <span class="line-mask"><span>Terms of <span class="editorial-highlight">service.</span></span></span>
            </h1>
            <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:300ms">
                The terms that apply when you use our website and services.
            </p>
            <p class="reveal mt-4 text-xs uppercase tracking-[0.1em] text-white/60" style="--reveal-delay:380ms">Last updated: 16 August 2026</p>
        </div>
    </x-page-hero>

    <article class="mx-auto max-w-3xl px-6 py-20 md:py-24">
        <section id="about-us" class="reveal scroll-mt-28">
            <h2 class="text-[28px] leading-snug">About us</h2>
            <p class="mt-4 text-lg leading-[1.7] text-brand-stone">
                This website is operated by Hollytree Pharmacy, a group of six registered pharmacies.
                Each branch is operated by the company shown below, and each is separately registered with the
                General Pharmaceutical Council.
            </p>
        </section>

        <section id="companies" class="reveal mt-12 scroll-mt-28">
            <h2 class="text-[22px] leading-snug">Registered premises, superintendent pharmacists and operating companies</h2>
            <div class="mt-6 overflow-x-auto rounded-[20px] border border-brand-hairline bg-white shadow-editorial-card">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-brand-ivory text-[11px] uppercase tracking-[0.08em] text-brand-stone-light">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Pharmacy</th>
                            <th class="px-5 py-3 font-semibold">Address</th>
                            <th class="px-5 py-3 font-semibold">GPhC premises</th>
                            <th class="px-5 py-3 font-semibold">Superintendent pharmacist</th>
                            <th class="px-5 py-3 font-semibold">Operating company</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-hairline">
                        @foreach ($branches as $b)
                            <tr>
                                <td class="px-5 py-4 font-semibold text-brand-forest">{{ $b['name'] }}</td>
                                <td class="px-5 py-4 text-brand-stone">{{ $b['address'] }} {{ $b['postcode'] }}</td>
                                <td class="px-5 py-4 text-brand-stone">{{ $b['gphc_premises'] }}</td>
                                <td class="px-5 py-4 text-brand-stone">{{ $b['superintendent'] }}<br><span class="text-xs text-brand-stone-light">GPhC {{ $b['superintendent_gphc'] }}</span></td>
                                <td class="px-5 py-4 text-brand-stone">{{ $b['company'] }}<br><span class="text-xs text-brand-stone-light">No. {{ $b['company_no'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-5 text-sm text-brand-stone">By using this website, our online shop or our app, you agree to these terms.</p>
        </section>

        @foreach ([
            ['Health information on this site', [
                'The health articles, condition pages and symptom checkers on this website are for general information only. They are not a diagnosis and are no substitute for a consultation with a pharmacist, doctor or nurse who knows your history.',
                'If you are seriously unwell, call 999. For urgent advice, call NHS 111. Never delay seeking medical help because of something you have read here.',
                'Our online eligibility checkers — for Pharmacy First, flu and Covid vaccinations, blood pressure services and travel vaccines — give an indication only. Final eligibility is always confirmed by a pharmacist, and being told you may be eligible online does not guarantee we can treat you.',
            ]],
            ['Using this website', [
                'You agree to use the site lawfully, and not to attempt to gain unauthorised access to any part of it, interfere with its operation, or use automated systems to extract data from it. We monitor and rate limit sign-in attempts to protect accounts.',
            ]],
            ['Accounts', [
                'You must be 16 or over to create an account, and the details you give us must be accurate. You’re responsible for keeping your password confidential; please choose one you don’t use anywhere else. Tell us straight away if you think someone else has access to your account. We may suspend or close an account where we suspect fraud, misuse, or attempts to obtain medicines inappropriately.',
            ]],
            ['The online shop', [
                'Purchases from our shop are governed by our shop terms and conditions, which cover ordering, pricing, delivery, pharmacy medicines and payment. Your cancellation and return rights are in our returns and cancellations policy.',
                'In short: a contract forms when we confirm your order after payment succeeds; we may decline an order where a pharmacist judges a product unsuitable; and medicines cannot be returned for resale once they have left the pharmacy.',
            ]],
            ['NHS services', [
                'NHS services including EPS nomination, Pharmacy First, vaccinations and the contraception service are subject to eligibility criteria set by NHS England. We must decline where those criteria aren’t met — that isn’t our choice. NHS services are free at the point of use, though standard prescription charges may apply.',
            ]],
            ['Private services and appointments', [
                'Private services including our weight loss clinic, travel vaccinations, B12 injections and private consultations are subject to a clinical assessment. We may decline to supply where it wouldn’t be safe or appropriate, and we will always explain why.',
                'Where you book an appointment online, please use the link in your confirmation email to change or cancel it if your plans change, so the slot can go to someone else. We may cancel or move appointments where staffing, stock or circumstances require, and we’ll contact you if that happens.',
            ]],
            ['Our app', [
                'This website can be installed to your device as an app. It is the same service in a different wrapper, and these terms apply equally. Some pages you have already visited remain available offline for convenience; offline content may not be up to date, so always reconnect before relying on it.',
            ]],
            ['Prices and availability', [
                'We try to keep prices and stock information accurate, but errors happen. Where an item is listed at an obviously incorrect price we will contact you before dispatch rather than simply charging you.',
            ]],
            ['Intellectual property', [
                'The content, design and branding of this website belong to us or our licensors and may not be copied or reused commercially without permission. You’re welcome to print or save pages for your own personal use.',
            ]],
            ['Links to other sites', [
                'We link to NHS and other reputable sources for your convenience. We’re not responsible for the content of external sites.',
            ]],
            ['Our liability', [
                'We do not exclude or limit our liability where it would be unlawful to do so — including for death or personal injury caused by our negligence, for fraud, or for your legal rights in relation to products we supply. Subject to that, we’re not liable for loss that was not foreseeable, or for business losses, as our services are provided for personal use.',
                'We aim to keep the website available at all times but cannot guarantee uninterrupted access.',
            ]],
            ['Complaints', [
                'If something goes wrong we want to hear about it — ask to speak to the superintendent pharmacist at any branch, or use the contact page, and we will tell you what happens next.',
            ]],
            ['Changes and governing law', [
                'We may update these terms as our services change; the date at the top shows when. These terms are governed by the law of England and Wales.',
            ]],
        ] as [$heading, $paras])
            <section id="{{ str($heading)->slug() }}" class="reveal mt-12 scroll-mt-28">
                <h2 class="text-[28px] leading-snug">{{ $heading }}</h2>
                @foreach ($paras as $p)
                    <p class="mt-4 text-lg leading-[1.7] text-brand-stone">{{ $p }}</p>
                @endforeach
            </section>
        @endforeach

        <p class="no-interact reveal mt-14 border-t border-brand-hairline pt-6 text-sm text-brand-stone-light">
            See also: privacy policy · cookie policy · data protection policy · shop terms · returns.
        </p>
    </article>
</x-layout>
