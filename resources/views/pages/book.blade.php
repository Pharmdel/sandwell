@php
    $services = [
        ['value' => 'flu', 'title' => 'Flu vaccination', 'body' => 'Seasonal NHS and private winter flu jabs.', 'chip' => 'NHS or private', 'tone' => 'quiet'],
        ['value' => 'covid', 'title' => 'Covid-19 vaccination', 'body' => 'Autumn and winter boosters for eligible groups.', 'chip' => 'NHS eligible groups', 'tone' => 'quiet'],
        ['value' => 'pharmacy-first', 'title' => 'Pharmacy First consultation', 'body' => 'Assessment and treatment for seven common conditions.', 'chip' => 'NHS · Free', 'tone' => 'nhs'],
        ['value' => 'blood-pressure', 'title' => 'Blood pressure check', 'body' => 'A free cardiovascular check, with 24-hour monitoring if needed.', 'chip' => 'NHS · Free', 'tone' => 'nhs'],
    ];
    $steps = ['Service', 'Eligibility', 'Branch & time', 'Your details'];
@endphp

<x-layout title="Book an appointment" hero description="Book a flu jab, Covid-19 vaccination, Pharmacy First consultation or blood pressure check at any Sandwell Pharmacy Group branch.">
    <x-page-hero image="flu-vaccine.jpg" alt="A pharmacist preparing a vaccination" size="short">
        <div class="max-w-3xl">
            <p class="reveal text-[11px] font-bold uppercase tracking-[0.14em] text-brand-orange">Book online · About 2 minutes</p>

            <h1 class="mt-5 text-4xl leading-[1.05] text-white md:text-[62px]" data-lines>
                <span class="line-mask"><span>Book your <span class="editorial-highlight">appointment.</span></span></span>
            </h1>

            <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80" style="--reveal-delay:300ms">
                Choose a service, check eligibility, pick a branch and time. You'll get a text
                confirmation straight away.
            </p>
        </div>
    </x-page-hero>

    <section class="py-16 md:py-20">
        <div class="mx-auto max-w-[1280px] px-6 sm:px-8">
            {{-- Stepper --}}
            <ol class="no-interact reveal mt-14 flex flex-wrap items-center gap-x-4 gap-y-3 border-y border-brand-hairline py-5">
                @foreach ($steps as $i => $step)
                    <li class="flex items-center gap-3">
                        <span @class([
                            'flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold',
                            'bg-brand-peach text-brand-orange' => $i === 0,
                            'bg-brand-navy/5 text-brand-stone-light' => $i !== 0,
                        ])>{{ $i + 1 }}</span>
                        <span @class([
                            'text-sm',
                            'font-semibold text-brand-navy' => $i === 0,
                            'text-brand-stone-light' => $i !== 0,
                        ])>{{ $step }}</span>
                        @if (! $loop->last)<span class="ml-1 hidden h-px w-10 bg-brand-hairline sm:block"></span>@endif
                    </li>
                @endforeach
            </ol>

            <div class="mt-12 grid gap-8 lg:grid-cols-12">
                {{-- Service choice --}}
                <form action="{{ route('book') }}" method="get" class="reveal lg:col-span-8">
                    <div class="rounded-3xl border border-brand-hairline bg-white p-8 shadow-editorial-card md:p-10">
                        <h2 class="text-[28px] leading-tight">What do you <span class="editorial-highlight">need today?</span></h2>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            @foreach ($services as $i => $service)
                                <label class="group relative flex cursor-pointer flex-col rounded-[20px] border border-brand-hairline p-6 transition-all duration-200 hover:border-brand-navy/40 has-[:checked]:border-2 has-[:checked]:border-brand-navy">
                                    <input type="radio" name="service" value="{{ $service['value'] }}" @checked($i === 0)
                                        class="peer sr-only">
                                    <span class="absolute right-5 top-5 hidden h-5 w-5 items-center justify-center rounded-full bg-brand-orange text-white peer-checked:flex">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-peach">
                                        <svg class="h-5 w-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 6v12m6-6H6"/></svg>
                                    </span>
                                    <span class="mt-4 block text-lg font-semibold text-brand-navy">{{ $service['title'] }}</span>
                                    <span class="mt-1.5 block text-sm leading-relaxed text-brand-stone">{{ $service['body'] }}</span>
                                    <x-chip :tone="$service['tone']" class="mt-4 self-start">{{ $service['chip'] }}</x-chip>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-8 flex flex-col items-start gap-5 border-t border-brand-hairline pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-brand-stone">Walk-ins are also welcome at all six branches.</p>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-orange px-7 py-3.5 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-orange-hover hover:shadow-orange-glow active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
                                Continue
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Summary --}}
                <aside class="no-interact reveal lg:col-span-4">
                    <div class="sticky top-28 space-y-5">
                        <div class="rounded-[20px] border border-brand-hairline bg-white p-7 shadow-editorial-card">
                            <h2 class="text-[22px]">Your booking</h2>
                            <div class="mt-5">
                                @foreach ([['Service', 'Flu vaccination'], ['Eligibility', 'Next step'], ['Branch', 'Not chosen'], ['Time', 'Not chosen']] as $row)
                                    <div class="flex items-center justify-between gap-3 border-b border-brand-hairline py-3 last:border-0">
                                        <span class="text-xs font-semibold uppercase tracking-[0.08em] text-brand-stone-light">{{ $row[0] }}</span>
                                        <span class="text-sm text-brand-navy">{{ $row[1] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-5 rounded-2xl bg-brand-peach p-4 text-xs leading-relaxed text-brand-orange-hover">
                                Bring your NHS number and a list of your current medicines.
                            </p>
                        </div>

                        <div class="rounded-[20px] border border-brand-hairline bg-white/60 p-7">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-stone-light">Eligibility preview</p>
                            <div class="mt-4 space-y-3 opacity-50">
                                @foreach (['Your age group', 'Pregnancy or long-term condition', 'Recent vaccinations'] as $q)
                                    <div class="flex items-center justify-between gap-3 border-b border-brand-hairline pb-3 last:border-0">
                                        <span class="text-sm text-brand-stone">{{ $q }}</span>
                                        <span class="rounded-full bg-brand-navy/5 px-3 py-1 text-[10px] uppercase tracking-wide text-brand-stone-light">Step 2</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="no-interact border-t border-brand-peach-line bg-brand-peach py-20">
        <div class="mx-auto grid max-w-[1280px] gap-10 px-6 sm:px-8 md:grid-cols-3" data-reveal-group>
            @foreach ([
                ['Text confirmation in seconds', 'You will know it is booked before you leave the page.'],
                ['Reschedule with one call', 'Life happens — ring us and we will move it.'],
                ['Pharmacist-delivered, in a private room', 'Every appointment happens in a private consultation room.'],
            ] as $item)
                <div class="reveal">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white">
                        <svg class="h-5 w-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <h3 class="mt-5 text-lg leading-snug">{{ $item[0] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-brand-stone">{{ $item[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-layout>
