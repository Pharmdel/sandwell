@php
    $c = $condition;
    $phone = config('pharmacy.phone');
    $free = in_array($c['tag'], ['Pharmacy First', 'Minor Ailments']);
    $btnPrimary = 'inline-flex items-center justify-center gap-2 rounded-full bg-brand-moss px-7 py-3.5 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-moss-hover hover:shadow-moss-glow active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss focus-visible:ring-offset-2';
    $input = 'w-full rounded-xl border border-brand-hairline bg-brand-ivory px-4 py-3 text-[15px] text-brand-forest transition placeholder:text-brand-stone-light focus:border-brand-forest focus:outline-none focus:ring-2 focus:ring-brand-forest/20';
    $label = 'mb-1.5 block text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light';
    $related = collect(config('conditions.list'))->where('slug', '!==', $c['slug'])->where('tag', $c['tag'])->shuffle()->take(4);
    $faqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]], $c['faqs'])];
@endphp

<x-layout :title="$c['name'].' — symptom check & treatment'" hero :description="$c['intro']">
    @push('scripts')
        <script type="application/json" id="condition-data">{!! json_encode(collect($c)->only(['slug', 'name', 'tag', 'min', 'max', 'sex', 'elig', 'flags', 'sym', 'dur', 'screening']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
        <script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
        @vite('resources/js/conditions.js')
    @endpush

    <x-page-hero image="mother-toddler.jpg" alt="A pharmacist in a consultation room" size="short">
        <div class="max-w-3xl">
            <div class="reveal flex flex-wrap items-center gap-2">
                <x-chip :tone="$c['tag'] === 'Pharmacy First' ? 'nhs' : ($free ? 'orange' : 'quiet')">{{ $c['tag'] }}</x-chip>
                <span class="text-[11px] font-semibold uppercase tracking-[0.1em] text-white/70">{{ $c['age_label'] }}{{ $free ? ' · free on the NHS' : '' }}</span>
            </div>
            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[62px]" data-lines>
                <span class="line-mask"><span>{{ $c['name'] }}</span></span>
            </h1>
            <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:300ms">{{ $c['intro'] }}</p>
            <ul class="reveal mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm font-medium text-white" style="--reveal-delay:380ms">
                @foreach ([$free ? 'Free NHS treatment' : 'Expert pharmacist advice', '1-minute symptom check', 'No GP appointment needed'] as $li)
                    <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-brand-moss"></span>{{ $li }}</li>
                @endforeach
            </ul>
            <div class="reveal mt-9" style="--reveal-delay:460ms">
                <a href="#symptom-test" class="{{ $btnPrimary }}" data-magnetic>Check my symptoms →</a>
            </div>
        </div>
    </x-page-hero>

    {{-- Symptom test --}}
    <section id="symptom-test" class="scroll-mt-24 border-b border-brand-hairline bg-brand-ivory py-20 md:py-24" data-phone="{{ $phone }}">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="reveal lg:col-span-7">
                    <div class="rounded-3xl border border-brand-hairline bg-white p-6 shadow-editorial-card md:p-10">
                        <div data-start-wrap>
                            <x-eyebrow>Symptom check</x-eyebrow>
                            <h2 class="mt-3 text-3xl leading-tight">Does this sound like <span class="editorial-highlight">{{ str($c['name'])->lower() }}?</span></h2>
                            <p class="mt-4 text-base leading-relaxed text-brand-stone">A quick check that asks what’s actually wrong first, confirms the symptoms fit, then checks age and safety — so if you genuinely have {{ str($c['name'])->lower() }} you’re walked towards treatment rather than filtered out. Under a minute.</p>
                            <button type="button" data-start class="{{ $btnPrimary }} mt-7">Start the symptom check →</button>
                            <p class="mt-4 text-xs text-brand-stone-light">This gives an indication only. Our pharmacist always confirms eligibility in person or by phone.</p>
                        </div>
                        <div data-questions hidden></div>
                        <div data-verdict hidden aria-live="polite"></div>
                        <form data-callback hidden action="{{ route('conditions.enquiry') }}" method="post" class="mt-6 space-y-4 rounded-[24px] border border-brand-hairline bg-brand-ivory p-6">
                            <h3 class="text-xl">Leave your details and we’ll call you</h3>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div><label for="st_name" class="{{ $label }}">Full name *</label><input id="st_name" name="name" type="text" autocomplete="name" required class="{{ $input }}"></div>
                                <div><label for="st_dob" class="{{ $label }}">Date of birth *</label><input id="st_dob" name="dob" type="date" required max="{{ date('Y-m-d') }}" class="{{ $input }}"></div>
                                <div><label for="st_phone" class="{{ $label }}">Phone *</label><input id="st_phone" name="phone" type="tel" autocomplete="tel" required class="{{ $input }}"></div>
                                <div><label for="st_email" class="{{ $label }}">Email</label><input id="st_email" name="email" type="email" autocomplete="email" class="{{ $input }}"></div>
                            </div>
                            <div><label for="st_symptoms" class="{{ $label }}">Describe your symptoms (optional)</label><textarea id="st_symptoms" name="symptoms" rows="3" class="{{ $input }}"></textarea></div>
                            <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                            <button type="submit" class="{{ $btnPrimary }} w-full">Send to our pharmacist</button>
                            <p class="text-xs leading-relaxed text-brand-stone-light">We’ll call you to arrange your consultation — usually the same day. Walk-ins welcome too.</p>
                        </form>
                    </div>
                </div>

                <aside class="reveal lg:col-span-5">
                    <div class="space-y-5 lg:sticky lg:top-28">
                        @if ($c['image'])
                            <div class="overflow-hidden rounded-3xl border border-brand-hairline shadow-editorial-card"><img src="{{ asset('images/'.$c['image']) }}" alt="" class="h-56 w-full object-cover"></div>
                        @endif
                        <div class="no-interact rounded-[20px] border border-red-200 bg-red-50 p-6">
                            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-red-700">Get help today instead if you have</p>
                            <ul class="mt-3 space-y-2 text-sm leading-relaxed text-brand-forest">
                                @foreach ($c['flags'] as $f)<li class="flex gap-2.5"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>{{ $f }}</li>@endforeach
                            </ul>
                            <p class="mt-4 text-xs text-brand-stone">Call us on {{ $phone }}, your GP, or NHS 111. In an emergency call 999.</p>
                        </div>
                        <div class="no-interact rounded-[20px] border border-brand-hairline bg-white p-6">
                            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-brand-stone-light">Who’s eligible?</p>
                            <p class="mt-3 text-sm leading-relaxed text-brand-stone">{{ $c['elig'] }}</p>
                            <p class="mt-3 text-sm text-brand-stone">Prefer to just come in? Walk into any of our six branches — no appointment needed.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Explained --}}
    <section class="no-interact bg-white py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl">
                <x-eyebrow>Understanding {{ str($c['name'])->lower() }}</x-eyebrow>
                <h2 class="mt-3 text-4xl leading-tight md:text-[42px]">{{ $c['name'] }}, <span class="editorial-highlight">explained.</span></h2>
                <p class="mt-4 text-base text-brand-stone">What it is, what to look out for, and what actually helps — from the pharmacists who treat it every day.</p>
            </div>
            <div class="mt-14 grid gap-12 lg:grid-cols-2">
                <div class="reveal">
                    <h3 class="text-[26px] leading-snug">{{ $c['what_heading'] }}</h3>
                    @foreach ($c['what'] as $p)<p class="mt-4 text-lg leading-[1.7] text-brand-stone">{{ $p }}</p>@endforeach
                </div>
                <div class="reveal rounded-[24px] border border-brand-hairline bg-brand-ivory p-8">
                    <h3 class="text-[22px] leading-snug">{{ $c['symptoms_heading'] }}</h3>
                    <ol class="mt-5 space-y-3">
                        @foreach ($c['symptoms'] as $i => $s)
                            <li class="flex gap-4 text-[15px] leading-relaxed text-brand-forest"><span class="font-serif text-2xl leading-none text-brand-moss">{{ $i + 1 }}</span>{{ $s }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- How we treat it --}}
    <section class="no-interact border-y border-brand-pistachio-line bg-brand-pistachio py-20 md:py-24">
        <div class="mx-auto grid max-w-[1280px] gap-10 px-6 sm:px-8 md:grid-cols-2">
            <div class="reveal">
                <h2 class="text-[28px] leading-snug">How we <span class="editorial-highlight">treat it.</span></h2>
                <p class="mt-4 text-base leading-relaxed text-brand-stone">Speak to our pharmacist in the private consultation room. Where appropriate we can supply treatment free of charge under the local NHS scheme, or recommend the best over-the-counter option.</p>
            </div>
            <div class="reveal">
                <h2 class="text-[28px] leading-snug">Who’s <span class="editorial-highlight">eligible?</span></h2>
                <p class="mt-4 text-base leading-relaxed text-brand-stone">{{ $c['nhs_note'] ?: 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment. Use the one-minute symptom check and our pharmacist will be in touch to arrange your consultation — or just walk in.' }}</p>
            </div>
        </div>
    </section>

    {{-- FAQs --}}
    <section class="bg-brand-ivory py-24 md:py-32">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal max-w-2xl"><x-eyebrow>Good to know</x-eyebrow><h2 class="mt-3 text-4xl leading-tight md:text-[42px]">{{ $c['name'] }} — <span class="editorial-highlight">your questions answered.</span></h2></div>
            <div class="mt-12 max-w-3xl divide-y divide-brand-hairline border-y border-brand-hairline" data-reveal-group>
                @foreach ($c['faqs'] as [$q, $a])
                    <details class="wl-faq reveal group py-1">
                        <summary class="flex items-center justify-between gap-6 py-5 text-lg font-medium text-brand-forest">{{ $q }}<span class="wl-faq__icon flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-brand-hairline text-brand-moss transition-transform duration-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg></span></summary>
                        <div class="pb-6 pr-14 text-[15px] leading-relaxed text-brand-stone">{{ $a }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Related --}}
    <section class="border-t border-brand-hairline bg-white py-20 md:py-24">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            <div class="reveal flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div><x-eyebrow>More we can treat</x-eyebrow><h2 class="mt-3 text-[32px] leading-tight md:text-[38px]">Other <span class="editorial-highlight">{{ str($c['tag'])->lower() }}</span> conditions.</h2></div>
                <a href="{{ route('pharmacy-first') }}" class="text-sm font-semibold text-brand-moss hover:underline">All conditions A–Z →</a>
            </div>
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                @foreach ($related as $r)
                    <a href="{{ route('conditions.show', $r['slug']) }}" class="reveal tilt group flex flex-col rounded-[20px] border border-brand-hairline bg-brand-ivory p-6 transition-all duration-[250ms] hover:-translate-y-1 hover:shadow-editorial-hover">
                        <h3 class="text-xl leading-snug">{{ $r['name'] }}</h3>
                        <p class="mt-1 text-xs text-brand-stone-light">{{ $r['age_label'] }}</p>
                        <span class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-brand-moss">Check my symptoms <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <x-final-cta :href="route('book')" action="Book a consultation" sub="No appointment needed at any of our six branches — but you can reserve a slot if you’d rather.">
        See a pharmacist, <span class="editorial-highlight">not a waiting room.</span>
    </x-final-cta>

    <div id="wl-toast" role="status" aria-live="polite" class="max-w-[90vw] rounded-full bg-brand-forest px-5 py-3 text-sm text-white shadow-editorial-hover"></div>
</x-layout>
