<?php

// Content for the weight loss clinic page. Copy mirrors the group's published
// clinic page so the pharmacist-approved wording stays intact.
return [
    'treatments' => [
        [
            'key' => 'wegovy',
            'name' => 'Wegovy® Injections',
            'type' => 'Weekly injection',
            'tag' => 'Weekly injection · Semaglutide',
            'short' => 'Weekly semaglutide pen',
            'badge' => 'Most popular',
            'image' => 'wl/wl-wegovy-inj-0826.webp',
            'bullets' => ['Semaglutide — proven appetite control', 'Once-weekly pen, doses built up gradually', 'Average ~15% body-weight loss in trials'],
            'strengths' => ['0.25mg', '0.5mg', '1mg', '1.7mg', '2.4mg'],
            'strength_note' => 'Doses build up over the first months to the 2.4mg weekly maintenance dose. Prices vary by strength — higher strengths cost more; we’ll confirm your exact price at your free consultation.',
            'detail' => [
                ['How it works', 'Wegovy contains semaglutide, a GLP-1 medicine that mimics a natural fullness hormone — you feel satisfied sooner and stay full for longer, so eating less feels natural rather than a battle.'],
                ['How you take it', 'One simple pre-filled pen injection a week, with the dose built up gradually over the first weeks to keep side effects manageable. We show you exactly how at your first appointment.'],
                ['What to expect', 'In clinical trials people lost around 15% of their body weight on average over 68 weeks, alongside diet and activity changes. Regular pharmacist check-ins are included throughout.'],
            ],
        ],
        [
            'key' => 'mounjaro',
            'name' => 'Mounjaro® Injections',
            'type' => 'Weekly injection',
            'tag' => 'Weekly injection · Tirzepatide',
            'short' => 'Weekly tirzepatide pen — strongest results',
            'badge' => null,
            'image' => 'wl/wl-mounjaro-0826.webp',
            'bullets' => ['Tirzepatide — dual-action GIP + GLP-1', 'The strongest results of any licensed option', 'Average ~20% body-weight loss in trials'],
            'strengths' => ['2.5mg', '5mg', '7.5mg', '10mg', '12.5mg', '15mg'],
            'strength_note' => 'You start at 2.5mg and step up every 4 weeks as needed. Prices vary by strength — higher strengths cost more; we’ll confirm your exact price at your free consultation.',
            'detail' => [
                ['How it works', 'Mounjaro is the newest option — tirzepatide targets two appetite hormones (GIP and GLP-1) at once, which is why it delivers the strongest results of any licensed weight-loss medicine.'],
                ['How you take it', 'One pre-filled KwikPen injection a week, doses stepped up gradually. Full training and support from our pharmacist.'],
                ['What to expect', 'Around 20% average body-weight loss in trials at the higher doses — with ongoing check-ins to keep you safe and on track.'],
            ],
        ],
        [
            'key' => 'wegovy_oral',
            'name' => 'Wegovy® Oral Tablets',
            'type' => 'Daily tablet · New for 2026',
            'tag' => 'Daily tablet · New for 2026',
            'short' => 'Daily tablet, no needles',
            'badge' => 'New for 2026',
            'image' => 'wl/wl-wegovy-oral-0826.webp',
            'bullets' => ['The same semaglutide — no needles', 'One tablet a day, taken at home', 'Ideal if injections aren’t for you'],
            'strengths' => ['Built up to 25mg daily'],
            'strength_note' => 'The tablet dose is increased gradually to the daily maintenance dose. Prices vary by strength — higher strengths cost more; we’ll confirm your exact price at your free consultation.',
            'detail' => [
                ['How it works', 'The same proven semaglutide as the Wegovy injection — in a once-daily tablet. Same appetite control, no needles.'],
                ['How you take it', 'One small tablet each morning on an empty stomach with a sip of water, then wait 30 minutes before eating. That’s it.'],
                ['What to expect', 'Results comparable to the weekly injection in trials, making it ideal if you want GLP-1 power but injections aren’t for you.'],
            ],
        ],
        [
            'key' => 'orlistat',
            'name' => 'Alli® (Orlistat 60mg)',
            'type' => 'Daily capsule',
            'tag' => 'Daily capsule · Orlistat',
            'short' => 'Daily capsule — budget-friendly',
            'badge' => null,
            'image' => 'wl/wl-alli-0826.webp',
            'bullets' => ['Blocks about a third of dietary fat', 'Taken with meals — the budget-friendly option', 'Works alongside a reduced-fat diet'],
            'strengths' => ['60mg — the only strength (available OTC)'],
            'strength_note' => 'Alli comes in one over-the-counter strength, so pricing is simple — we’ll confirm it at your consultation.',
            'detail' => [
                ['How it works', 'Alli works differently — instead of reducing appetite, it blocks about a third of the fat in your food from being absorbed, so fewer calories make it in.'],
                ['How you take it', 'One capsule with each main meal (up to three a day), alongside a reduced-fat diet — our pharmacist will help you plan it.'],
                ['What to expect', 'Steady, sustainable loss — typically 50% more than diet alone — at the most budget-friendly price point of our options.'],
            ],
        ],
    ],

    'foundayo' => [
        'name' => 'Foundayo · orforglipron',
        'intro' => 'A once-daily tablet — no injections, no needles, and no food or water restrictions. Approved by the MHRA in August 2026 for weight management, and made by Eli Lilly, the company behind Mounjaro.',
        'bullets' => ['One tablet a day, taken any time', 'Around 11–12% average weight loss at the highest dose in trials', 'Same GLP-1 family as Mounjaro and Wegovy', 'Private prescription only — not available on the NHS'],
        'note' => 'We’ll contact you the moment we can supply it. No payment, no obligation.',
        'small_print' => 'Foundayo (orforglipron) is a prescription-only medicine and is not available on the NHS. Supply depends on a consultation with our pharmacist and on stock reaching us. Registering does not guarantee treatment, and we’ll only use your details to contact you about this.',
    ],

    'outcomes' => [
        ['figure' => 2, 'label' => '−2 lbs', 'when' => 'In a few weeks', 'text' => 'Healthy weight loss of 1–2 lbs per week*'],
        ['figure' => 23, 'label' => '−23 lbs', 'when' => 'In one year', 'text' => 'Lose 10–20% of your body weight*'],
    ],
    'outcomes_disclaimer' => '*With Wegovy or Mounjaro alongside diet and activity changes, based on published clinical trials. Individual results vary.',

    'journey' => [
        ['when' => 'now', 'title' => 'Select a treatment', 'body' => 'Browse the four options above — or let the suitability check pick for you.'],
        ['when' => '1 minute', 'title' => 'Check your suitability', 'body' => 'Five quick questions — BMI, preferences and budget — then book your free consultation call.'],
        ['when' => 'same day', 'title' => 'Pharmacist review', 'body' => 'Our pharmacist calls to confirm suitability, check your medical history and answer questions.'],
        ['when' => '1–2 days', 'title' => 'Start your treatment', 'body' => 'Collect from the pharmacy or have it delivered free — with regular check-ins from then on.'],
    ],
    'eligibility_note' => 'A BMI of 30 or above is needed for weight-loss medicines. Taking other medication? Mention it on the call — our pharmacist will review everything before you start.',

    'testimonials' => [
        ['initial' => 'S', 'name' => 'Sarah M.', 'result' => '−2st 4lbs on Mounjaro', 'quote' => 'I’d tried everything before this. The pharmacist explained it all properly at the first call and checks in with me every month — four months on and I’m down over two stone. Never felt rushed once.'],
        ['initial' => 'J', 'name' => 'James T.', 'result' => '−18lbs on Wegovy', 'quote' => 'Switched from an online provider and it’s night and day. Being able to walk in and ask questions instead of emailing a helpdesk makes the whole thing feel safer. Delivery is free too.'],
        ['initial' => 'A', 'name' => 'Aisha K.', 'result' => '−11lbs on Wegovy tablets', 'quote' => 'I hate needles so the tablet option was perfect for me. The suitability check online took a minute and they called me the same afternoon. Three months in and my energy is completely different.'],
        ['initial' => 'M', 'name' => 'Mark D.', 'result' => '−3st on Mounjaro', 'quote' => 'The monthly check-ins keep me honest. They adjusted my dose when I plateaued and explained exactly why. Down three stone since January and my knees have stopped complaining.'],
        ['initial' => 'P', 'name' => 'Priya S.', 'result' => '−9lbs on Alli', 'quote' => 'I didn’t want injections or a big monthly bill. They were honest that Alli would be steadier progress and helped me sort my meals around it. Slow and steady but it’s working.'],
        ['initial' => 'L', 'name' => 'Louise B.', 'result' => '−1st 6lbs on Wegovy', 'quote' => 'What sold me was talking to an actual pharmacist who knows my other medication. They checked everything against my records before starting me. Six weeks in and it’s the easiest ‘diet’ I’ve ever done.'],
    ],

    'switch' => [
        'bullets' => ['Seamless switch — no break in your doses', 'A real pharmacist in West Bromwich, not a call centre', 'Collect in store or free local delivery'],
        'medications' => ['Mounjaro injections', 'Wegovy injections', 'Wegovy oral tablets', 'Alli / Orlistat', 'Other'],
    ],

    'faqs' => [
        ['Am I eligible for weight-loss treatment?', 'Weight-loss medicines are licensed for adults with a BMI of 30 or above. Our one-minute suitability check works out your BMI and recommends the right option, and the pharmacist confirms everything on your free consultation call.'],
        ['How much weight will I lose?', 'In clinical trials people lost around 15% of their body weight on Wegovy and around 20% on Mounjaro over a year, alongside diet and activity changes. A healthy pace is 1–2 lbs a week — individual results vary.'],
        ['Are there side effects?', 'The most common are nausea, constipation and tiredness in the first weeks, which is why every treatment starts on a low dose and builds up gradually. Your pharmacist monitors you at every check-in and adjusts the plan if needed.'],
        ['I hate needles — are there tablet options?', 'Yes — Wegovy Oral Tablets give you the same semaglutide as the injection in a once-daily tablet, and Alli (orlistat 60mg) is a daily capsule that works differently by blocking about a third of dietary fat.'],
        ['Can I switch from another provider?', 'Yes — if you’re already on Wegovy or Mounjaro elsewhere we can take over your care. We match your current medication and dose and time the handover so you never miss a week. Use the switch form above.'],
        ['What happens on the free consultation call?', 'Our pharmacist goes through your goals, medical history and current medication (with your consent we can check your NHS Summary Care Record), confirms which treatment is suitable, and gets you booked in to start. There’s nothing to pay until you begin treatment.'],
        ['Do I have to come into the pharmacy?', 'Your first supply can be collected in store — handy if you’d like an in-person run-through of your injection pen — or delivered free locally. Check-ins can happen by phone or in person, whichever suits you.'],
    ],

    'best_times' => ['Morning (9am–12pm)', 'Afternoon (12–4pm)', 'Late afternoon (4–6pm)'],

    // Article bodies are filled from the group's published guides.
    'guides' => [
        [
            'slug' => 'weight-loss-injections-west-bromwich',
            'chip' => 'Weight loss',
            'image' => 'weightloss-outdoor.jpg',
            'read' => '2 min read',
            'title' => 'Weight Loss Injections in West Bromwich: Your Local Options Explained',
            'blurb' => 'Mounjaro and Wegovy through a local pharmacist-led clinic — with real check-ins, not just a courier and a login.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Weight loss injections have transformed what’s possible for people who’ve spent years fighting their weight. In clinical trials, patients lost around 15% of their body weight on Wegovy and around 20% on Mounjaro over a year — results that diet and willpower alone rarely deliver for people with a BMI over 30.',
                'If you’re searching for weight loss support in West Bromwich, you don’t need to rely on an anonymous online-only provider. Hollytree Pharmacy runs a pharmacist-led weight loss clinic at your local branch, combining the same medications with something the online clinics can’t offer: a local pharmacist who knows you and checks in with you face to face or by phone at every stage.',
            ],
            'body' => [
                [
                    'What treatments are available?',
                    'Our clinic offers Mounjaro injections (tirzepatide — our most popular option), Wegovy injections (semaglutide), Wegovy oral tablets for people who prefer to avoid needles, and Alli (orlistat 60mg) capsules as a lower-cost daily option. All work differently and suit different people, budgets and goals — our one-minute online suitability check recommends the right starting point, and the pharmacist confirms everything at a free consultation call before anything is supplied.',
                ],
                [
                    'Why choose a local clinic over online-only?',
                    'Injection technique shown in person. Doses reviewed by someone who can actually see you. Side effects handled the same day rather than by ticket queue. Collection in store or free local delivery. And if you’re already on treatment with an online provider, we can take over your care — matching your current medication and dose so you never miss a week.',
                ],
                [
                    'Am I eligible?',
                    'Weight-loss medicines are licensed for adults with a BMI of 30 or above (or lower with weight-related conditions, assessed individually). Our suitability check works out your BMI and asks about your health and medications; if injections aren’t right for you, we’ll tell you that too and suggest what is.',
                ],
            ],
            'faqs' => [
                [
                    'How much weight can I lose on Mounjaro or Wegovy?',
                    'In trials, people lost around 20% of their body weight on Mounjaro and around 15% on Wegovy over about a year, alongside diet and activity changes. Individual results vary.',
                ],
                [
                    'Is there a weight loss clinic near West Bromwich?',
                    'Yes — your local branch runs a pharmacist-led weight loss clinic offering Mounjaro, Wegovy, Wegovy oral tablets and Alli, with free consultations.',
                ],
                [
                    'Can I switch to Hollytree Pharmacy from an online provider?',
                    'Yes. We match your current medication and dose and time the handover so you stay on schedule. Use the switch form on our weight loss page.',
                ],
            ],
            'cta' => [
                'Check your suitability across Hollytree Pharmacy',
                'Check your suitability →',
            ],
        ],
        [
            'slug' => 'mounjaro-west-bromwich',
            'chip' => 'Mounjaro',
            'image' => 'consult-room.jpg',
            'read' => '2 min read',
            'title' => 'Mounjaro in West Bromwich: How It Works and How to Start',
            'blurb' => 'What tirzepatide actually does, what the weekly routine looks like, and how to start with a local pharmacist.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Mounjaro (tirzepatide) is the most talked-about weight loss medicine in the UK right now — and with reason. It’s the first treatment to act on two appetite hormones at once, GLP-1 and GIP, and in trials patients lost around 20% of their body weight over a year, more than any other licensed option.',
                'Across Hollytree Pharmacy, Mounjaro is available through our pharmacist-led weight loss clinic — with a free consultation before you start and proper check-ins while you’re on it.',
            ],
            'body' => [
                [
                    'How Mounjaro works',
                    'Tirzepatide mimics two natural gut hormones that regulate appetite and blood sugar. The practical effect: you feel full sooner, stay full longer, and food noise quietens down remarkably. It’s a once-weekly injection with a pre-filled pen — most people find it far easier than they expected, and we demonstrate the technique in person with your first supply.',
                ],
                [
                    'Doses and what to expect',
                    'Everyone starts at 2.5mg weekly, moving up gradually (5mg, 7.5mg, and beyond to a maximum of 15mg) as your body adjusts. The slow build-up is deliberate — it keeps side effects like nausea and constipation manageable in the first weeks. Prices vary by strength; your pharmacist confirms costs at your consultation before you commit to anything.',
                ],
                [
                    'Starting locally',
                    'Take our one-minute suitability check on the weight loss page — it works out your BMI (treatment is licensed for BMI 30+) and books your free consultation call. Your pharmacist reviews your medical history and medications, confirms Mounjaro is appropriate, and arranges your first supply for collection in store or free local delivery. Already on Mounjaro elsewhere? We can take over your care at your current dose.',
                ],
            ],
            'faqs' => [
                [
                    'How much is Mounjaro in West Bromwich?',
                    'Prices vary by dose strength. Hollytree Pharmacy confirms exact pricing at your free consultation — start with the suitability check on our weight loss page.',
                ],
                [
                    'Do I qualify for Mounjaro?',
                    'Mounjaro is licensed for weight loss in adults with a BMI of 30 or above (or lower with weight-related health conditions). Our online check works out your BMI in under a minute.',
                ],
                [
                    'What are the most common Mounjaro side effects?',
                    'Nausea, constipation and tiredness in the early weeks, easing as your body adjusts — which is why doses build up gradually. Your pharmacist monitors you at every check-in.',
                ],
            ],
            'cta' => [
                'Start with a free consultation across Hollytree Pharmacy',
                'Start with a free consultation →',
            ],
        ],
        [
            'slug' => 'wegovy-injections-west-bromwich',
            'chip' => 'Wegovy',
            'image' => 'flu-vaccine.jpg',
            'read' => '3 min read',
            'title' => 'Wegovy Injections in West Bromwich: How Semaglutide Works and How to Start',
            'blurb' => 'How Wegovy (semaglutide) works, the weekly dose schedule, what results to expect, and how to start at a pharmacist-led clinic in West Bromwich.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Wegovy (semaglutide) is the weight loss injection that started the conversation. It has been used by millions of people worldwide, it has the longest real-world track record of any GLP-1 weight loss treatment, and in trials people lost around 15% of their body weight over 68 weeks alongside diet and activity changes.',
                'Across Hollytree Pharmacy, Wegovy is prescribed through our pharmacist-led weight loss clinic. That means a free consultation before you start, a proper medical and medication review, and real check-ins as your dose builds — not just a courier and a login.',
            ],
            'body' => [
                [
                    'How Wegovy works',
                    'Semaglutide mimics GLP-1, a hormone your gut releases after eating. It tells your brain you are full, slows how quickly your stomach empties, and quietens the constant background pull towards food that most people describe as “food noise”. You are not fighting your appetite with willpower alone — the appetite itself is turned down.

It is a once-weekly injection under the skin of the stomach, thigh or upper arm, given with a pre-filled pen. Most people find it far less daunting than they expect; we show you the technique in person when you collect your first pen.',
                ],
                [
                    'The dose schedule',
                    'Wegovy is deliberately slow to build. You start at 0.25mg once a week for four weeks, then step up roughly every four weeks — 0.5mg, 1mg, 1.7mg — to a maintenance dose of 2.4mg. That gradual climb is what keeps nausea and other early side effects manageable, so it is worth not rushing. Some people settle at a lower dose and stay there; that is a clinical decision we make with you rather than a fixed script.',
                ],
                [
                    'What results look like in practice',
                    'Weight loss is steady rather than dramatic — typically 1–2lb a week once you are past the starting doses. The people who do best treat the injection as the thing that makes the other changes possible: eating enough protein, keeping fluids up, and moving most days. Muscle loss is the main risk of losing weight quickly on any GLP-1, which is why we talk about protein and resistance exercise at every check-in.',
                ],
                [
                    'Starting across Hollytree Pharmacy',
                    'Take the one-minute suitability check on our weight loss clinic page. It works out your BMI — Wegovy is licensed from a BMI of 30, or 27 if you have a weight-related condition such as high blood pressure or prediabetes — and books your free consultation. Your pharmacist reviews your history and medicines, confirms Wegovy is appropriate, and arranges collection in store or free local delivery. Already on Wegovy elsewhere? We can take over your care at your current dose.',
                ],
            ],
            'faqs' => [
                [
                    'How much weight can I lose on Wegovy?',
                    'In the main clinical trial, people taking Wegovy alongside diet and activity changes lost around 15% of their body weight over 68 weeks. Individual results vary a great deal — your starting weight, dose, diet and activity all matter. Your pharmacist will set realistic expectations at your consultation.',
                ],
                [
                    'Do I qualify for Wegovy?',
                    'Wegovy is licensed for adults with a BMI of 30 or above, or 27 and above with a weight-related health condition such as high blood pressure, prediabetes or sleep apnoea. Our online check works out your BMI in under a minute.',
                ],
                [
                    'What are the common Wegovy side effects?',
                    'Nausea, constipation, burping and tiredness are the usual early complaints, and they generally ease as your body adjusts. That is exactly why the dose builds up slowly over months rather than starting high.',
                ],
                [
                    'Wegovy or Mounjaro — which should I choose?',
                    'Mounjaro acts on two appetite hormones rather than one and produced greater average weight loss in trials, but Wegovy has the longer track record and suits many people perfectly well. We compare both properly at your consultation.',
                ],
                [
                    'What happens if I stop taking Wegovy?',
                    'Appetite returns to how it was before, and weight is commonly regained unless the habits built during treatment stick. We talk about the exit plan from the very first appointment, not at the end.',
                ],
            ],
            'cta' => [
                'Start Wegovy with a free consultation',
                'Start with a free consultation →',
            ],
        ],
        [
            'slug' => 'wegovy-vs-mounjaro',
            'chip' => 'Weight loss',
            'image' => 'weightloss-outdoor.jpg',
            'read' => '2 min read',
            'title' => 'Wegovy vs Mounjaro: Which Weight Loss Injection Is Right for You?',
            'blurb' => 'The honest differences — results, side effects, routine and cost — from pharmacists who prescribe both.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Wegovy and Mounjaro are both once-weekly injections that work by taming appetite — but they’re not the same medicine, and the right choice depends on your goals, your budget and how your body responds.',
                'Here’s the comparison we walk patients through at our West Bromwich weight loss clinic every week.',
            ],
            'body' => [
                [
                    'The headline difference: results',
                    'Wegovy (semaglutide) acts on one appetite hormone, GLP-1, and delivered around 15% average body-weight loss in its main trial. Mounjaro (tirzepatide) acts on two hormones, GLP-1 and GIP, and delivered around 20% at the top dose — currently the strongest results of any licensed weight loss medicine. That gap is why Mounjaro is the most popular choice in our clinic.',
                ],
                [
                    'Side effects and routine',
                    'Both share the same family of side effects — nausea, constipation, tiredness in the early weeks — managed the same way, by starting low and building the dose gradually. Both are once-weekly pens. Wegovy builds from 0.25mg to 2.4mg; Mounjaro from 2.5mg up to 15mg. Some evidence suggests Mounjaro’s gut side effects are comparable or slightly milder dose-for-dose, but individual experience varies more than averages do.',
                ],
                [
                    'Needle-free and budget options',
                    'Prefer tablets? Wegovy oral gives you the same semaglutide in a once-daily tablet. Watching the budget? Alli (orlistat 60mg) works completely differently — blocking about a third of dietary fat — at a fraction of the price. Our suitability wizard factors in your preference and budget before recommending anything.

The honest answer to “which is better?” is: the one you’ll stay on, at a dose you tolerate, with support that keeps you consistent. That’s what the free consultation is for.',
                ],
            ],
            'faqs' => [
                [
                    'Is Mounjaro better than Wegovy?',
                    'Mounjaro produced greater average weight loss in trials (~20% vs ~15%) and is our clinic’s most popular option, but the best choice depends on your response, budget and preferences. Both are effective.',
                ],
                [
                    'Can I switch from Wegovy to Mounjaro?',
                    'Often yes — switches are planned by the pharmacist so dosing stays safe and effective. Book a free consultation across Hollytree Pharmacy and bring your current dose details.',
                ],
                [
                    'Are there tablet alternatives to injections?',
                    'Yes — Wegovy oral tablets (daily semaglutide) and Alli capsules (orlistat 60mg) are both available through our West Bromwich clinic.',
                ],
            ],
            'cta' => [
                'Get a personal recommendation across Hollytree Pharmacy',
                'Get a personal recommendation →',
            ],
        ],
        [
            'slug' => 'wegovy-oral-tablets-explained',
            'chip' => 'Wegovy tablets',
            'image' => 'products.jpg',
            'read' => '3 min read',
            'title' => 'Wegovy Oral Tablets: The Needle-Free Semaglutide Option Explained',
            'blurb' => 'Oral semaglutide explained — how the tablet version of Wegovy works, the strict timing rules that make it effective, and who it suits best.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Not everyone wants a weekly injection. Oral semaglutide gives you the same active ingredient as Wegovy in a daily tablet — no needles, no pen, no fridge. For people who are needle-averse, or who travel constantly and would rather not think about a cold chain, it removes the single biggest barrier to starting treatment.',
                'It is available through the pharmacist-led weight loss clinic across Hollytree Pharmacy, with the same free consultation and check-ins as the injectable options.',
            ],
            'body' => [
                [
                    'How the tablet version works',
                    'Semaglutide is semaglutide: it mimics the GLP-1 hormone, reduces appetite, slows stomach emptying and turns down food noise. The difference is delivery. Semaglutide is a large molecule that the gut does not absorb easily, so the tablet is formulated with an absorption enhancer and taken daily rather than weekly to keep levels steady.',
                ],
                [
                    'The timing rules matter — a lot',
                    'This is the part people get wrong. The tablet must be taken on an empty stomach, first thing in the morning, with no more than half a glass of plain water — then nothing to eat, drink or take by mouth for at least 30 minutes. Tea, coffee, breakfast or other tablets inside that window can substantially reduce how much is absorbed, which is usually the real reason someone reports the tablets “doing nothing”. If a rigid morning routine sounds impossible, the weekly injection is honestly the better fit and we will say so.',
                ],
                [
                    'Who it suits',
                    'Oral semaglutide tends to work best for people with a genuine needle phobia, people who want to try a GLP-1 without committing to injections, and anyone whose mornings are already predictable. It is less suitable if you take several other morning medicines, if you cannot reliably leave a 30-minute gap before breakfast, or if you would simply rather deal with one injection a week than one tablet a day.',
                ],
                [
                    'Starting across Hollytree Pharmacy',
                    'Use the suitability check on our weight loss clinic page and pick the oral option, or tell your pharmacist at the consultation that you would rather avoid injections. We will go through the timing rules properly, confirm none of your other medicines clash with that morning window, and arrange collection or free local delivery.',
                ],
            ],
            'faqs' => [
                [
                    'Are Wegovy tablets as effective as the injection?',
                    'Taken exactly as directed, oral semaglutide produces meaningful weight loss, though the injectable form has the larger evidence base. In practice the deciding factor is adherence: the tablet only works properly if the empty-stomach timing is followed every single morning.',
                ],
                [
                    'Why must I take it on an empty stomach?',
                    'Semaglutide is poorly absorbed from the gut, and food, drink or other tablets in the stomach reduce absorption further. Taking it first thing with a small sip of plain water, then waiting at least 30 minutes, is what makes the dose count.',
                ],
                [
                    'Can I switch from injections to tablets, or the other way round?',
                    'Yes, and people do. Switching needs the dose matching up sensibly rather than swapping like for like, so it should always be done through a consultation rather than on your own.',
                ],
                [
                    'Do the tablets cause fewer side effects?',
                    'Not really — the side effect profile is much the same, because the active ingredient is identical. Nausea and constipation are still the most common early complaints.',
                ],
            ],
            'cta' => [
                'Prefer tablets to injections? Start with a free consultation',
                'Start with a free consultation →',
            ],
        ],
        [
            'slug' => 'foundayo-orforglipron-explained',
            'chip' => 'Coming soon',
            'image' => 'products.jpg',
            'read' => '3 min read',
            'title' => 'Foundayo (Orforglipron): The New Daily Weight Loss Tablet Explained',
            'blurb' => 'Foundayo (orforglipron) is a once-daily weight loss tablet with no injection and no food timing restrictions. How it works and who it suits, from our West Bromwich pharmacists.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Foundayo (orforglipron) is the newest option on the weight loss shelf, and the one people ask us about most. It is a once-daily tablet that acts on the same appetite pathway as the injections — but unlike oral semaglutide, it does not have to be taken on an empty stomach, and unlike the pens, it does not need refrigerating.',
            ],
            'body' => [
                [
                    'What makes it different',
                    'Orforglipron is a small-molecule GLP-1 receptor agonist. That chemistry is the whole point: because the molecule is small and stable, it survives the gut without an absorption enhancer, so it can be taken with or without food, at any time of day that suits you. No pen, no needles, no fridge, no 30-minute morning window — just a tablet, once a day.',
                ],
                [
                    'What to expect',
                    'The mechanism is familiar, so the experience is too: appetite falls, portions shrink naturally, and food noise quietens. Dosing starts low and steps up gradually, as with every treatment in this class, because that is what keeps early nausea and constipation tolerable. Weight loss is steady over months rather than sudden, and it works alongside diet and activity changes rather than instead of them.',
                ],
                [
                    'Who it suits',
                    'Foundayo tends to appeal to people who ruled out weight loss treatment because of needles, people who found the empty-stomach rules of oral semaglutide unworkable, and anyone whose day is too unpredictable for a fixed morning routine. Because it is newer, its long-term real-world track record is shorter than semaglutide’s — something we will be straight with you about at your consultation rather than glossing over.',
                ],
                [
                    'Starting across Hollytree Pharmacy',
                    'Availability of any new treatment moves quickly, so the honest answer on supply and price is the one your pharmacist gives you on the day. Start with the suitability check on our weight loss clinic page — it works out your BMI and books a free consultation, where we go through every option including the injectables and tell you which we would actually recommend for you. You can also register your interest on the clinic page and we will contact you the moment we can supply it.',
                ],
            ],
            'faqs' => [
                [
                    'Is Foundayo an injection?',
                    'No. Foundayo (orforglipron) is a once-daily tablet. There is no pen, no needle and no need to keep it in the fridge.',
                ],
                [
                    'Do I have to take Foundayo on an empty stomach?',
                    'No — that is its main practical advantage over oral semaglutide. It can be taken with or without food, which makes it far easier to fit around a real routine.',
                ],
                [
                    'How does Foundayo compare with Mounjaro and Wegovy?',
                    'All three act on the GLP-1 appetite pathway. Mounjaro also acts on a second hormone and produced the largest average weight loss in trials; Wegovy has the longest track record; Foundayo trades some of that established history for the convenience of a daily tablet.',
                ],
                [
                    'Is Foundayo suitable for me?',
                    'It is intended for adults whose BMI meets the treatment threshold, and it is not appropriate alongside certain medicines or medical histories. Your pharmacist checks all of that at the free consultation before anything is supplied.',
                ],
            ],
            'cta' => [
                'Ask about Foundayo at a free consultation',
                'Register your interest →',
            ],
        ],
        [
            'slug' => 'alli-orlistat-explained',
            'chip' => 'Alli',
            'image' => 'products.jpg',
            'read' => '2 min read',
            'title' => 'Alli (Orlistat 60mg): The Budget-Friendly Weight Loss Tablet Explained',
            'blurb' => 'How the only pharmacy weight-loss capsule works, who it suits, and how to take it without the infamous side effects.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Not everyone wants — or can afford — weekly injections. Alli (orlistat 60mg) is the established tablet alternative: a capsule taken with meals that blocks around a quarter to a third of the fat you eat from being absorbed.',
                'It’s less powerful than Mounjaro or Wegovy, but it’s dramatically cheaper, needle-free, and genuinely effective when used properly: adding roughly 50% more weight loss than dieting alone in studies.',
            ],
            'body' => [
                [
                    'How Alli works',
                    'Orlistat inhibits the enzymes that digest fat in your gut. Undigested fat passes straight through — which means fewer calories absorbed, and also explains the side effects everyone’s heard about. Here’s the part people miss: those side effects are almost entirely diet-dependent. Keep each meal under about 15g of fat and Alli is usually very well tolerated; eat a greasy takeaway and it will let you know.',
                ],
                [
                    'Who suits Alli?',
                    'Adults with a BMI of 28 or above who want a lower-cost, needle-free option, are willing to follow a reduced-fat diet, and prefer a treatment that reinforces good habits rather than suppressing appetite. It also suits people who can’t take GLP-1 medicines for medical reasons — your pharmacist checks this at the consultation.',
                ],
                [
                    'Getting the best from it',
                    'Take one capsule with each main meal containing fat (skip the dose for fat-free meals). Spread fat evenly across the day, take a multivitamin at bedtime since fat-soluble vitamin absorption drops slightly, and pair it with the food and activity changes that do the real long-term work. Across Hollytree Pharmacy, Alli is part of our weight loss clinic, so you get pharmacist check-ins rather than just a box off a shelf.',
                ],
            ],
            'faqs' => [
                [
                    'How much weight can you lose with Alli?',
                    'Studies show orlistat adds roughly 50% more weight loss than diet alone — for example, 6kg instead of 4kg over several months. It’s gentler than injections but far cheaper.',
                ],
                [
                    'How do I avoid Alli side effects?',
                    'Keep each meal below about 15g of fat. The oily digestive side effects almost always come from exceeding that — the tablet effectively enforces the diet.',
                ],
                [
                    'Do I need a consultation for Alli?',
                    'At any of our branches, yes — a quick suitability check confirms your BMI (28+) and rules out interactions, then you’re supplied the same day.',
                ],
            ],
            'cta' => [
                'Ask about Alli across Hollytree Pharmacy',
                'Ask about Alli →',
            ],
        ],
        [
            'slug' => 'weight-loss-injection-side-effects',
            'chip' => 'Side effects',
            'image' => 'consult-room.jpg',
            'read' => '4 min read',
            'title' => 'Weight Loss Injection Side Effects — and How to Actually Manage Them',
            'blurb' => 'Nausea, constipation, tiredness and the warning signs that need attention — a pharmacist’s practical guide to managing GLP-1 weight loss side effects.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                'Almost everyone starting Mounjaro, Wegovy or an oral GLP-1 gets some side effects in the first few weeks. Most are predictable, most are manageable, and most settle. Knowing which is which is the difference between pushing through a rough fortnight and abandoning a treatment that would have worked.',
                'This is the advice we give at check-ins at our West Bromwich clinic — and the reason we build check-ins into treatment in the first place.',
            ],
            'body' => [
                [
                    'Nausea: the common one',
                    'Nausea is the side effect people notice most, usually in the days after a dose increase. What helps: smaller portions, eating slowly and stopping before you are full, avoiding fatty and fried food, sipping water through the day rather than gulping, and not lying down straight after eating. Ginger and plain, dry food genuinely help some people. If a dose step is rough, staying at your current dose for another few weeks instead of climbing is a legitimate option — tell us rather than quietly stopping.',
                ],
                [
                    'Constipation and the fluid problem',
                    'These treatments slow the gut, and appetite suppression means most people eat and drink less, so constipation is almost inevitable without a plan. Keep fluids up deliberately, get fibre in from wholegrains, fruit, vegetables and beans, and move every day. If it persists, ask us — the right laxative depends on the problem, and matching one properly to your situation is a two-minute conversation at the counter.',
                ],
                [
                    'Tiredness, and why protein matters',
                    'Feeling flat in the early weeks usually reflects eating far less than before. Protein at every meal protects muscle while you lose fat, keeps you fuller, and steadies energy. Losing weight quickly without enough protein and some resistance exercise means losing muscle alongside fat — the outcome nobody wants, and the one we most often have to steer people away from.',
                ],
                [
                    'What needs attention, not patience',
                    'Come back to us or seek medical advice promptly for severe or persistent tummy pain, especially pain that goes through to your back, or vomiting you cannot keep on top of. The same applies to signs of dehydration, or any severe pain in the upper right of your tummy. These are uncommon, but they are the ones that are not part of “settling in”. If you are ever unsure, call us on 0121 500 5756 — we would far rather check.',
                ],
            ],
            'faqs' => [
                [
                    'How long do weight loss injection side effects last?',
                    'For most people the early nausea and constipation ease within a couple of weeks at a given dose, then may return briefly after each dose increase. That pattern is why doses build up slowly over months.',
                ],
                [
                    'Should I stop treatment if the side effects are bad?',
                    'Speak to your pharmacist before stopping. Very often the answer is to hold at your current dose for longer rather than climbing, or to change what and how you are eating — not to abandon treatment altogether.',
                ],
                [
                    'Will I lose muscle as well as fat?',
                    'You can, if you lose weight quickly without enough protein or any resistance exercise. Prioritising protein at every meal and doing some strength work two or three times a week protects muscle while the fat comes off.',
                ],
                [
                    'Can I drink alcohol on a GLP-1 treatment?',
                    'Many people find they simply want it less. Alcohol adds calories, irritates a slowed stomach and worsens nausea, so keeping it low, especially around a dose increase, is sensible.',
                ],
                [
                    'Which side effects mean I should get help the same day?',
                    'Severe or persistent tummy pain — particularly pain radiating to your back — relentless vomiting, or signs of dehydration all warrant prompt advice. Call us, your GP or NHS 111, and 999 in an emergency.',
                ],
            ],
            'cta' => [
                'On treatment and struggling with side effects? Talk to us',
                'Start with a free consultation →',
            ],
        ],
        [
            'slug' => 'weight-loss-treatment-cost-west-bromwich',
            'chip' => 'Cost',
            'image' => 'hero-consult.jpg',
            'read' => '3 min read',
            'title' => 'What Weight Loss Treatment Actually Costs in West Bromwich',
            'blurb' => 'How weight loss treatment pricing really works — why cost changes with dose, what is included, and why the consultation across Hollytree Pharmacy is free.',
            'reviewed' => 'Clinically reviewed by Jeetender Singh Sahota, Superintendent Pharmacist (GPhC 2048691)',
            'updated' => 'August 2026',
            'lead' => [
                '“How much is it?” is the first question almost everyone asks, and the honest answer is: it depends on which treatment and which dose — which is exactly why we do not publish a single headline number that turns out to be wrong for most people.',
                'Here is how the pricing actually works, so you can compare us with anywhere else properly.',
            ],
            'body' => [
                [
                    'Why cost changes with dose',
                    'Injectable treatments are priced by strength. You start on the lowest dose and step up over several months, so your monthly cost typically rises as your dose does, then levels off once you settle at a maintenance dose. Any clinic quoting one flat figure is either quoting the starting dose or averaging — ask which. Tablet options are priced differently again, and Alli, the pharmacy orlistat capsule, sits at the budget end of the range.',
                ],
                [
                    'What should be included',
                    'Medicine is only part of it. At any of our branches the consultation is free, the pen or tablets come with an in-person demonstration and a proper medication review, check-ins are included rather than charged as extras, and local delivery is free if you would rather not come in. When you compare prices with an online-only service, compare what happens after the parcel arrives — that is usually where the difference sits.',
                ],
                [
                    'How to get an accurate figure',
                    'Take the one-minute suitability check on our weight loss clinic page. Your pharmacist confirms which treatments are appropriate for you and gives you exact prices for the doses you would actually be on, before you commit to anything. Nothing is charged at the consultation stage, and there is no obligation to start.',
                ],
                [
                    'Switching from another provider',
                    'If you are already on treatment elsewhere and paying more than you expected, you can switch to us at your current dose — use the switch form on the weight loss page. We will confirm your dose, review your medicines, and take over your care without restarting the build-up from the bottom.',
                ],
            ],
            'faqs' => [
                [
                    'Why don’t you publish a single price for Mounjaro or Wegovy?',
                    'Because the cost depends on dose strength, and your dose changes as treatment builds up. A single figure would be misleading for most people, so we confirm exact prices for your doses at the free consultation.',
                ],
                [
                    'Is the consultation really free?',
                    'Yes. The suitability check and the pharmacist consultation cost nothing, and you are under no obligation to start treatment afterwards.',
                ],
                [
                    'Is weight loss treatment available on the NHS?',
                    'NHS access exists but is tightly restricted and usually routed through specialist weight management services with long waits. Most people in our clinic are paying privately, which is why we are open about what that costs.',
                ],
                [
                    'What is the cheapest option?',
                    'Alli (orlistat 60mg) is the lowest-cost treatment available directly from a pharmacy, though it works differently from the GLP-1 injections and suits a different person. Your pharmacist will tell you honestly whether it is likely to help in your case.',
                ],
                [
                    'Can I switch to Hollytree Pharmacy without starting again at the lowest dose?',
                    'Yes. If your current dose is established, we can continue at that dose after reviewing your history and medicines — use the switch request form on our weight loss page.',
                ],
            ],
            'cta' => [
                'Get exact pricing at a free consultation',
                'Start with a free consultation →',
            ],
        ],
    ],
];
