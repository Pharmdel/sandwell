<?php

/*
|--------------------------------------------------------------------------
| Service catalogue
|--------------------------------------------------------------------------
|
| One entry per service page, mirroring the catalogue published on
| sandwellpharmacygroup.co.uk/services. Pages that already have bespoke
| templates -- Pharmacy First, repeat prescriptions, the weight loss clinic
| and the conditions A-Z -- are listed in 'external' at the bottom so the
| hub and the menu can show the full set in one place.
|
| Prices are as published by the group. Confirm before launch.
|
*/

return [

    'services' => [
        'flu-covid-vaccinations' => [
        'booking_form' => 'flu-covid-booking',
        'checker' => [
            'type' => 'eligibility',
            'heading' => 'Check your eligibility',
            'lead' => 'Answer two quick questions — age first, then anything that applies — and we’ll tell you which jabs you can have free on the NHS this season.',
            'age_label' => 'How old is the person having the jab?',
            'reasons_label' => 'Do any of these apply? Tick all that do.',
            'reasons' => [
                [
                    'key' => 'pregnant',
                    'label' => 'I’m pregnant',
                ],
                [
                    'key' => 'condition',
                    'label' => 'I have a long-term health condition (e.g. asthma, COPD, diabetes, heart, liver or kidney disease)',
                ],
                [
                    'key' => 'immuno',
                    'label' => 'I have a weakened immune system (e.g. chemotherapy, transplant, immunosuppressant medicines)',
                ],
                [
                    'key' => 'carehome',
                    'label' => 'I live in a care home for older adults',
                ],
                [
                    'key' => 'carer',
                    'label' => 'I receive carer’s allowance, or I’m the main carer for an elderly or disabled person',
                ],
                [
                    'key' => 'contact',
                    'label' => 'I live with or care for someone with a weakened immune system',
                ],
                [
                    'key' => 'frontline',
                    'label' => 'I’m a frontline health or social care worker',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Flu & Covid Jabs',
            'sub' => 'Book online this season',
            'thumb' => 'flu-vaccine.jpg',
            'badge' => 'BOOK',
        ],
            'name' => 'Flu & Covid-19 vaccinations',
            'nav' => 'Flu & Covid-19 jabs',
            'group' => 'nhs',
            'image' => 'flu-vaccine.jpg',
            'title' => 'Flu & Covid jabs West Bromwich',
            'summary' => 'Free NHS jabs, plus private flu from £30.',
            'description' => 'Book your flu and Covid-19 vaccination across Hollytree Pharmacy. Free NHS jabs for eligible patients, private flu jabs £30.',
            'h1' => 'Flu & Covid-19 vaccinations, booked in under a minute',
            'highlight' => 'in under a minute',
            'intro' => 'Free NHS flu and Covid-19 jabs for eligible patients across Hollytree Pharmacy — plus private flu jabs for £30. Both jabs can be given together in one visit.',
            'badges' => [
                'Flu from 1 Sep · Covid-19 from 1 Oct',
                'Private flu jabs £30 — pay in store',
                'Appointments Mon–Fri, 10am–5pm',
            ],
            'items' => [
                'heading' => 'What we offer this season',
                'lead' => 'Every vaccination is given in our private consultation room by our pharmacist team. Appointments take around 15 minutes.',
                'label' => 'This season',
                'list' => [
                    [
                        'name' => 'NHS flu vaccination',
                        'price' => 'Free',
                        'body' => 'Free on the NHS if you are pregnant, aged 65 or over, in a clinical risk group, a carer, in long-stay residential care, a close contact of someone immunocompromised, or an eligible frontline health or social care worker.',
                        'bullets' => [
                            'Appointments start 1 October — pregnant women and children are eligible from September and can be vaccinated at their GP surgery before then',
                            'All other eligible adults from 1 October — timed so protection is strongest over the winter peak',
                            'Season runs until 31 March, but earlier is better',
                        ],
                    ],
                    [
                        'name' => 'Private flu vaccination',
                        'price' => '£30',
                        'body' => 'Not eligible for a free NHS jab? Anyone aged 18 or over can book a private flu vaccination.',
                        'bullets' => [
                            'Same quadrivalent vaccine used across the NHS programme',
                            'Available from 1 October — pay in store on the day',
                            'Ideal for office workers, students and anyone who just wants winter covered',
                        ],
                    ],
                    [
                        'name' => 'NHS Covid-19 vaccination',
                        'price' => 'Free',
                        'body' => 'This autumn the NHS programme covers adults aged 75 and over, residents in care homes for older adults, and anyone aged 6 months and over who is immunosuppressed.',
                        'bullets' => [
                            'Programme runs 1 October – 31 January',
                            'Can be given at the same visit as your flu jab',
                        ],
                    ],
                    [
                        'name' => 'Flu + Covid-19 together',
                        'price' => 'One visit',
                        'body' => 'Co-administration is recommended by the NHS where possible — it is safe, and saves you a second trip.',
                        'bullets' => [
                            'One 15-minute appointment, one jab in each arm',
                            'Bookable from 1 October while the Covid-19 programme is running',
                        ],
                    ],
                ],
            ],
            'cta' => [
                'heading' => 'Book your appointment',
                'body' => 'Appointments run Monday to Friday, 10am–5pm, every 15 minutes. Need to cancel or change your time? Just call the pharmacy.',
                'label' => 'Book an appointment',
                'route' => 'book',
            ],
            'related' => [
                [
                    'Travel vaccinations',
                    'services.show',
                    'travel-vaccinations',
                ],
            ],
        ],
        'contraception' => [
        'form' => [
            'heading' => 'Request a consultation',
            'button' => 'Request consultation',
            'lead' => 'Call us or request a callback and our pharmacist will arrange your consultation at a convenient time. All information is kept strictly confidential.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'dob',
                    'type' => 'date',
                    'label' => 'Date of birth',
                    'required' => true,
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'Service required',
                    'required' => true,
                    'options' => [
                        'Emergency contraception (EHC)',
                        'Daily contraceptive pill',
                        'Both / not sure',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Any notes (optional)',
                    'placeholder' => 'e.g. currently taking Cerazette and need a repeat supply',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Contraception',
            'sub' => 'Free NHS oral contraception',
            'thumb' => 'consult-room.jpg',
        ],
            'name' => 'Contraception',
            'nav' => 'Contraception',
            'group' => 'nhs',
            'image' => 'consult-room.jpg',
            'title' => 'NHS contraception service West Bromwich',
            'summary' => 'Start or continue the pill free on the NHS.',
            'description' => 'Start or continue oral contraception free on the NHS across Hollytree Pharmacy, with a private pharmacist consultation. No GP appointment needed.',
            'h1' => 'Contraception services',
            'highlight' => 'services',
            'intro' => 'Emergency hormonal contraception and ongoing daily contraception — both available free on the NHS from Hollytree Pharmacy. Confidential, non-judgemental care.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'how' => [
                'heading' => 'Emergency hormonal contraception (EHC)',
                'lead' => 'Also known as the morning after pill. Available free on the NHS — no GP appointment needed. Come in as soon as possible for the best effectiveness.',
                'steps' => [
                    [
                        'Come in as soon as possible',
                        'EHC is most effective when taken as soon as possible after unprotected sex. The sooner the better — do not wait.',
                    ],
                    [
                        'Speak to our pharmacist',
                        'Our pharmacist will ask a few confidential questions to ensure the right treatment for you. This is completely private.',
                    ],
                    [
                        'Receive your medication',
                        'If appropriate, you will be supplied with EHC free of charge on the NHS. No prescription or GP visit needed.',
                    ],
                ],
            ],
            'notes' => [
                'heading' => 'Important information',
                'list' => [
                    'Levonorgestrel (Levonelle) is effective up to 72 hours after unprotected sex',
                    'Ulipristal acetate (ellaOne) is effective up to 120 hours (5 days) after unprotected sex',
                    'EHC is not a regular method of contraception',
                    'You must be 16 or over to access this service',
                    'EHC does not protect against sexually transmitted infections (STIs)',
                ],
            ],
            'items' => [
                'heading' => 'Daily contraceptive pill',
                'lead' => 'We supply progestogen-only contraceptive pills (mini pills) free on the NHS under the Pharmacy Contraception Service. Up to 6 months supply per consultation, for women aged 16 and over. Our pharmacist carries out the consultation, checks your blood pressure and supplies your chosen pill.',
                'label' => 'Pills we supply',
                'list' => [
                    [
                        'name' => 'Desogestrel',
                        'price' => 'Free on the NHS',
                        'body' => 'Once daily. Suitable for most women including those who cannot take oestrogen. One of the most commonly prescribed mini pills.',
                    ],
                    [
                        'name' => 'Norethisterone',
                        'price' => 'Free on the NHS',
                        'body' => 'Once daily at the same time each day. Must be taken within a strict 3-hour window.',
                    ],
                    [
                        'name' => 'Levonorgestrel',
                        'price' => 'Free on the NHS',
                        'body' => 'Once daily. An older progestogen-only pill, taken within a 3-hour window daily.',
                    ],
                ],
            ],
            'eligibility' => [
                'heading' => 'Eligibility',
                'list' => [
                    'Must be 16 years of age or over',
                    'Currently taking or previously taken the pill (or suitable for initiation)',
                    'No contraindications identified during consultation',
                    'Blood pressure check required before supply',
                    'Up to 6 months supply provided per consultation',
                ],
            ],
            'cta' => [
                'heading' => 'Speak to our pharmacist',
                'body' => 'Our pharmacist is here to help with any questions about contraception in complete confidence. All consultations are completely confidential.',
                'label' => 'Request a consultation',
            ],
        ],
        'ear-infection' => [
        'form' => [
            'heading' => 'Ask us about this service',
            'button' => 'Request a call back',
            'lead' => 'Leave your details and our pharmacist will be in touch. It is completely free on the NHS, and your details are never shared with third parties.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'What you would like to talk to our pharmacist about',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Ear Infection Treatment',
            'sub' => 'Earache treated on the spot',
            'thumb' => 'mother-toddler.jpg',
        ],
            'name' => 'Ear infection treatment',
            'nav' => 'Ear infection treatment',
            'group' => 'nhs',
            'image' => 'mother-toddler.jpg',
            'title' => 'Ear infection treatment West Bromwich',
            'summary' => 'Earache assessed and treated on the spot.',
            'description' => 'Earache and ear infections assessed and treated across Hollytree Pharmacy — free on the NHS for ages 1–17 under Pharmacy First. No GP appointment.',
            'h1' => 'Earache and ear infections, treated on the spot',
            'highlight' => 'on the spot',
            'intro' => 'Ear infections assessed and treated by our pharmacist — free on the NHS for children and young people aged 1–17 under Pharmacy First, with no GP appointment needed.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'Walk in — most people are seen, assessed and treated in one visit.',
                'steps' => [
                    [
                        'A quick chat',
                        'A quick chat about the pain, hearing changes and how long it has been going on.',
                    ],
                    [
                        'Otoscope examination',
                        'Our pharmacist examines the ear with an otoscope to check the ear canal and eardrum.',
                    ],
                    [
                        'Treatment',
                        'Antibiotics, drops or pain relief where appropriate — plus clear advice on what to watch for.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Fast relief, done properly',
                'paras' => [
                    'Middle-ear infections (acute otitis media) are one of the seven conditions pharmacists can assess and treat under NHS Pharmacy First — no GP appointment needed for children and teenagers aged 1 to 17.',
                    'Adults with earache, outer-ear infections or wax-related blockages can be seen privately — and if wax is the culprit, ear syringing is available at our Hollytree Pharmacy branch.',
                ],
            ],
            'cta' => [
                'heading' => 'Book an ear assessment',
                'body' => 'Leave your details and we will be in touch to arrange a time that suits you.',
                'label' => 'Get seen today',
            ],
            'related' => [
                [
                    'Earache symptom check',
                    'conditions.show',
                    'earache',
                ],
                [
                    'Pharmacy First',
                    'pharmacy-first',
                    null,
                ],
            ],
        ],
        'hypertension' => [
        'checker' => [
            'js' => 'bp',
            'heading' => 'Do I qualify for a free NHS check?',
            'lead' => 'Three quick questions — takes about twenty seconds.',
            'steps' => [
                [
                    'key' => 'age',
                    'question' => 'Are you aged 40 or over?',
                    'options' => [
                        [
                            'value' => 'yes',
                            'label' => 'Yes, I’m 40 or over',
                        ],
                        [
                            'value' => 'no',
                            'label' => 'No, I’m under 40',
                        ],
                    ],
                ],
                [
                    'key' => 'meds',
                    'question' => 'Are you currently taking blood pressure medication?',
                    'options' => [
                        [
                            'value' => 'no',
                            'label' => 'No',
                        ],
                        [
                            'value' => 'yes',
                            'label' => 'Yes',
                            'note' => 'Your GP or our pharmacist monitors you separately',
                        ],
                    ],
                ],
                [
                    'key' => 'last5',
                    'question' => 'Have you had a blood pressure check at a pharmacy in the last 5 years?',
                    'options' => [
                        [
                            'value' => 'no',
                            'label' => 'No, or I can’t remember',
                        ],
                        [
                            'value' => 'yes',
                            'label' => 'Yes, within 5 years',
                            'note' => 'The NHS covers one pharmacy check every 5 years',
                        ],
                    ],
                ],
            ],
        ],
        'menu' => [
            'title' => 'Blood Pressure Checks',
            'sub' => 'Free checks + 24hr monitoring',
            'thumb' => 'consult-room.jpg',
        ],
            'name' => 'Blood pressure checks',
            'nav' => 'Blood pressure checks',
            'group' => 'nhs',
            'image' => 'consult-room.jpg',
            'title' => 'Blood pressure check West Bromwich',
            'summary' => 'Free NHS checks, plus monitoring and home testing.',
            'description' => 'Blood pressure checks in West Bromwich: free NHS checks for over-40s, £5 private checks for everyone, £50 24-hour monitoring and home monitors.',
            'h1' => 'Blood pressure checks, free on the NHS',
            'highlight' => 'free on the NHS',
            'intro' => 'High blood pressure usually has no symptoms — a free five-minute check at our pharmacy could catch it early. No appointment needed, just walk in.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'items' => [
                'heading' => 'Checks, monitoring and home testing',
                'lead' => 'We offer free NHS blood pressure checks for adults aged 40 and over who have not had a pharmacy check in the past five years. Not eligible? Private checks are available to everyone from £5, with 24-hour monitoring and home monitors also on offer — no appointment needed.',
                'label' => 'What we offer',
                'list' => [
                    [
                        'name' => 'Free NHS blood pressure check',
                        'price' => 'Free',
                        'body' => 'A quick, painless check by our pharmacist that could catch hypertension before it causes problems.',
                        'bullets' => [
                            'Adults aged 40 and over',
                            'Not currently on blood pressure medication',
                            'No pharmacy BP check in the last 5 years',
                        ],
                    ],
                    [
                        'name' => 'Private check',
                        'price' => '£5',
                        'body' => 'Had a pharmacy check within the last 5 years, under 40, or already on BP medication? Get a same-day private check for a fiver — no appointment needed.',
                        'bullets' => [
                            'Anyone, any age — walk in',
                            'Results and advice on the spot',
                            'Pay in store — takes 5 minutes',
                        ],
                    ],
                    [
                        'name' => '24-hour monitoring (ABPM)',
                        'price' => '£50',
                        'body' => 'A discreet cuff records your blood pressure over a full day and night — a far more accurate picture than one clinic reading, and the gold standard for confirming hypertension.',
                        'bullets' => [
                            'No referral and no waiting list — book direct with us',
                            'Fitted in store in minutes, worn for a full 24 hours',
                            'Full written report, explained by our pharmacist',
                        ],
                    ],
                    [
                        'name' => 'Take home a clinically validated BP monitor',
                        'price' => 'Ask in branch',
                        'body' => 'Check your blood pressure whenever you want, from your own sofa. Our pharmacist sets it up with you and shows you how to take a proper reading.',
                        'bullets' => [
                            'Clinically validated upper-arm cuff',
                            'Unlimited checks at home — any time you like',
                            'Free in-store demo so your readings are accurate',
                        ],
                    ],
                ],
            ],
            'about' => [
                'heading' => 'High blood pressure, explained',
                'paras' => [
                    'Around a third of UK adults have high blood pressure and millions of them have no idea — because it almost never causes symptoms until it has already done damage.',
                    'Blood pressure is the force your blood puts on the walls of your arteries as your heart pumps it around your body. High blood pressure — hypertension — means that force stays too high for too long, and your heart and blood vessels have to work harder than they should.',
                    'It is measured as two numbers in millimetres of mercury (mmHg). The first, systolic, is the pressure as your heart beats. The second, diastolic, is the pressure as your heart relaxes between beats. Left untreated over months and years, raised pressure quietly damages the arteries and raises your risk of a heart attack, stroke, kidney disease and vascular dementia.',
                    'The good news: it is easily measured, and in most cases very treatable — often with lifestyle changes alone, and where needed with medication that works well.',
                ],
            ],
            'notes' => [
                'heading' => 'You may have high blood pressure if you have',
                'list' => [
                    'Headaches that keep coming back, particularly first thing in the morning',
                    'Dizziness, light-headedness or a general feeling of being off-colour',
                    'Blurred or disturbed vision',
                    'Shortness of breath on light activity',
                    'Nosebleeds, chest discomfort or a pounding in your chest, neck or ears',
                ],
                'foot' => 'Most people have none of these. That is exactly why it is nicknamed the silent killer, and why a five-minute check matters more than waiting to feel unwell.',
            ],
            'eligibility' => [
                'heading' => 'What helps bring it down',
                'list' => [
                    'Cut the salt. Aim under 6g a day — most of it hides in bread, sauces, ready meals and takeaways rather than the salt cellar.',
                    'Move more. 150 minutes of moderate activity a week — brisk walking counts, and it can take several points off your reading.',
                    'Lose a little weight if you carry extra. Even a few kilos makes a measurable difference.',
                    'Keep alcohol within 14 units a week and spread it out, with drink-free days.',
                    'Stop smoking. Every cigarette spikes your pressure, and quitting cuts your heart risk fast — ask us about support.',
                    'Sleep and stress. Poor sleep and constant pressure both push your numbers up over time.',
                ],
            ],
            'cta' => [
                'heading' => 'Walk in for a free blood pressure check',
                'body' => 'No appointment needed. Call us to enquire about 24-hour monitoring.',
                'label' => 'Request a call back',
            ],
        ],
        'mds-trays' => [
        'checker' => [
            'type' => 'quiz',
            'js' => 'mds',
            'heading' => 'Check if MDS trays are right for you',
            'lead' => 'Five quick questions — we’ll tell you straight away whether the free NHS route or priority setup fits best.',
            'steps' => [
                [
                    'key' => 'items',
                    'question' => 'How many prescription items are you on?',
                    'options' => [
                        [
                            'value' => '1-3',
                            'label' => '1–3 items',
                        ],
                        [
                            'value' => '4-6',
                            'label' => '4–6 items',
                        ],
                        [
                            'value' => '7+',
                            'label' => '7 or more',
                        ],
                    ],
                ],
                [
                    'key' => 'who',
                    'question' => 'Who manages your medicines day-to-day?',
                    'options' => [
                        [
                            'value' => 'myself',
                            'label' => 'I do',
                        ],
                        [
                            'value' => 'family',
                            'label' => 'A family member',
                        ],
                        [
                            'value' => 'carer',
                            'label' => 'A carer',
                        ],
                    ],
                ],
                [
                    'key' => 'issue',
                    'question' => 'How does your GP issue your prescriptions?',
                    'options' => [
                        [
                            'value' => 'weekly',
                            'label' => 'Weekly',
                        ],
                        [
                            'value' => 'monthly',
                            'label' => 'Monthly',
                        ],
                        [
                            'value' => 'other',
                            'label' => 'Other',
                        ],
                        [
                            'value' => 'unsure',
                            'label' => 'I’m not sure',
                        ],
                    ],
                ],
                [
                    'key' => 'miss',
                    'question' => 'Do you ever miss or muddle doses?',
                    'options' => [
                        [
                            'value' => 'often',
                            'label' => 'Often',
                        ],
                        [
                            'value' => 'sometimes',
                            'label' => 'Sometimes',
                        ],
                        [
                            'value' => 'rarely',
                            'label' => 'Rarely',
                        ],
                    ],
                ],
                [
                    'key' => 'route',
                    'question' => 'There are two routes onto trays:',
                    'options' => [
                        [
                            'value' => 'priority',
                            'label' => 'Priority setup — £25/month',
                            'note' => 'Start straight away, trays organised and ready every week, no waiting list',
                        ],
                        [
                            'value' => 'free',
                            'label' => 'Free NHS waiting list',
                            'note' => 'No charge — we’ll add you to the list and contact you when a place opens',
                        ],
                    ],
                ],
            ],
        ],
        'form' => [
            'heading' => 'Ask about MDS trays',
            'button' => 'Send my request',
            'lead' => 'Leave your details and our pharmacist will talk you through which route suits you — usually within one working day.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'Which route suits you?',
                    'required' => true,
                    'options' => [
                        'Free NHS waiting list',
                        'Priority setup — £25/month',
                        'Not sure — please advise',
                    ],
                ],
                [
                    'name' => 'address',
                    'type' => 'text',
                    'label' => 'Address (for delivery)',
                    'placeholder' => 'Including postcode',
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'e.g. how many items you take, who manages them',
                ],
            ],
        ],
        'menu' => [
            'title' => 'MDS Blister Packs',
            'sub' => 'Weekly medication trays',
            'thumb' => 'blister-pack.jpg',
        ],
            'name' => 'MDS blister packs',
            'nav' => 'MDS blister packs',
            'group' => 'nhs',
            'image' => 'blister-pack.jpg',
            'title' => 'MDS blister packs West Bromwich',
            'summary' => 'Medication organised by day and time.',
            'description' => 'Weekly MDS blister packs from Hollytree Pharmacy — medication organised by day and time. Free standard waiting list or £25/month priority service.',
            'h1' => 'MDS compliance trays',
            'highlight' => 'trays',
            'intro' => 'We organise your medicines into clearly labelled blister packs so you always take the right tablet at the right time.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'items' => [
                'heading' => 'Two ways to start',
                'lead' => 'Five quick questions tell us whether the free NHS route or priority setup fits best — or just ask our team in branch.',
                'label' => 'Your options',
                'list' => [
                    [
                        'name' => 'Free NHS waiting list',
                        'price' => 'No charge',
                        'body' => 'We add you to the list and contact you when a place opens. No charge at any point.',
                    ],
                    [
                        'name' => 'Priority setup',
                        'price' => '£25 per month',
                        'body' => 'Start straight away — trays organised and ready every week, with no waiting list.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Who they help',
                'paras' => [
                    'Blister packs suit anyone managing several medicines at different times of day, and anyone who finds it hard to keep track of what has been taken.',
                    'They are also a help for family members and carers, who can see at a glance whether a dose has been missed.',
                ],
            ],
            'cta' => [
                'heading' => 'Ask about MDS trays',
                'body' => 'Leave your details and our pharmacist will talk you through which route suits you.',
                'label' => 'Request a call back',
            ],
        ],
        'nms' => [
        'form' => [
            'heading' => 'Ask us about this service',
            'button' => 'Request a call back',
            'lead' => 'Leave your details and our pharmacist will be in touch. It is completely free on the NHS, and your details are never shared with third parties.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'What you would like to talk to our pharmacist about',
                ],
            ],
        ],
        'menu' => [
            'title' => 'New Medicine Service',
            'sub' => 'Support with a new medicine',
            'thumb' => 'consult-room.jpg',
        ],
            'name' => 'New Medicine Service',
            'nav' => 'New Medicine Service',
            'group' => 'nhs',
            'image' => 'consult-room.jpg',
            'title' => 'New Medicine Service West Bromwich',
            'summary' => 'Pharmacist check-ins when you start a new medicine.',
            'description' => 'Free NHS New Medicine Service across Hollytree Pharmacy — one-to-one pharmacist check-ins when you start a new medicine for a long-term condition.',
            'h1' => 'Starting a new medicine? We will help it work for you',
            'highlight' => 'work for you',
            'intro' => 'The New Medicine Service gives you free one-to-one check-ins with our pharmacist when you start a new medicine for a long-term condition — so you get the most from it, right from the start.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'All free on the NHS — in person at the pharmacy or over the phone, whichever suits you.',
                'steps' => [
                    [
                        'We enrol you',
                        'When you collect a newly prescribed medicine for an eligible condition, we will offer to enrol you — it takes seconds.',
                    ],
                    [
                        'A check-in',
                        'Our pharmacist checks in: how are you getting on? Any side effects, questions or trouble remembering doses?',
                    ],
                    [
                        'A follow-up',
                        'A final catch-up to make sure everything has settled and the medicine is working as it should.',
                    ],
                ],
            ],
            'items' => [
                'heading' => 'Conditions covered',
                'lead' => 'The service covers new medicines prescribed for many long-term conditions. Not sure if your new medicine qualifies? Just ask — our pharmacist will tell you on the spot when you collect your prescription.',
                'label' => 'Commonly covered',
                'list' => [
                    [
                        'name' => 'Asthma and COPD',
                        'body' => 'Inhalers and other new respiratory medicines, including inhaler technique checks.',
                    ],
                    [
                        'name' => 'Type 2 diabetes',
                        'body' => 'Newly started tablets and injectables, with advice on timing and side effects.',
                    ],
                    [
                        'name' => 'High blood pressure',
                        'body' => 'New antihypertensives — what to expect in the first few weeks.',
                    ],
                    [
                        'name' => 'High cholesterol',
                        'body' => 'Statins and other lipid-lowering medicines.',
                    ],
                    [
                        'name' => 'Blood-thinning medicines',
                        'body' => 'Anticoagulants and antiplatelets, including what to watch for.',
                    ],
                    [
                        'name' => 'Osteoporosis and gout',
                        'body' => 'Newly prescribed long-term treatment for bone and joint conditions.',
                    ],
                ],
            ],
            'cta' => [
                'heading' => 'Ask us about this service',
                'body' => 'Leave your details and our pharmacist will be in touch. It is completely free on the NHS, and your details are never shared with third parties.',
                'label' => 'Request a call back',
            ],
        ],
        'dms' => [
        'form' => [
            'heading' => 'Ask us about this service',
            'button' => 'Request a call back',
            'lead' => 'Leave your details and our pharmacist will be in touch. It is completely free on the NHS, and your details are never shared with third parties.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'What you would like to talk to our pharmacist about',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Discharge Medicines Service',
            'sub' => 'Medicines checked after hospital',
            'thumb' => 'team.jpg',
        ],
            'name' => 'Discharge Medicines Service',
            'nav' => 'Discharge Medicines Service',
            'group' => 'nhs',
            'image' => 'team.jpg',
            'title' => 'Discharge Medicines Service West Bromwich',
            'summary' => 'Medicines checked after a hospital stay.',
            'description' => 'Just out of hospital? The free NHS Discharge Medicines Service across Hollytree Pharmacy — our pharmacist reviews your medicine changes and keeps you safe.',
            'h1' => 'Just out of hospital? We will get your medicines right',
            'highlight' => 'medicines right',
            'intro' => 'Medicines often change during a hospital stay. Through the Discharge Medicines Service, our pharmacist reviews those changes with you and makes sure everything is safe, correct and understood — free on the NHS.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'Most referrals reach us electronically from the hospital before you are even home.',
                'steps' => [
                    [
                        'The hospital refers you',
                        'When you are discharged, the hospital sends us details of your stay and any medicine changes, securely and electronically.',
                    ],
                    [
                        'We check the changes',
                        'Our pharmacist compares your new medicines against what you were taking before, and checks your first prescription after discharge extra carefully.',
                    ],
                    [
                        'We talk it through',
                        'We make sure you understand what has changed, what has stopped and why — and answer any questions. It keeps you safe and out of hospital.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Not sure you were referred?',
                'paras' => [
                    'If you or someone you care for has been discharged in the last few weeks and your medicines have changed, let us know — even if you are not sure whether the hospital referred you.',
                    'Our pharmacist can check and make sure nothing falls through the cracks.',
                ],
            ],
            'cta' => [
                'heading' => 'Ask us about this service',
                'body' => 'Leave your details and our pharmacist will be in touch. It is completely free on the NHS, and your details are never shared with third parties.',
                'label' => 'Request a call back',
            ],
        ],
        'medicine-review' => [
        'form' => [
            'heading' => 'Ask us about this service',
            'button' => 'Request a call back',
            'lead' => 'Leave your details and our pharmacist will be in touch. It is completely free on the NHS, and your details are never shared with third parties.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'What you would like to talk to our pharmacist about',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Prescription Review',
            'sub' => 'Only order what you need',
            'thumb' => 'blister-pack.jpg',
        ],
            'name' => 'Prescription review',
            'nav' => 'Prescription review',
            'group' => 'nhs',
            'image' => 'blister-pack.jpg',
            'title' => 'Prescription review — reduce medicine waste',
            'summary' => 'Tell us what you already have at home.',
            'description' => 'Tell Hollytree Pharmacy which medicines you already have, have stopped or want paused — so we only order what you actually need.',
            'h1' => 'Tell us what you already have at home',
            'highlight' => 'already have at home',
            'intro' => 'If you have a cupboard full of medicines you are not using, let us know. We will only order what you actually need — which keeps your prescriptions accurate and cuts NHS waste.',
            'badges' => [
                'Free on the NHS',
                'No GP appointment needed',
                'Walk in or enquire online',
            ],
            'about' => [
                'heading' => 'Why it matters',
                'paras' => [
                    'Repeat prescriptions can drift out of step with what you actually take. Items get ordered automatically, pile up unopened, and eventually have to be destroyed — medicines returned to a pharmacy cannot be reused, even unopened.',
                    'A quick review with our team keeps your repeat list matched to what you are really taking, and means the items you do need are ready when you need them.',
                ],
            ],
            'cta' => [
                'heading' => 'Tell us what you have',
                'body' => 'Let our team know which medicines you already have, have stopped, or want paused on your repeat list.',
                'label' => 'Request a call back',
            ],
            'related' => [
                [
                    'Repeat prescriptions',
                    'repeat-prescriptions',
                    null,
                ],
            ],
        ],
        'travel-vaccinations' => [
        'form' => [
            'heading' => 'Travel vaccination enquiry',
            'button' => 'Submit enquiry',
            'lead' => 'Tell us about your trip and we’ll advise on what you need. We’ll contact you within one working day — nothing is charged until your appointment.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email address (optional)',
                    'placeholder' => 'your@email.com',
                ],
                [
                    'name' => 'detail',
                    'type' => 'text',
                    'label' => 'Country travelling to',
                    'required' => true,
                    'placeholder' => 'e.g. Thailand, Kenya, India',
                    'half' => true,
                ],
                [
                    'name' => 'region',
                    'type' => 'text',
                    'label' => 'City / region',
                    'placeholder' => 'e.g. Bangkok, rural areas',
                    'half' => true,
                ],
                [
                    'name' => 'travel_date',
                    'type' => 'date',
                    'label' => 'Planned travel date',
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Do you know which vaccines you need? (optional)',
                    'placeholder' => 'e.g. I think I need hepatitis A and typhoid',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Travel Vaccinations',
            'sub' => 'Every destination covered',
            'thumb' => 'flu-vaccine.jpg',
        ],
            'name' => 'Travel vaccinations',
            'nav' => 'Travel vaccinations',
            'group' => 'private',
            'image' => 'flu-vaccine.jpg',
            'title' => 'Travel vaccinations West Bromwich',
            'summary' => 'Every destination covered, from Hajj to backpacking.',
            'description' => 'Travel vaccination clinic in West Bromwich — hepatitis A, typhoid, yellow fever certificate, meningitis ACWY, rabies and anti-malarials.',
            'h1' => 'Travel vaccinations, ready for take-off',
            'highlight' => 'ready for take-off',
            'intro' => 'Travelling abroad? Our private travel clinic covers the common travel vaccines — tell us your destination and travel dates, and our pharmacist will advise exactly what you need.',
            'badges' => [
                'GPhC-registered pharmacist care',
                'Private consultation room',
                'Same-day appointments available',
            ],
            'destinations' => [
                'heading' => 'Where our patients travel — and what they usually need',
                'lead' => 'A guide only. Ask for a call back and our pharmacist will confirm the schedule for your exact trip.',
                'list' => [
                    [
                        'name' => 'Saudi Arabia · Hajj & Umrah',
                        'body' => 'Meningitis ACWY certificate is mandatory for your visa — issued no more than 5 years and no less than 10 days before you arrive.',
                        'bullets' => [
                            'Meningitis ACWY £45.00',
                            'Hepatitis A £65.00',
                            'Tetanus (Td/IPV) booster £35.00',
                        ],
                    ],
                    [
                        'name' => 'Punjab · India',
                        'body' => 'Visiting family often means village stays and home-cooked food — the risks differ from a hotel holiday, so tell us your plans.',
                        'bullets' => [
                            'Hepatitis A £65.00',
                            'Typhoid £35.00',
                            'Tetanus (Td/IPV) booster £35.00',
                        ],
                    ],
                    [
                        'name' => 'Thailand & Southeast Asia',
                        'body' => 'Cities and beach resorts usually need no malaria tablets — backpacking, islands and border areas can be a different story.',
                        'bullets' => [
                            'Hepatitis A £65.00',
                            'Typhoid £35.00',
                            'Tetanus (Td/IPV) booster £35.00',
                        ],
                    ],
                    [
                        'name' => 'Pakistan · Mirpur, Lahore & the north',
                        'body' => 'Family trips often mean village water, home cooking and longer stays — a different risk picture to a hotel holiday.',
                        'bullets' => [
                            'Hepatitis A £65.00',
                            'Typhoid £35.00',
                            'Tetanus (Td/IPV) booster £35.00',
                        ],
                    ],
                    [
                        'name' => 'Bangladesh · Sylhet & Dhaka',
                        'body' => 'Long family visits, rural districts and monsoon season all change what you need — tell us when you are going.',
                        'bullets' => [
                            'Hepatitis A £65.00',
                            'Typhoid £35.00',
                            'Tetanus (Td/IPV) booster £35.00',
                        ],
                    ],
                    [
                        'name' => 'Dubai & the UAE',
                        'body' => 'No vaccines are required for entry and there is no malaria — but it is a good moment to check your routine boosters are current.',
                        'bullets' => [
                            'Tetanus (Td/IPV) booster £35.00',
                            'Hepatitis A £65.00',
                        ],
                    ],
                    [
                        'name' => 'Nigeria · Lagos & beyond',
                        'body' => 'A yellow fever certificate is required for entry, and malaria tablets are advised throughout the country — start early.',
                        'bullets' => [
                            'Yellow Fever (certificate) £65.00',
                            'Hepatitis A £65.00',
                            'Typhoid £35.00',
                            'Anti-malarial tablets from £30.00',
                        ],
                    ],
                    [
                        'name' => 'Turkey · coast & cities',
                        'body' => 'Resorts and cities need little beyond routine cover. Travelling for dental or cosmetic treatment? Ask us about hepatitis B before you go.',
                        'bullets' => [
                            'Tetanus (Td/IPV) booster £35.00',
                            'Hepatitis A £65.00',
                        ],
                    ],
                ],
            ],
            'items' => [
                'heading' => 'Our vaccine menu',
                'lead' => 'Prices are per dose unless stated. Contact us to confirm availability and exactly what your trip needs. Some courses need to start 4–6 weeks before you fly, so check early.',
                'label' => 'Vaccines we offer',
                'list' => [
                    [
                        'name' => 'Hepatitis A',
                        'price' => '£65.00',
                        'body' => 'Single dose plus booster protection against food- and water-borne hepatitis A. Paediatric dose £55.',
                    ],
                    [
                        'name' => 'Hepatitis B',
                        'price' => 'Ask in branch',
                        'body' => '3-dose course for full protection.',
                    ],
                    [
                        'name' => 'Typhoid',
                        'price' => '£35.00',
                        'body' => 'Oral or injection — around 3 years of protection.',
                    ],
                    [
                        'name' => 'Tetanus (Td/IPV)',
                        'price' => '£35.00',
                        'body' => 'Combined tetanus, diphtheria and polio booster for travellers and unvaccinated adults.',
                    ],
                    [
                        'name' => 'Meningitis ACWY',
                        'price' => '£45.00',
                        'body' => 'Single dose — a certificate is mandatory for Hajj and Umrah visas.',
                    ],
                    [
                        'name' => 'Yellow Fever',
                        'price' => '£65.00',
                        'body' => 'Required for entry to some countries — internationally recognised certificate issued, valid for life.',
                    ],
                    [
                        'name' => 'Chickenpox',
                        'price' => 'Ask in branch',
                        'body' => 'Two-dose course for children and adults who have not had chickenpox.',
                    ],
                    [
                        'name' => 'Cholera',
                        'price' => 'Ask in branch',
                        'body' => 'Oral vaccine, two doses — protection for high-risk travel and work.',
                    ],
                    [
                        'name' => 'Diphtheria, Tetanus & Polio',
                        'price' => '£35.00',
                        'body' => 'Single combined booster covering all three.',
                    ],
                    [
                        'name' => 'Hepatitis B Paediatric',
                        'price' => 'Ask in branch',
                        'body' => 'Child dosing of the 3-dose hepatitis B course.',
                    ],
                    [
                        'name' => 'Japanese Encephalitis',
                        'price' => 'Ask in branch',
                        'body' => 'Two-dose course for rural Asia travel.',
                    ],
                    [
                        'name' => 'MMR',
                        'price' => 'Ask in branch',
                        'body' => 'Measles, mumps and rubella — two doses if not previously vaccinated.',
                    ],
                    [
                        'name' => 'Meningitis B',
                        'price' => 'Ask in branch',
                        'body' => 'Protection against meningococcal group B disease.',
                    ],
                    [
                        'name' => 'Rabies',
                        'price' => 'Ask in branch',
                        'body' => 'Three-dose pre-exposure course.',
                    ],
                    [
                        'name' => 'Tick-Borne Encephalitis',
                        'price' => 'Ask in branch',
                        'body' => 'Course for forest and rural travel in risk regions.',
                    ],
                    [
                        'name' => 'Anti-malarials',
                        'price' => 'From £30.00',
                        'body' => 'Tablets to prevent malaria — doxycycline £30, atovaquone/proguanil £36, Malarone £40 (paediatric £30). The right option depends on your destination, itinerary and medical history.',
                    ],
                ],
            ],
            'cta' => [
                'heading' => 'Tell us where you are travelling',
                'body' => 'Tell us about your trip and our pharmacist will advise on what you need. We will contact you within one working day. Nothing is charged until your appointment.',
                'label' => 'Request a travel consultation',
            ],
            'related' => [
                [
                    'Flu & Covid-19 vaccinations',
                    'services.show',
                    'flu-covid-vaccinations',
                ],
                [
                    'Chickenpox vaccination',
                    'services.show',
                    'chickenpox-vaccination',
                ],
            ],
        ],
        'acne' => [
        'form' => [
            'heading' => 'Book your skin consultation',
            'lead' => 'Leave your details and we’ll be in touch to arrange a time that suits you.',
            'button' => 'Book a skin consultation',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                    'autocomplete' => 'email',
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'How long have you had acne?',
                    'required' => true,
                    'options' => [
                        'Under 6 months',
                        '6–24 months',
                        'Over 2 years',
                    ],
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'Anything that would help us prepare for your appointment',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Acne Treatment',
            'sub' => 'Pharmacist-led skin care',
            'thumb' => 'consult-room.jpg',
        ],
            'name' => 'Acne treatment',
            'nav' => 'Acne treatment',
            'group' => 'private',
            'image' => 'consult-room.jpg',
            'title' => 'Acne treatment West Bromwich',
            'summary' => 'Pharmacist-led treatment plans for teens and adults.',
            'description' => 'Pharmacist-led acne treatment across Hollytree Pharmacy — evidence-based treatment plans for teens and adults with regular reviews.',
            'h1' => 'Acne treatment, clearer skin ahead',
            'highlight' => 'clearer skin ahead',
            'intro' => 'Effective, evidence-based acne treatment from our pharmacist — from targeted creams and gels to practical skincare advice, with reviews to keep progress on track.',
            'badges' => [
                'GPhC-registered pharmacist care',
                'Private consultation room',
                'Same-day appointments available',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'A proper plan — not just something off the shelf.',
                'steps' => [
                    [
                        'Assessment',
                        'Our pharmacist assesses your skin, your history and anything you have already tried.',
                    ],
                    [
                        'Your plan',
                        'A tailored, evidence-based plan — with honest advice on what will and will not help.',
                    ],
                    [
                        'Review',
                        'Acne treatment takes weeks, not days — we review progress and adjust as your skin responds.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'More than a skincare aisle',
                'paras' => [
                    'Most acne can be managed well with the right combination of treatments — the key is matching the treatment to your skin and sticking with it long enough for it to work.',
                    'Severe, scarring or persistent acne sometimes needs a GP or dermatologist — if that is you, we will say so and point you in the right direction rather than sell you something that will not help.',
                ],
            ],
            'cta' => [
                'heading' => 'Book your skin consultation',
                'body' => 'Leave your details and we will be in touch to arrange a time that suits you.',
                'label' => 'Book a skin consultation',
            ],
            'related' => [
                [
                    'Acne symptom check',
                    'conditions.show',
                    'acne',
                ],
            ],
        ],
        'hair-loss' => [
        'form' => [
            'heading' => 'Book your hair loss consultation',
            'lead' => 'Leave your details and we’ll be in touch to arrange a time that suits you.',
            'button' => 'Book a private consultation',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                    'autocomplete' => 'email',
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'How long have you noticed hair loss?',
                    'required' => true,
                    'options' => [
                        'Under 1 year',
                        '1–5 years',
                        'Over 5 years',
                    ],
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'Anything that would help us prepare for your appointment',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Hair Loss Treatment',
            'sub' => 'Finasteride & minoxidil',
            'thumb' => 'consult-room.jpg',
        ],
            'name' => 'Male hair loss',
            'nav' => 'Male hair loss',
            'group' => 'private',
            'image' => 'consult-room.jpg',
            'title' => 'Male hair loss treatment West Bromwich',
            'summary' => 'Finasteride and minoxidil, discreetly.',
            'description' => 'Discreet, pharmacist-led male hair loss treatment across Hollytree Pharmacy. Private consultation, clinically proven treatments and ongoing reviews.',
            'h1' => 'Male hair loss, treated discreetly',
            'highlight' => 'treated discreetly',
            'intro' => 'Clinically proven treatment for male pattern hair loss, supplied by our pharmacist after a private consultation — no GP visit, no awkward conversations.',
            'badges' => [
                'GPhC-registered pharmacist care',
                'Private consultation room',
                'Same-day appointments available',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'A straightforward, judgement-free process from start to finish.',
                'steps' => [
                    [
                        'Private consultation',
                        'A confidential chat in our consultation room about your hair loss, your goals and which treatments suit you.',
                    ],
                    [
                        'Same-day supply',
                        'If suitable, your treatment is supplied the same day from our dispensary with clear instructions.',
                    ],
                    [
                        'Ongoing review',
                        'We keep an eye on progress together and adjust your plan as results come through.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Proven treatment, honest advice',
                'paras' => [
                    'Male pattern hair loss affects around half of men by the age of 50. Proven daily treatments can slow, stop, and in many cases partially reverse it — and the earlier you start, the better the result tends to be.',
                    'Our pharmacist will talk through the options, realistic expectations and possible side effects honestly, then keep an eye on progress with regular reviews.',
                ],
            ],
            'cta' => [
                'heading' => 'Book your hair loss consultation',
                'body' => 'Leave your details and we will be in touch to arrange a time that suits you.',
                'label' => 'Book a private consultation',
            ],
        ],
        'period-delay' => [
        'form' => [
            'heading' => 'Book your period delay consultation',
            'lead' => 'Leave your details and we’ll be in touch to arrange a time that suits you.',
            'button' => 'Book a consultation',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                    'autocomplete' => 'email',
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'When is your period due?',
                    'required' => true,
                    'options' => [
                        'Within 3 days',
                        'Within a week',
                        '1–2 weeks',
                        'More than 2 weeks',
                    ],
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'Anything that would help us prepare for your appointment',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Period Delay',
            'sub' => 'Plan around big events',
            'thumb' => 'weightloss-outdoor.jpg',
        ],
            'name' => 'Period delay',
            'nav' => 'Period delay',
            'group' => 'private',
            'image' => 'weightloss-outdoor.jpg',
            'title' => 'Period delay tablets West Bromwich',
            'summary' => 'Plan around holidays, weddings and exams.',
            'description' => 'Delay your period safely for holidays, weddings or events with a quick, confidential pharmacist consultation across Hollytree Pharmacy.',
            'h1' => 'Delay your period, for the moments that matter',
            'highlight' => 'that matter',
            'intro' => 'Holiday, wedding, exams or a big event — safe prescription tablets can delay your period by up to 17 days. Quick, confidential consultation with our pharmacist, no GP needed.',
            'badges' => [
                'GPhC-registered pharmacist care',
                'Private consultation room',
                'Same-day appointments available',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'In and out in minutes — ideally start three days before your period is due.',
                'steps' => [
                    [
                        'A quick check',
                        'A few short, confidential questions with our pharmacist to check the treatment is safe for you.',
                    ],
                    [
                        'Supplied on the spot',
                        'Your tablets are supplied on the spot with clear instructions on when to start and stop.',
                    ],
                    [
                        'Back to normal',
                        'Your period usually returns two to three days after you stop taking the tablets.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Plan around your cycle',
                'paras' => [
                    'Norethisterone is a prescription-only tablet taken three times a day, starting three days before your period is due. It works by holding your hormone levels steady so your period does not start until you stop taking it.',
                    'It is not suitable for everyone — our pharmacist will run through a few quick questions to check it is safe for you, including any history of blood clots and your current medicines.',
                ],
            ],
            'cta' => [
                'heading' => 'Book your period delay consultation',
                'body' => 'Leave your details and we will be in touch to arrange a time that suits you.',
                'label' => 'Book a consultation',
            ],
        ],
        'b12-injections' => [
        'form' => [
            'heading' => 'Book your B12 injection',
            'lead' => 'Leave your details and we’ll be in touch to arrange a time that suits you. £30 per dose.',
            'button' => 'Register interest · £30 per dose',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                    'autocomplete' => 'email',
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'Have you had B12 injections before?',
                    'required' => true,
                    'options' => [
                        'Yes — I’m on regular maintenance doses',
                        'Yes — but not currently',
                        'No — never',
                    ],
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'Anything that would help us prepare for your appointment',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Vitamin B12 Injections',
            'sub' => 'Energy top-ups in store',
            'thumb' => 'consult-room.jpg',
        ],
            'name' => 'Vitamin B12 injections',
            'nav' => 'Vitamin B12 injections',
            'group' => 'private',
            'image' => 'consult-room.jpg',
            'title' => 'B12 injections West Bromwich — £30',
            'summary' => 'Maintenance doses, £30 each, no GP referral.',
            'description' => 'Private vitamin B12 maintenance injections across Hollytree Pharmacy — £30 per dose, administered by our pharmacist. No GP referral needed.',
            'h1' => 'Vitamin B12 injections, without the wait',
            'highlight' => 'without the wait',
            'intro' => 'Maintenance B12 injections from our trained pharmacist for £30 per dose — quick, convenient and with no GP referral needed. Walk in or register below.',
            'badges' => [
                'GPhC-registered pharmacist care',
                'Private consultation room',
                'Same-day appointments available',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'In and out in minutes — most appointments take less than a quarter of an hour.',
                'steps' => [
                    [
                        'Suitability check',
                        'Our pharmacist completes a short consultation to confirm B12 maintenance injections are right for you.',
                    ],
                    [
                        'Your injection',
                        'Your B12 maintenance dose is administered by our trained pharmacist in our private consultation room.',
                    ],
                    [
                        'Next dose noted',
                        'We note when your next maintenance dose is due, so keeping on top of your B12 is effortless.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Maintenance doses, made easy',
                'paras' => [
                    'Vitamin B12 keeps your nerves and blood cells healthy, and many people rely on regular top-up injections to keep their levels steady. Our private service is designed for adults who are already established on B12 maintenance therapy and want a faster, more convenient way to stay on schedule.',
                    'Our pharmacist completes a short suitability check before every injection. If you have never had B12 treatment before, or you think you may be deficient, please speak to your GP first — diagnosis and initial loading doses are not part of this service.',
                ],
            ],
            'cta' => [
                'heading' => 'Book your B12 injection',
                'body' => 'Leave your details and we will be in touch to arrange a time that suits you. £30 per dose.',
                'label' => 'Register interest',
            ],
        ],
        'chickenpox-vaccination' => [
        'form' => [
            'heading' => 'Register for chickenpox vaccination',
            'lead' => 'Leave your details and we’ll be in touch to arrange a time that suits you.',
            'button' => 'Register interest',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                    'autocomplete' => 'email',
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'Who is the vaccination for?',
                    'required' => true,
                    'options' => [
                        'My child',
                        'Myself',
                        'Several people',
                    ],
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (9am–12pm)',
                        'Afternoon (12pm–3pm)',
                        'Late afternoon (3pm–6pm)',
                        'Saturday morning',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'Anything that would help us prepare for your appointment',
                ],
            ],
        ],
        'menu' => [
            'title' => 'Chickenpox Vaccination',
            'sub' => 'Private varicella jab',
            'thumb' => 'mother-toddler.jpg',
        ],
            'name' => 'Chickenpox vaccination',
            'nav' => 'Chickenpox vaccination',
            'group' => 'private',
            'image' => 'mother-toddler.jpg',
            'title' => 'Chickenpox vaccination West Bromwich',
            'summary' => 'Private varicella jab for children and adults.',
            'description' => 'Private chickenpox (varicella) vaccination across Hollytree Pharmacy. Two-dose course for children and adults, given by our trained pharmacist.',
            'h1' => 'Chickenpox vaccination, protection that lasts',
            'highlight' => 'protection that lasts',
            'intro' => 'Private varicella vaccination for children and adults — a simple two-dose course given by our trained pharmacist, with no GP referral needed.',
            'badges' => [
                'GPhC-registered pharmacist care',
                'Private consultation room',
                'Same-day appointments available',
            ],
            'how' => [
                'heading' => 'How it works',
                'lead' => 'Two quick appointments, four to eight weeks apart.',
                'steps' => [
                    [
                        'Suitability check',
                        'A short consultation to confirm the vaccine is right for you or your child.',
                    ],
                    [
                        'First dose',
                        'The first dose is given in our private consultation room — quick, clean and over in moments.',
                    ],
                    [
                        'Second dose',
                        'The second dose follows 4–8 weeks later to complete the course — we will book it before you leave.',
                    ],
                ],
            ],
            'about' => [
                'heading' => 'Who should consider it?',
                'paras' => [
                    'Chickenpox is usually mild in young children but can be far more unpleasant in older children and adults. The vaccine is ideal for children, adults who never caught it, and people in close contact with vulnerable family members.',
                    'It is a live vaccine, so it is not suitable during pregnancy or for people with weakened immune systems — our pharmacist checks suitability before every course.',
                ],
            ],
            'cta' => [
                'heading' => 'Register for chickenpox vaccination',
                'body' => 'Leave your details and we will be in touch to arrange a time that suits you.',
                'label' => 'Register interest',
            ],
        ],
    ],

    // Forms for pages that predate the catalogue, plus the flu booking form which
    // sits alongside that service's eligibility checker.
    'forms' => [
        'repeat-prescriptions' => [
            'heading' => 'Request your prescription',
            'button' => 'Send my request',
            'lead' => 'Tell us what you need and we’ll forward it to your GP. Please allow 2–3 working days — the timing depends on your surgery.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'dob',
                    'type' => 'date',
                    'label' => 'Date of birth',
                    'required' => true,
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'postcode',
                    'type' => 'text',
                    'label' => 'Postcode',
                    'required' => true,
                    'placeholder' => 'e.g. B70 7RW',
                    'autocomplete' => 'postal-code',
                    'half' => true,
                ],
                [
                    'name' => 'address',
                    'type' => 'text',
                    'label' => 'Address',
                    'required' => true,
                    'placeholder' => 'Your delivery address',
                    'autocomplete' => 'street-address',
                ],
                [
                    'name' => 'gp',
                    'type' => 'text',
                    'label' => 'GP surgery (optional)',
                    'placeholder' => 'e.g. The West Bromwich Medical Centre',
                ],
                [
                    'name' => 'medications',
                    'type' => 'textarea',
                    'label' => 'Medications needed',
                    'required' => true,
                    'placeholder' => 'List each medication on a separate line, with the dose if you know it',
                ],
                [
                    'name' => 'delivery',
                    'type' => 'select',
                    'label' => 'Collect or deliver?',
                    'options' => [
                        'Deliver to my address',
                        'Deliver to a different address',
                        'I’ll collect in branch',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything else we should know? (optional)',
                    'placeholder' => 'e.g. I am running low and need it urgently',
                ],
            ],
        ],
        'pharmacy-first' => [
            'heading' => 'Send it to our pharmacist',
            'button' => 'Request a consultation',
            'lead' => 'Prefer not to walk in? Leave your details and our pharmacist will call you back, usually within one working day, to arrange your consultation.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'dob',
                    'type' => 'date',
                    'label' => 'Date of birth',
                    'required' => true,
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'you@example.com',
                    'autocomplete' => 'email',
                    'half' => true,
                ],
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'What do you need seen?',
                    'required' => true,
                    'options' => [
                        'Sore throat',
                        'Earache',
                        'Sinusitis',
                        'Urinary tract infection (women 16–64)',
                        'Infected insect bite',
                        'Impetigo',
                        'Shingles',
                        'A minor ailment — not sure which',
                    ],
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Describe the symptoms',
                    'required' => true,
                    'placeholder' => 'What you have noticed, where, and how long it has been going on',
                ],
            ],
        ],
        'minor-ailments' => [
            'heading' => 'Am I eligible?',
            'button' => 'Request a call back',
            'lead' => 'Leave your details and our pharmacist will call to check the scheme is right for you. No appointment needed, and there is nothing to pay.',
            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'dob',
                    'type' => 'date',
                    'label' => 'Date of birth',
                    'required' => true,
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone number',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'nhs_number',
                    'type' => 'text',
                    'label' => 'NHS number (optional)',
                    'placeholder' => '10 digits — or leave blank',
                    'half' => true,
                ],
                [
                    'name' => 'gp',
                    'type' => 'text',
                    'label' => 'Your GP practice',
                    'required' => true,
                    'placeholder' => 'e.g. Linkway Medical Practice',
                ],
                [
                    'name' => 'duration',
                    'type' => 'select',
                    'label' => 'How long have the symptoms been there?',
                    'required' => true,
                    'options' => [
                        'Under 3 days',
                        '3–7 days',
                        '1–2 weeks',
                        'Over 2 weeks',
                    ],
                ],
                [
                    'name' => 'symptoms',
                    'type' => 'textarea',
                    'label' => 'Describe the symptoms',
                    'required' => true,
                    'placeholder' => 'What you have noticed, where, and how long it has been going on',
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Medicines, allergies and anything you have tried (optional)',
                    'placeholder' => 'Anything you take regularly, plus anything you have already tried',
                ],
            ],
        ],
        'flu-covid-booking' => [
            'heading' => 'Book your appointment',
            'button' => 'Request this appointment',
            'lead' => 'Appointments run Monday to Friday, 10am–5pm. Tell us which jab and when suits, and we’ll confirm your slot by phone.',
            'fields' => [
                [
                    'name' => 'detail',
                    'type' => 'select',
                    'label' => 'Vaccination',
                    'required' => true,
                    'options' => [
                        'NHS flu jab (free — eligible patients)',
                        'Private flu jab (£30)',
                        'NHS Covid-19 vaccination (free — eligible patients)',
                        'Flu + Covid-19 together',
                    ],
                ],
                [
                    'name' => 'travel_date',
                    'type' => 'date',
                    'label' => 'Preferred day (Mon–Fri)',
                    'required' => true,
                    'half' => true,
                ],
                [
                    'name' => 'best_time',
                    'type' => 'select',
                    'label' => 'Preferred time',
                    'options' => [
                        'Morning (10am–12pm)',
                        'Early afternoon (12pm–3pm)',
                        'Late afternoon (3pm–5pm)',
                    ],
                    'half' => true,
                ],
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Full name',
                    'required' => true,
                    'placeholder' => 'Your full name',
                    'autocomplete' => 'name',
                    'half' => true,
                ],
                [
                    'name' => 'dob',
                    'type' => 'date',
                    'label' => 'Date of birth',
                    'required' => true,
                    'half' => true,
                ],
                [
                    'name' => 'phone',
                    'type' => 'tel',
                    'label' => 'Phone',
                    'required' => true,
                    'placeholder' => '07700 000000',
                    'autocomplete' => 'tel',
                    'half' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Email (optional)',
                    'placeholder' => 'your@email.com',
                    'autocomplete' => 'email',
                    'half' => true,
                ],
                [
                    'name' => 'notes',
                    'type' => 'textarea',
                    'label' => 'Anything we should know? (optional)',
                    'placeholder' => 'e.g. allergies, needle anxiety, which arm you prefer',
                ],
            ],
        ],
    ],

    // Services whose pages already exist under their own routes.
    'external' => [
        [
            'slug' => 'pharmacy-first',
            'menu' => ['sub' => 'Free NHS treatment, no GP needed', 'thumb' => 'mother-toddler.jpg'],
            'nav_title' => 'Pharmacy First & Minor Illness',
            'name' => 'Pharmacy First & minor illness',
            'nav' => 'Pharmacy First & minor illness',
            'group' => 'nhs',
            'route' => 'pharmacy-first',
            'summary' => 'Free NHS treatment for seven conditions, plus free medicines for everyday ailments.',
        ],
        [
            'slug' => 'repeat-prescriptions',
            'menu' => ['sub' => 'Order online, free delivery', 'thumb' => 'delivery-door.jpg'],
            'nav_title' => 'Repeat Prescriptions',
            'name' => 'Repeat prescriptions',
            'nav' => 'Repeat prescriptions',
            'group' => 'nhs',
            'route' => 'repeat-prescriptions',
            'summary' => 'Order online, delivered free to your door.',
        ],
        [
            'slug' => 'nominate',
            'menu' => ['sub' => 'Make us your NHS pharmacy', 'thumb' => 'nhs-app.jpg'],
            'nav_title' => 'Nominate Us',
            'name' => 'Nominate us',
            'nav' => 'Nominate us',
            'group' => 'nhs',
            'route' => 'repeat-prescriptions',
            'fragment' => 'nominate',
            'summary' => 'Make us your NHS pharmacy in under two minutes.',
        ],
        [
            'slug' => 'weight-loss',
            'menu' => ['sub' => 'Wegovy & Mounjaro', 'thumb' => 'weightloss-outdoor.jpg'],
            'nav_title' => 'Weight Loss Clinic',
            'name' => 'Weight loss clinic',
            'nav' => 'Weight loss clinic',
            'group' => 'private',
            'route' => 'weight-loss',
            'summary' => 'Mounjaro, Wegovy, oral tablets and Alli.',
        ],
    ],
];
