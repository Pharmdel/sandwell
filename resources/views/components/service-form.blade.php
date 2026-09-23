@props(['slug', 'form'])

@php
    $input = 'w-full rounded-xl border border-brand-hairline bg-white px-4 py-3 text-[15px] text-brand-forest placeholder:text-brand-stone-light transition focus:border-brand-moss focus:outline-none focus:ring-2 focus:ring-brand-moss/25';
    $label = 'block text-sm font-semibold text-brand-forest';
@endphp

<section id="enquire" class="scroll-mt-24 border-b border-brand-hairline bg-brand-ivory py-24 md:py-28">
    <div class="mx-auto max-w-[720px] px-6 sm:px-8">
        <div class="reveal text-center">
            <x-eyebrow>Get started</x-eyebrow>
            <h2 class="mt-3 text-4xl leading-tight md:text-[40px]">{{ $form['heading'] }}</h2>
            <p class="mx-auto mt-4 max-w-lg text-base leading-relaxed text-brand-stone">{{ $form['lead'] }}</p>
        </div>

        <form data-service-form action="{{ route('services.enquiry', $slug) }}" method="post"
            class="reveal mt-10 rounded-[24px] border border-brand-hairline bg-white p-7 shadow-editorial-card sm:p-9">
            @csrf

            {{-- Honeypot: a real patient never sees this, a bot fills it and is rejected. --}}
            <div class="sr-only" aria-hidden="true">
                <label for="{{ $slug }}-website">Leave this field empty</label>
                <input id="{{ $slug }}-website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($form['fields'] as $field)
                    @php $id = $slug.'-'.$field['name']; $half = ! empty($field['half']); @endphp
                    <div class="{{ $half ? '' : 'sm:col-span-2' }}">
                        <label for="{{ $id }}" class="{{ $label }}">
                            {{ $field['label'] }}@if (! empty($field['required']))<span class="text-brand-moss"> *</span>@endif
                        </label>

                        @if ($field['type'] === 'select')
                            <select id="{{ $id }}" name="{{ $field['name'] }}" @required(! empty($field['required'])) class="{{ $input }} mt-2">
                                <option value="">Please choose…</option>
                                @foreach ($field['options'] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @elseif ($field['type'] === 'textarea')
                            <textarea id="{{ $id }}" name="{{ $field['name'] }}" rows="3" placeholder="{{ $field['placeholder'] ?? '' }}"
                                @required(! empty($field['required'])) class="{{ $input }} mt-2 resize-y"></textarea>
                        @else
                            <input id="{{ $id }}" type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}" autocomplete="{{ $field['autocomplete'] ?? 'off' }}"
                                @required(! empty($field['required'])) class="{{ $input }} mt-2">
                        @endif

                        <p data-error-for="{{ $field['name'] }}" hidden class="mt-1.5 text-[13px] text-brand-moss"></p>
                    </div>
                @endforeach
            </div>

            <button type="submit" data-magnetic
                class="mt-7 inline-flex w-full items-center justify-center gap-2.5 rounded-full bg-brand-moss px-7 py-4 text-[15px] font-semibold text-white transition-all duration-300 hover:bg-brand-moss-hover hover:shadow-moss-glow active:scale-[0.98] disabled:opacity-60 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-moss focus-visible:ring-offset-2">
                <span data-form-label>{{ $form['button'] }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/></svg>
            </button>

            <p class="mt-4 text-center text-[13px] leading-relaxed text-brand-stone-light">
                Your details are kept confidential and never shared with third parties.
            </p>

            <div data-form-done hidden class="mt-6 rounded-2xl border border-brand-hairline bg-brand-ivory p-6 text-center">
                <p class="font-serif text-xl text-brand-forest">Thank you — we have your details.</p>
                <p class="mt-2 text-sm leading-relaxed text-brand-stone" data-form-message></p>
                <a href="{{ config('pharmacy.phone_href') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-moss hover:underline">
                    Or call {{ config('pharmacy.phone') }} now
                </a>
            </div>
        </form>
    </div>
</section>
