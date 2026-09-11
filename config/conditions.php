<?php

// Conditions treated under Pharmacy First and the local minor ailments scheme.
// Checker rules (ages, red flags, duration scoring) and pathway questions mirror the
// group's published symptom checker; page copy mirrors the group's condition pages.
return [
    'list' => [
        [
            'name' => 'Earache (Ear Infection)',
            'slug' => 'earache',
            'az' => [
                'Earache',
            ],
            'tag' => 'Pharmacy First',
            'min' => 1,
            'max' => 17,
            'sex' => null,
            'age_label' => 'Ages 1–17',
            'elig' => 'Available free on the NHS for children aged 1 to 17.',
            'kw' => 'ear pain infection otitis media child fever tugging ear discharge',
            'intro' => 'Middle-ear infections (acute otitis media) are common in babies and children, causing ear pain, fever, irritability and sometimes discharge from the ear.',
            'image' => null,
            'icon' => 'pf/pf-earache.webp',
            'flags' => [
                'Swelling or redness behind the ear',
                'Severe drowsiness or stiff neck',
                'Something stuck in the ear',
            ],
            'sym' => [
                'Ear pain',
                'Tugging or rubbing at the ear (in a young child)',
                'Discharge from the ear',
                'Reduced hearing on that side',
                'A high temperature',
            ],
            'dur' => [
                'q' => 'How long has it been going on?',
                'opts' => [
                    'Less than 3 days',
                    '3 days or more',
                ],
                'score' => [
                    0,
                    1,
                ],
                'note' => null,
            ],
            'screening' => [
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 1 year',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '1 to 17 years',
                            'result' => 'pass',
                        ],
                        [
                            'text' => '18 or over',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Does the patient have ear pain or earache?',
                    'options' => [
                        [
                            'text' => 'Yes',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'No',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Is there discharge or fluid leaking from the ear?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Is the patient very unwell, unusually drowsy, or do they have a stiff neck?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is an ear infection?',
            'what' => [
                'Earache in babies and children is most often acute otitis media — an infection of the middle ear, the space behind the eardrum. It usually follows a cold, when fluid builds up behind the drum and becomes infected, causing pain, fever and a miserable, unsettled child.',
                'Many middle-ear infections clear by themselves within three days, but some need treatment. Under Pharmacy First our pharmacist can examine the ear with an otoscope and supply NHS treatment — including antibiotics where clinically appropriate — for children aged 1 to 17. No GP appointment, no waiting on the phone at 8am.',
            ],
            'symptoms_heading' => 'Your child may have an ear infection if they have:',
            'symptoms' => [
                'Ear pain — younger children may pull or rub at the ear',
                'Fever, irritability, crying more than usual and poor sleep',
                'Reduced hearing or a feeling of fullness in the ear',
                'Loss of appetite or being off their food',
                'Sometimes fluid or discharge leaking from the ear',
            ],
            'faqs' => [
                [
                    'What causes ear infections in children?',
                    'Middle-ear infections usually follow a cold, when the tube that drains the middle ear gets blocked and trapped fluid becomes infected. Children get them far more than adults because their tubes are smaller and more horizontal. Most are caused by viruses or bacteria and often affect one ear.',
                ],
                [
                    'How can I ease my child\'s earache at home?',
                    'Children\'s paracetamol or ibuprofen (not both at once unless advised) will bring down pain and fever. A warm flannel held against the ear can soothe it, and keep them drinking fluids. Don\'t put cotton buds, oil or drops in the ear unless a clinician has told you to.',
                ],
                [
                    'Does my child need antibiotics for an ear infection?',
                    'Often not — most infections settle within three days on their own, and antibiotics make little difference to those. They\'re considered when symptoms are severe, affect both ears in a young child, there\'s discharge, or things aren\'t improving after a few days. Our pharmacist examines the ear and supplies NHS antibiotics only where the assessment supports it.',
                ],
                [
                    'When should I get urgent help for earache?',
                    'The same day if your child is under 1 with a suspected ear infection (we\'ll direct you appropriately), has a stiff neck, severe headache, swelling behind the ear, is drowsy or hard to wake, or seems really unwell. Redness and swelling behind the ear can signal mastoiditis, which needs hospital treatment — call 999 or go to A&E.',
                ],
                [
                    'Can adults use this service for earache?',
                    'The Pharmacy First ear infection pathway covers ages 1 to 17. Adults with earache are still very welcome — our pharmacist can examine the ear, advise on treatment, and check for other causes such as outer-ear infections or wax, and wax-related blockages can be cleared by — see our adult ear infection page for more.',
                ],
            ],
            'nhs_note' => 'Earache is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Earache in Children: Getting It Treated Without a GP AppointmentWhat Pharmacy First covers, and when earache needs more.',
        ],
        [
            'name' => 'Impetigo',
            'slug' => 'impetigo',
            'az' => [
                'Impetigo',
            ],
            'tag' => 'Pharmacy First',
            'min' => 1,
            'max' => 120,
            'sex' => null,
            'age_label' => 'Ages 1+',
            'elig' => 'Available free on the NHS for anyone aged 1 or over.',
            'kw' => 'skin infection crusty golden sores blisters contagious face hands',
            'intro' => 'Impetigo is a contagious skin infection causing sores and blisters that burst and leave golden-brown crusts, most often around the nose, mouth and hands.',
            'image' => null,
            'icon' => 'pf/pf-impetigo.webp',
            'flags' => [
                'Widespread blistering or skin peeling',
                'Fever or feeling very unwell',
                'Sores that keep coming back after treatment',
            ],
            'sym' => [
                'Red sores or blisters, often around the nose or mouth',
                'Golden or honey-coloured crusts',
                'Sores that are spreading',
                'Itching around the sores',
            ],
            'dur' => null,
            'screening' => [
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 1 year',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '1 year or over',
                            'result' => 'pass',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have sores, blisters or crusting on the skin?',
                    'options' => [
                        [
                            'text' => 'Yes',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'No',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Is the affected area widespread (covering a large part of the body)?',
                    'options' => [
                        [
                            'text' => 'No — small or localised area',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes — widespread',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have a high temperature or feel very unwell?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is impetigo?',
            'what' => [
                'Impetigo is a common and very contagious bacterial skin infection, most often seen in children. It starts as red sores or blisters — typically around the nose and mouth — which quickly burst and leave the tell-tale golden-brown, honey-coloured crusts.',
                'It isn\'t usually serious, but it spreads easily to other parts of the body and to other people, so prompt treatment matters. Under Pharmacy First our pharmacist can diagnose impetigo and supply NHS treatment — antibiotic cream or tablets where appropriate — for anyone aged 1 and over.',
            ],
            'symptoms_heading' => 'You may have impetigo if you have:',
            'symptoms' => [
                'Red sores or blisters, often around the nose, mouth or on the hands',
                'Sores that burst quickly and weep fluid',
                'Golden-brown or honey-coloured crusts as they dry',
                'Itching or mild soreness around the patches',
                'New patches appearing nearby or elsewhere on the body',
            ],
            'faqs' => [
                [
                    'What causes impetigo?',
                    'Bacteria — usually Staphylococcus aureus or Streptococcus — getting into skin through a cut, graze, insect bite or skin already damaged by eczema. It spreads through direct contact with the sores or via towels, flannels, bedding and toys, which is why it romps through households and classrooms.',
                ],
                [
                    'How is impetigo treated?',
                    'Small, localised patches are usually treated with an antibiotic or antiseptic cream; more widespread impetigo may need antibiotic tablets or liquid. Treatment shortens the illness and, importantly, makes you non-contagious much faster. Our pharmacist can assess and supply NHS treatment the same day.',
                ],
                [
                    'How long should my child stay off school with impetigo?',
                    'Children should stay off school or nursery until the patches have crusted over and dried out, or until 48 hours after starting antibiotic treatment. The same applies to adults and work, particularly in food handling or healthcare roles.',
                ],
                [
                    'How do I stop impetigo spreading at home?',
                    'Don\'t touch or scratch the sores, wash hands frequently, and give the affected person their own towel and flannel. Wash bedding, towels and clothes at a high temperature, keep fingernails short, and don\'t share bath water. Cover sores loosely where practical.',
                ],
                [
                    'When should I get further help?',
                    'If the impetigo is widespread, keeps coming back, isn\'t improving after a week of treatment, or the person becomes feverish and unwell, it needs a further review. Also seek advice if the skin around the sores becomes hot, swollen and painful, which can signal a deeper infection.',
                ],
            ],
            'nhs_note' => null,
            'guide' => 'Impetigo: School and Nursery Rules, and Stopping the SpreadWhen children can go back, and how to stop it going round the house.',
        ],
        [
            'name' => 'Infected Insect Bites',
            'slug' => 'infected-insect-bites',
            'az' => [
                'Insect bites',
            ],
            'tag' => 'Pharmacy First',
            'min' => 1,
            'max' => 120,
            'sex' => null,
            'age_label' => 'Ages 1+',
            'elig' => 'Available free on the NHS for anyone aged 1 or over.',
            'kw' => 'bite sting infected red hot swollen spreading pus itchy',
            'intro' => 'Most bites settle on their own, but a bite that becomes increasingly red, hot, swollen or painful after 48 hours may be infected and need treatment.',
            'image' => null,
            'icon' => 'pf/pf-infected-insect-bites.webp',
            'flags' => [
                'Redness spreading quickly or red streaks from the bite',
                'Fever, chills or feeling generally very unwell',
                'A bite near the eyes or mouth with swelling',
            ],
            'sym' => [
                'A bite or sting that is getting more red or swollen',
                'Warm to the touch',
                'Pus or weeping from the site',
                'Spreading redness around the bite',
                'Feeling generally unwell',
            ],
            'dur' => [
                'q' => 'When were you bitten?',
                'opts' => [
                    'Today or yesterday',
                    '2–7 days ago',
                    'More than a week ago',
                ],
                'score' => [
                    0,
                    1,
                    1,
                ],
                'note' => null,
            ],
            'screening' => [
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 1 year',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '1 year or over',
                            'result' => 'pass',
                        ],
                    ],
                ],
                [
                    'q' => 'Does the bite show signs of infection (redness, swelling, warmth, pus)?',
                    'options' => [
                        [
                            'text' => 'Yes',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'No — no signs of infection',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Is the redness spreading rapidly or do you have a high temperature?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have any known allergy to insect stings?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes — I carry an EpiPen',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is an infected insect bite?',
            'what' => [
                'Most insect bites and stings cause a small, itchy lump that settles within a few days. A bite becomes a problem when bacteria get in — usually from scratching — and the skin around it becomes infected. That\'s when redness spreads, the area feels hot and swollen, and it becomes more painful rather than less.',
                'An infected bite left untreated can develop into cellulitis, a deeper skin infection that needs antibiotics. Under Pharmacy First our pharmacist can examine the bite and supply NHS treatment, including antibiotics where clinically appropriate, for anyone aged 1 and over.',
            ],
            'symptoms_heading' => 'A bite may be infected if you have:',
            'symptoms' => [
                'Redness spreading outwards from the bite after the first 48 hours',
                'Skin that feels hot, tight, swollen and increasingly painful',
                'Pus, fluid or a yellow crust around the bite',
                'Swollen glands near the bite, or a red line tracking away from it',
                'Fever, chills or feeling generally unwell',
            ],
            'faqs' => [
                [
                    'How do I know if a bite is infected rather than just reacting?',
                    'A normal bite reaction is worst in the first day or two, then improves — itchy, a bit red, a bit swollen. Infection tends to show 2–3 days later and gets worse instead: spreading redness, heat, increasing pain, pus or fever. If it\'s growing rather than fading, get it looked at.',
                ],
                [
                    'How should I treat an insect bite at home?',
                    'Wash it with soap and water, apply a cold compress for the swelling, and take an antihistamine for the itch. Paracetamol or ibuprofen helps with pain. The single most important thing: try not to scratch — broken skin is how bites get infected in the first place.',
                ],
                [
                    'What will the pharmacist do for an infected bite?',
                    'We examine the bite, check how far any redness has spread and whether you\'re systemically unwell, and rule out red flags. Where the assessment shows a bacterial skin infection, we can supply NHS antibiotics on the spot — and we\'ll tell you exactly what to watch for while it heals.',
                ],
                [
                    'When is an insect bite an emergency?',
                    'Call 999 for any signs of a severe allergic reaction — swelling of the lips, tongue or throat, wheezing, difficulty breathing, dizziness or collapse. Get same-day help if redness is spreading rapidly, you have a fever with a spreading rash, or a red streak is tracking up a limb.',
                ],
                [
                    'How can I avoid getting bitten?',
                    'Use an insect repellent containing DEET, cover up with long sleeves near water and at dusk, and keep food and sweet drinks covered outdoors. If you\'re travelling somewhere with mosquito-borne disease, come and see us about repellents and antimalarials before you go.',
                ],
            ],
            'nhs_note' => 'Bites and Stings and Allergies is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Infected Insect Bite? 5 Signs It Needs TreatmentSpreading redness, weeping and fever — when a bite stops being a bite.',
        ],
        [
            'name' => 'Shingles',
            'slug' => 'shingles',
            'az' => [
                'Shingles',
            ],
            'tag' => 'Pharmacy First',
            'min' => 18,
            'max' => 120,
            'sex' => null,
            'age_label' => 'Ages 18+',
            'elig' => 'Available free on the NHS for adults aged 18 or over.',
            'kw' => 'rash blisters one side burning tingling nerve pain band stripe',
            'intro' => 'Shingles is a painful, blistering rash that appears on one side of the body, often preceded by tingling or burning. Early treatment works best — ideally within 72 hours of the rash appearing.',
            'image' => null,
            'icon' => 'pf/pf-shingles.webp',
            'flags' => [
                'A rash near your eye or on your nose',
                'A weakened immune system',
                'Rash on both sides of the body',
            ],
            'sym' => [
                'A painful rash on one side of the body only',
                'Blisters in a band or stripe',
                'Burning, tingling or stabbing pain in that area',
                'Pain that started before the rash appeared',
            ],
            'dur' => [
                'q' => 'When did the rash appear?',
                'opts' => [
                    'Within the last 72 hours',
                    '3–7 days ago',
                    'More than a week ago',
                ],
                'score' => [
                    0,
                    1,
                    2,
                ],
                'note' => 'Antiviral treatment works best when started within 72 hours of the rash appearing.',
            ],
            'screening' => [
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 18',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '18 or over',
                            'result' => 'pass',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have a painful rash on one side of the body or face?',
                    'options' => [
                        [
                            'text' => 'Yes',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'No',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'How long have you had the rash?',
                    'options' => [
                        [
                            'text' => 'Less than 72 hours',
                            'result' => 'pass',
                        ],
                        [
                            'text' => '72 hours or more',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Is the rash near your eye or on your face?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes — refer to pharmacist',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Are you pregnant or severely immunocompromised?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is shingles?',
            'what' => [
                'Shingles is a painful, blistering rash caused by the chickenpox virus reactivating in your body, usually years or decades after the original infection. It typically appears as a band or patch on one side of the body or face, often preceded by days of tingling, burning or stabbing pain in that area before anything is visible.',
                'Antiviral treatment works best when started within 72 hours of the rash appearing, so speed matters. Under Pharmacy First our pharmacist can assess suspected shingles and supply NHS antiviral treatment where appropriate for adults aged 18 and over — walk in as soon as you notice the rash.',
            ],
            'symptoms_heading' => 'You may have shingles if you notice:',
            'symptoms' => [
                'Tingling, burning or sharp pain in one area, often before any rash',
                'A red, blotchy rash appearing on one side of the body or face',
                'Fluid-filled blisters that burst and crust over',
                'Skin so sensitive that clothes brushing it hurts',
                'Headache, fever and feeling generally unwell',
            ],
            'faqs' => [
                [
                    'What causes shingles — and is it contagious?',
                    'After chickenpox, the varicella-zoster virus lies dormant in your nerves; shingles is that virus waking up, often when your immune system is run down by age, stress or illness. You can\'t catch shingles from someone, but a person who has never had chickenpox can catch chickenpox from fluid in shingles blisters — so keep the rash covered and avoid pregnant women, newborns and anyone immunosuppressed until it crusts over.',
                ],
                [
                    'Why do I need treatment within 72 hours?',
                    'Antiviral tablets work by stopping the virus multiplying, so they\'re most effective started within three days of the rash appearing. Early treatment can shorten the illness, reduce its severity and lower the risk of long-lasting nerve pain afterwards. If you suspect shingles, don\'t wait to see how it goes — come in the same day.',
                ],
                [
                    'How do I look after shingles at home?',
                    'Take paracetamol for the pain, keep the rash clean and dry, and wear loose clothing. A cool compress soothes the blisters — but don\'t burst them, and don\'t use antibiotic creams or plasters that stick. Rest as much as you can; shingles is more draining than people expect.',
                ],
                [
                    'What is post-herpetic neuralgia?',
                    'It\'s nerve pain that continues in the area after the rash has healed — burning, stabbing or extreme sensitivity that can last months. It\'s more common over 50 and after severe shingles, and it\'s the main complication early antiviral treatment aims to prevent. If pain persists after healing, see your GP; there are specific treatments.',
                ],
                [
                    'When is shingles urgent?',
                    'Same-day help if the rash involves your eye, nose tip or ear, if your face droops or hearing changes, or if you\'re pregnant or have a weakened immune system. Shingles around the eye can threaten your sight and needs urgent assessment. Call us, your GP or 111 straight away.',
                ],
            ],
            'nhs_note' => null,
            'guide' => 'Shingles: Why the First 72 Hours Decide EverythingAntivirals work best started within three days of the rash.',
        ],
        [
            'name' => 'Sinusitis',
            'slug' => 'sinusitis',
            'az' => [
                'Sinusitis',
            ],
            'tag' => 'Pharmacy First',
            'min' => 12,
            'max' => 120,
            'sex' => null,
            'age_label' => 'Ages 12+',
            'elig' => 'Available free on the NHS for anyone aged 12 or over.',
            'kw' => 'blocked nose face pain pressure headache mucus congestion',
            'intro' => 'Sinusitis is swelling of the sinuses, usually after a cold or flu, causing pain and tenderness around your cheeks, eyes and forehead, a blocked nose and reduced sense of smell.',
            'image' => null,
            'icon' => 'pf/pf-sinusitis.webp',
            'flags' => [
                'Severe swelling around the eye',
                'Double vision or reduced vision',
                'Severe headache with vomiting or neck stiffness',
            ],
            'sym' => [
                'Blocked or stuffy nose',
                'Pain or pressure around the cheeks, eyes or forehead',
                'Thick or discoloured mucus',
                'Reduced sense of smell',
                'Toothache in the upper teeth',
            ],
            'dur' => [
                'q' => 'How long have you had it?',
                'opts' => [
                    'Less than 10 days',
                    '10 days or more',
                    'More than 4 weeks',
                ],
                'score' => [
                    0,
                    1,
                    2,
                ],
                'note' => 'Sinusitis is usually treated once symptoms have lasted 10 days or more without improving.',
            ],
            'screening' => [
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 12',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '12 or over',
                            'result' => 'pass',
                        ],
                    ],
                ],
                [
                    'q' => 'Have you had facial pain, blocked or runny nose, and reduced sense of smell?',
                    'options' => [
                        [
                            'text' => 'Yes',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'No',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'How long have you had these symptoms?',
                    'options' => [
                        [
                            'text' => 'Less than 10 days',
                            'result' => 'pass',
                        ],
                        [
                            'text' => '10 days or more — not improving',
                            'result' => 'fail',
                        ],
                        [
                            'text' => 'Over 3 months (chronic)',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have a very high temperature, severe headache or neck stiffness?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is sinusitis?',
            'what' => [
                'Sinusitis is swelling of the lining of the sinuses — the air-filled spaces behind your cheekbones and forehead. It usually follows a cold or flu, when the sinuses can\'t drain properly and pressure builds up. That\'s what causes the characteristic facial pain, blocked nose and thick discharge.',
                'Most sinusitis is viral and settles within two to three weeks without antibiotics. Where symptoms have lasted longer or point to a bacterial infection, our pharmacist can assess you under Pharmacy First and supply NHS treatment — including antibiotics when clinically appropriate — for anyone aged 12 and over.',
            ],
            'symptoms_heading' => 'You may have sinusitis if you have:',
            'symptoms' => [
                'Pain, pressure or tenderness around your cheeks, eyes or forehead',
                'A blocked nose or thick green or yellow discharge',
                'Reduced sense of smell',
                'A sinus headache that worsens when you bend forward',
                'Toothache in the upper jaw, fever or bad breath',
            ],
            'faqs' => [
                [
                    'What causes sinusitis?',
                    'Almost always a viral infection — a cold or flu that spreads into the sinus lining and stops it draining. Less often bacteria take hold in the trapped mucus, and allergies, dental infections or nasal polyps can also block the sinuses and set it off.',
                ],
                [
                    'How can I treat sinusitis at home?',
                    'Paracetamol or ibuprofen for the pain, plenty of fluids, and rest. Breathing in steam from a hot shower, holding a warm flannel over your face and sleeping propped up all help the sinuses drain. A saline nasal rinse or spray from the pharmacy can clear congestion, and decongestant sprays help short-term (no more than a week).',
                ],
                [
                    'Do I need antibiotics for sinusitis?',
                    'Usually not — most cases are viral and antibiotics won\'t touch them. If your symptoms have lasted around 10 days or more without improving, or are severe with fever and one-sided facial pain, a bacterial cause is more likely. That\'s when our pharmacist can assess you and supply NHS antibiotics where appropriate.',
                ],
                [
                    'How long does sinusitis last?',
                    'Acute sinusitis usually clears within two to three weeks. If it drags on beyond that, keeps coming back, or lasts more than three months (chronic sinusitis), it needs a proper review — start with our pharmacist, who can treat or refer you on.',
                ],
                [
                    'When should I get urgent help?',
                    'Seek help the same day for a severe headache, swelling or redness around an eye, changes to your vision, a very high fever, confusion or a stiff neck. These are rare but can mean the infection is spreading beyond the sinuses. Call your GP or NHS 111, or 999 in an emergency.',
                ],
            ],
            'nhs_note' => null,
            'guide' => 'Sinusitis or Just a Bad Cold? How to Tell — and When to Get TreatedFacial pressure, the ten-day rule, and when antibiotics genuinely help.',
        ],
        [
            'name' => 'Sore Throat',
            'slug' => 'sore-throat',
            'az' => [
                'Sore throat',
            ],
            'tag' => 'Pharmacy First',
            'min' => 5,
            'max' => 120,
            'sex' => null,
            'age_label' => 'Ages 5+',
            'elig' => 'Available free on the NHS for anyone aged 5 or over.',
            'kw' => 'throat pain swallowing tonsils tonsillitis scratchy',
            'intro' => 'A sore throat makes swallowing painful and often comes with redness, swollen glands and a hoarse voice. Most clear up within a week, but some need treatment.',
            'image' => null,
            'icon' => 'pf/pf-sore-throat.webp',
            'flags' => [
                'Difficulty breathing or swallowing saliva',
                'Unable to open your mouth fully',
                'A weakened immune system',
            ],
            'sym' => [
                'Painful throat, worse when swallowing',
                'Swollen glands in the neck',
                'White patches on the tonsils',
                'A high temperature',
                'No cough',
            ],
            'dur' => [
                'q' => 'How long have you had it?',
                'opts' => [
                    '1–2 days',
                    '3 days or more',
                    'More than a week',
                ],
                'score' => [
                    0,
                    1,
                    1,
                ],
                'note' => null,
            ],
            'screening' => [
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 5',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '5 or over',
                            'result' => 'pass',
                        ],
                    ],
                ],
                [
                    'q' => 'How long have you had a sore throat?',
                    'options' => [
                        [
                            'text' => 'Less than 7 days',
                            'result' => 'pass',
                        ],
                        [
                            'text' => '7 days or more',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have severe difficulty swallowing or breathing?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have a very high temperature and feel very unwell?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Have you had recurring throat infections (more than 3 in a year)?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes — see GP',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is a sore throat?',
            'what' => [
                'A sore throat is pain, scratchiness or irritation of the throat that is usually worse when you swallow. The vast majority are caused by viruses — the same ones behind colds and flu — and clear up on their own within about a week. A smaller number are bacterial, most often streptococcus, and these are the ones that can benefit from antibiotics.',
                'Telling the two apart is exactly what our pharmacists are trained to do. Under Pharmacy First we assess your throat using the NHS FeverPAIN score — looking at fever, pus on the tonsils, how quickly it came on and how inflamed things are — and can supply NHS treatment, including antibiotics where clinically appropriate, without you needing a GP appointment.',
            ],
            'symptoms_heading' => 'You may have a sore throat if you have:',
            'symptoms' => [
                'A painful, dry or scratchy throat that hurts more on swallowing',
                'Red, inflamed tonsils, sometimes with white patches or pus',
                'Swollen, tender glands in your neck',
                'A hoarse voice, mild cough or bad breath',
                'Fever, headache and feeling generally run down',
            ],
            'faqs' => [
                [
                    'What causes a sore throat?',
                    'Most sore throats are viral, caused by cold and flu viruses, laryngitis or glandular fever. Bacterial infections such as strep throat cause a minority but tend to be more severe, with fever and pus on the tonsils. Smoking, allergies, dry air and acid reflux can also leave the throat raw and sore without any infection at all.',
                ],
                [
                    'How can I treat a sore throat at home?',
                    'Paracetamol or ibuprofen ease the pain, and adults can gargle warm salty water. Drink plenty, rest, and suck lozenges or ice lollies to keep the throat moist. Avoid smoking and smoky places. Most viral sore throats settle within a week with nothing more than this.',
                ],
                [
                    'Do I need antibiotics for a sore throat?',
                    'Usually not — antibiotics do nothing against the viruses that cause most sore throats. Our pharmacist scores your symptoms using the NHS FeverPAIN tool, and only where the score points to a likely bacterial infection can antibiotics be supplied on the NHS. That assessment takes minutes and needs no appointment.',
                ],
                [
                    'When should I worry about a sore throat?',
                    'Get help the same day if you have difficulty breathing or swallowing your saliva, can\'t open your mouth fully, are drooling, have a muffled voice, or a high fever that won\'t settle. These can signal a deeper infection such as quinsy that needs urgent treatment. Call us, your GP or NHS 111 — and 999 if breathing is affected.',
                ],
                [
                    'Is a sore throat contagious?',
                    'The infections behind sore throats spread easily through coughs, sneezes and shared cups or cutlery. Wash your hands often, bin tissues straight away and avoid sharing drinks while you\'re unwell. You\'re most infectious in the first few days of symptoms.',
                ],
            ],
            'nhs_note' => 'Sore Throat is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Sore Throat: When You Need Antibiotics — and How to Get Assessed Same DayHow the FeverPAIN score decides who genuinely needs antibiotics.',
        ],
        [
            'name' => 'Urinary Tract Infection (UTI)',
            'slug' => 'uti',
            'az' => [
                'Cystitis',
                'Urinary tract infection (UTI)',
            ],
            'tag' => 'Pharmacy First',
            'min' => 16,
            'max' => 64,
            'sex' => 'F',
            'age_label' => 'Women 16–64',
            'elig' => 'Available free on the NHS for women aged 16 to 64.',
            'kw' => 'urine burning stinging wee cystitis bladder frequent urgency water infection',
            'intro' => 'Uncomplicated UTIs cause burning or stinging when you wee, needing to go more often, and lower tummy discomfort. Women aged 16–64 can now be treated at the pharmacy.',
            'image' => null,
            'icon' => 'pf/pf-uti.webp',
            'flags' => [
                'Fever, shivering or back/side (kidney) pain',
                'Blood in your urine',
                'Pregnancy, or a catheter in place',
            ],
            'sym' => [
                'Burning or stinging when passing urine',
                'Needing to go more often or more urgently',
                'Cloudy or strong-smelling urine',
                'Pain low down in the tummy',
            ],
            'dur' => null,
            'screening' => [
                [
                    'q' => 'What is the patient\'s sex?',
                    'options' => [
                        [
                            'text' => 'Female',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Male',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'How old is the patient?',
                    'options' => [
                        [
                            'text' => 'Under 16',
                            'result' => 'fail',
                        ],
                        [
                            'text' => '16–64',
                            'result' => 'pass',
                        ],
                        [
                            'text' => '65 or over',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have any of the following: burning when urinating, needing to urinate more often, cloudy or strong-smelling urine?',
                    'options' => [
                        [
                            'text' => 'Yes',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'No',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Do you have back or loin pain, a high temperature, or feel very unwell?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes — may be kidney infection',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Are you pregnant?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
                [
                    'q' => 'Have you had a catheter fitted or recent urinary tract surgery?',
                    'options' => [
                        [
                            'text' => 'No',
                            'result' => 'pass',
                        ],
                        [
                            'text' => 'Yes',
                            'result' => 'fail',
                        ],
                    ],
                ],
            ],
            'what_heading' => 'What is a urinary tract infection?',
            'what' => [
                'A urinary tract infection happens when bacteria — usually from the bowel — get into the bladder and multiply. Uncomplicated UTIs are extremely common in women and cause that unmistakable burning when you wee, the constant urge to go, and cloudy or strong-smelling urine.',
                'Under Pharmacy First, our pharmacist can assess women aged 16 to 64 with an uncomplicated UTI and supply NHS antibiotics where clinically appropriate — same day, no GP appointment, no urine sample sent away. If your symptoms fall outside that group, we\'ll still assess you and point you to the right care fast.',
            ],
            'symptoms_heading' => 'You may have a UTI if you have:',
            'symptoms' => [
                'Burning, stinging or pain when you wee',
                'Needing to wee more often or more urgently, often with little to pass',
                'Cloudy, dark or strong-smelling urine',
                'Lower tummy ache or pressure',
                'Feeling tired, achy or generally off-colour',
            ],
            'faqs' => [
                [
                    'What causes UTIs?',
                    'Most are caused by E. coli bacteria from the bowel entering the urethra and travelling to the bladder. Women get them far more often than men simply because the urethra is shorter. Sex, dehydration, holding urine in, the menopause and some contraceptives can all raise the risk.',
                ],
                [
                    'How can I ease UTI symptoms at home?',
                    'Drink plenty of water, take paracetamol for the pain, and rest. A hot water bottle on your lower tummy helps the ache. Avoid alcohol and caffeine while symptoms last. Mild cystitis sometimes clears in a day or two with fluids alone — but if it\'s not settling or is getting worse, come and be assessed.',
                ],
                [
                    'Do I need antibiotics for a UTI?',
                    'Not always — very mild symptoms may settle with fluids and painkillers. But a true bladder infection usually responds quickly to a short antibiotic course, typically three days. Our pharmacist runs through your symptoms and history, and where the assessment supports it, supplies NHS antibiotics on the spot for women 16–64.',
                ],
                [
                    'Why can\'t men and over-65s use the pharmacy UTI service?',
                    'UTIs in men, children, over-65s, and anyone pregnant or with a catheter are classed as complicated — they carry a higher risk of underlying causes and need urine testing and GP-led care. We\'ll still see you, check your symptoms and make sure you get to the right place quickly.',
                ],
                [
                    'When is a UTI urgent?',
                    'Get same-day help for pain in your back or side just below the ribs, a high fever, shaking or chills, nausea and vomiting, or blood in your urine. These suggest the infection may have reached the kidneys, which needs prompt treatment. Call your GP or 111 — and 999 if someone becomes confused or drowsy.',
                ],
            ],
            'nhs_note' => 'Cystitis is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'UTI Treatment Without a GP AppointmentWho Pharmacy First covers, and what happens in the consultation.',
        ],
        [
            'name' => 'Acne',
            'slug' => 'acne',
            'az' => [
                'Acne',
            ],
            'tag' => 'Minor Ailments',
            'min' => 12,
            'max' => 120,
            'sex' => null,
            'age_label' => 'Ages 12+',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'spots pimples blackheads whiteheads oily skin teenage',
            'intro' => 'Acne is one of the everyday skin conditions our pharmacists treat — from the right wash and topical treatments to knowing when a prescriber should step in.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Deep, painful lumps or cysts under the skin',
                'Scarring or dark marks left behind as spots heal',
                'Acne that is affecting your mood or confidence',
                'No improvement after 8–12 weeks of proper treatment',
            ],
            'sym' => [
                'Blackheads and whiteheads',
                'Red, inflamed spots',
                'Oily or greasy skin',
                'Spots on the face, chest, back or shoulders',
                'Tenderness or soreness where spots are forming',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is acne?',
            'what' => [
                'Acne happens when hair follicles become blocked with oil and dead skin, and the bacteria that live on everyone’s skin multiply inside them. Hormones drive the oil production, which is why it peaks in the teenage years — but adult acne, especially along the jaw and chin, is common and often hormonal too.',
                'Our pharmacists can recommend benzoyl peroxide, salicylic acid or adapalene-based treatments, explain the eight-to-twelve-week rule that most people give up on too early, and refer you to our prescriber for stronger topical or oral options if you need them.',
            ],
            'symptoms_heading' => 'You may have acne if you have:',
            'symptoms' => [
                'Blackheads and whiteheads',
                'Red, inflamed spots or pustules',
                'Oily skin that shines within hours of washing',
                'Spots on the face, chest, back or shoulders',
                'Dark marks or shallow scars where spots have healed',
            ],
            'faqs' => [
                [
                    'What actually works for acne?',
                    'Benzoyl peroxide and topical retinoids such as adapalene have the strongest evidence. Both take eight to twelve weeks to show their full effect — most people stop at week three, which is exactly when they would have started working.',
                ],
                [
                    'Does diet or dirt cause acne?',
                    'Dirt does not, and scrubbing makes inflammation worse. Diet plays a smaller role than people think, though very high-sugar diets and some whey supplements can aggravate it in some people.',
                ],
                [
                    'When should I see a prescriber?',
                    'If you are getting painful cysts, any scarring, or three months of proper topical treatment has not helped. Scarring is permanent, so do not wait it out — our pharmacist prescriber can assess you in branch.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Allergies & Hives',
            'slug' => 'allergies',
            'az' => [
                'Allergies',
                'Hives (urticaria)',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'allergy hives urticaria itchy rash antihistamine reaction',
            'intro' => 'Mild allergic reactions — itchy hives, sneezing, streaming eyes — are quick to treat at the pharmacy. Our pharmacist can supply the right antihistamine and tell you when a reaction needs more than that.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Swelling of the lips, tongue, face or throat',
                'Difficulty breathing, wheezing or a tight chest',
                'Feeling faint, dizzy or confused',
                'A rash with a high temperature or feeling very unwell',
            ],
            'sym' => [
                'Itchy raised bumps or welts (hives)',
                'Sneezing, runny nose or itchy eyes',
                'Itchy skin after contact with something',
                'Mild swelling around a bite, sting or contact area',
                'Symptoms that come and go over a few hours',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What causes an allergic reaction?',
            'what' => [
                'An allergy is your immune system overreacting to something harmless — pollen, pet hair, a food, a plant, an insect bite. The histamine it releases causes the itching, swelling and streaming that most people recognise. Hives (urticaria) are the classic sign: raised, itchy welts that move around and fade within hours.',
                'Most reactions are mild and settle with an antihistamine. What matters is recognising the few that are not: any swelling of the mouth or throat, or trouble breathing, is anaphylaxis and needs 999 immediately.',
            ],
            'symptoms_heading' => 'You may be having a mild allergic reaction if you have:',
            'symptoms' => [
                'Itchy, raised red or skin-coloured welts that shift position',
                'Sneezing, an itchy or runny nose, itchy watery eyes',
                'Itching or a rash where something touched your skin',
                'Mild, localised swelling around a bite or sting',
                'Symptoms that improve with an antihistamine',
            ],
            'faqs' => [
                [
                    'Which antihistamine should I take?',
                    'Non-drowsy options such as cetirizine or loratadine suit most adults and children; chlorphenamine works quickly but causes drowsiness. Our pharmacist will match one to your age, other medicines and how you need to function that day.',
                ],
                [
                    'How long do hives last?',
                    'Individual welts fade within 24 hours, though new ones can keep appearing for a few days. Hives that keep coming back for more than six weeks are chronic and worth a GP review.',
                ],
                [
                    'When is it an emergency?',
                    'Any swelling of the lips, tongue or throat, wheezing, difficulty breathing, or feeling faint after exposure is anaphylaxis. Use an adrenaline pen if you have one and call 999 — do not come to the pharmacy first.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Athlete\'s Foot',
            'slug' => 'athletes-foot',
            'az' => [
                'Athlete’s foot',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'itchy feet flaky skin between toes fungal rash',
            'intro' => 'Athlete\'s Foot is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-athletes-foot.webp',
            'icon' => 'pf/pf-athletes-foot.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Itchy, flaky skin between the toes',
                'Cracked or peeling skin',
                'Redness or soreness',
                'Skin that stings or burns',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is athlete\'s foot?',
            'what' => [
                'Athlete\'s foot is a fungal infection of the skin on the feet, usually starting in the warm, damp spaces between the toes. The fungus thrives in sweaty socks, tight trainers and communal wet floors — you don\'t have to be an athlete, just have feet that spend time warm and moist.',
                'It causes itchy, flaky, sometimes cracked and sore skin, and left untreated it can spread to the soles, toenails and even the hands. Effective antifungal creams, sprays and powders are available right over the counter — our pharmacist will match the right one to how your skin looks.',
            ],
            'symptoms_heading' => 'You may have athlete\'s foot if you have:',
            'symptoms' => [
                'Itchy, burning or stinging skin between the toes',
                'White, soggy-looking or flaky, peeling skin',
                'Cracked or split skin that can be sore or bleed',
                'Redness, scaling or small blisters on the soles or sides',
                'An unpleasant smell from the affected foot',
            ],
            'faqs' => [
                [
                    'What causes athlete\'s foot?',
                    'Dermatophyte fungi that feed on the keratin in skin. They love warmth and moisture — sweaty feet in enclosed shoes, wet changing-room floors, shared towels and pool sides. Once established, the fungus sheds spores in skin flakes, which is how it spreads to other people and other parts of your body.',
                ],
                [
                    'How is athlete\'s foot treated?',
                    'Antifungal creams, sprays or powders from the pharmacy clear most cases — terbinafine cream typically works fastest, often within a week, while others need up to four weeks. The golden rule: keep using the treatment for the full course (usually 1–2 weeks after the skin looks normal), or it comes straight back.',
                ],
                [
                    'How do I stop it coming back?',
                    'Dry thoroughly between your toes after every wash, change socks daily, alternate pairs of shoes so each dries fully, and wear flip-flops in communal showers and pools. An antifungal powder dusted into shoes kills lingering spores. Never share towels while infected.',
                ],
                [
                    'Can athlete\'s foot spread to my toenails?',
                    'Yes — untreated, the same fungus commonly moves into the nails, turning them thick, yellow and crumbly. Nail infections take months of treatment to clear, so treating the skin promptly is well worth it. If a nail is already affected, come and ask us — options range from paint-on lacquers to GP-prescribed tablets.',
                ],
                [
                    'When should I see someone about it?',
                    'See our pharmacist first — but seek further help if there\'s no improvement after two weeks of proper treatment, if the foot becomes hot, swollen and painful (possible bacterial infection), or if you have diabetes. Foot infections in diabetes need prompt professional care, so don\'t self-treat cracked or broken skin without advice.',
                ],
            ],
            'nhs_note' => 'Athletes Foot is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Athlete’s Foot That Keeps Coming Back: How to Break the CycleWhy it returns, how long to keep treating, and the shoe habits that stop it.',
        ],
        [
            'name' => 'Cold Sores',
            'slug' => 'cold-sores',
            'az' => [
                'Cold sores',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'cold sore herpes lip blister tingling aciclovir',
            'intro' => 'Cold sores are common, contagious and frustrating — but caught at the tingle stage they can be shortened. Our pharmacists can supply treatment and explain how to stop them spreading.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'A sore near or in the eye, or any change to your vision',
                'Very large, widespread or rapidly spreading sores',
                'A weakened immune system, or you are having chemotherapy',
                'A cold sore on a baby under 3 months, or a baby who is unwell',
            ],
            'sym' => [
                'Tingling, itching or burning on the lip before anything appears',
                'Small fluid-filled blisters on or around the lips',
                'Blisters that burst, weep and then crust over',
                'Soreness that makes eating or talking uncomfortable',
                'Sores that come back in the same place',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What are cold sores?',
            'what' => [
                'Cold sores are caused by the herpes simplex virus, which most of us pick up in childhood and carry for life. It lies dormant in a nerve and reactivates when you are run down, sunburnt, stressed or fighting a cold — hence the name.',
                'Antiviral creams such as aciclovir work best applied at the very first tingle, before a blister forms. Our pharmacist can supply them, suggest patches to protect and hide the sore, and advise on keeping it away from babies, whose immune systems cannot cope with the virus.',
            ],
            'symptoms_heading' => 'You may have a cold sore if you have:',
            'symptoms' => [
                'A tingling, itching or burning patch on the lip a day or so before it appears',
                'A cluster of small, painful, fluid-filled blisters',
                'Blisters that burst, ooze and crust over within a few days',
                'Soreness or swelling of the lip',
                'Recurrences in the same spot, often when run down',
            ],
            'faqs' => [
                [
                    'How long does a cold sore last?',
                    'Usually seven to ten days from tingle to healed. Antiviral cream started at the tingle stage can shave a day or two off and reduce the severity.',
                ],
                [
                    'Are cold sores contagious?',
                    'Yes — from the first tingle until fully healed. Avoid kissing, sharing cups, towels or lip products, and keep well away from newborn babies, whose immune systems cannot fight the virus.',
                ],
                [
                    'Why do I keep getting them?',
                    'The virus never leaves; triggers such as sunlight, colds, stress, tiredness and menstruation reactivate it. An SPF lip balm and keeping antiviral cream to hand prevent a surprising number of outbreaks.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Colic',
            'slug' => 'colic',
            'az' => [
                'Colic',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 1,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'baby crying colic wind infant evening',
            'intro' => 'Colic is exhausting for parents and, thankfully, harmless for babies. Our pharmacists can rule out the things that are not colic, and suggest what genuinely helps in the meantime.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Vomiting that is green or contains blood',
                'A high temperature, or a baby who seems floppy or unusually unresponsive',
                'Feeding poorly, or fewer wet nappies than usual',
                'Blood in the stool, or a swollen, hard tummy',
            ],
            'sym' => [
                'Intense crying for hours at a time, often in the evening',
                'Drawing the knees up, clenching fists and arching the back',
                'A red, flushed face while crying',
                'Difficult to soothe, but otherwise feeding and gaining weight well',
                'Crying that follows a pattern most days',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is colic?',
            'what' => [
                'Colic is the name for prolonged, hard-to-soothe crying in an otherwise healthy, well-fed baby — classically more than three hours a day, three days a week, from the first few weeks of life. Nobody fully knows why it happens; it usually settles by itself between three and four months.',
                'The important job is making sure it really is colic. A baby who is feeding well, gaining weight and has a normal temperature and nappies almost certainly has it. Our pharmacist can check the warning signs with you and talk through simethicone drops, feeding technique and the soothing methods that stand up to evidence.',
            ],
            'symptoms_heading' => 'Your baby may have colic if they have:',
            'symptoms' => [
                'Long bouts of intense crying, often at the same time each day',
                'Knees drawn up, fists clenched and a red face while crying',
                'Difficulty settling despite feeding, changing and cuddling',
                'Otherwise feeding normally and gaining weight',
                'Crying that started in the first few weeks and is improving by 3–4 months',
            ],
            'faqs' => [
                [
                    'Do colic drops work?',
                    'Simethicone drops are safe and some parents find them helpful, though the evidence is modest. Gentle tummy massage, holding your baby upright after feeds, white noise and motion often help as much. There is no harm in trying drops for a week to see.',
                ],
                [
                    'Could it be reflux or a milk allergy?',
                    'Sometimes. Frequent vomiting, poor weight gain, blood or mucus in the stool, eczema or a very unsettled baby after every feed all point away from simple colic and warrant a GP or health-visitor review.',
                ],
                [
                    'How do I cope?',
                    'Colic is not caused by anything you are doing. It is safe to put your baby down somewhere safe and take five minutes if you feel overwhelmed. Never shake a baby. Ask family for breaks, and talk to your health visitor or GP if you are struggling — that matters as much as the crying.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Conjunctivitis',
            'slug' => 'conjunctivitis',
            'az' => [
                'Conjunctivitis',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'red eye sticky discharge itchy gritty pink eye',
            'intro' => 'Conjunctivitis is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-conjunctivitis.webp',
            'icon' => 'pf/pf-conjunctivitis.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Red or pink eye',
                'Sticky or watery discharge',
                'Gritty or burning feeling',
                'Eyelids stuck together in the morning',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is conjunctivitis?',
            'what' => [
                'Conjunctivitis is inflammation of the thin, clear layer covering the white of the eye and the inside of the eyelids. It makes eyes red or pink, gritty, itchy and watery — and depending on the cause, sticky with discharge that can glue the lashes together overnight.',
                'It looks alarming but is rarely serious. Most cases are viral or allergic and settle without antibiotics; bacterial cases produce more of the thick yellow-green discharge. Our pharmacist can tell you which type you\'re dealing with, supply the right drops or advice, and spot the rare cases that need a doctor.',
            ],
            'symptoms_heading' => 'You may have conjunctivitis if you have:',
            'symptoms' => [
                'Red or pink colouring in one or both eyes',
                'A gritty, burning feeling — like sand in the eye',
                'Watering, or a sticky yellow-green discharge',
                'Lashes crusted together after sleep',
                'Itchiness, especially when allergy is the cause',
            ],
            'faqs' => [
                [
                    'What causes conjunctivitis?',
                    'Three main culprits: viruses (often alongside a cold), bacteria, and allergies such as pollen or pet dander. Viral and bacterial forms are contagious; allergic conjunctivitis is not. Irritants like chlorine, smoke or a stray eyelash can cause identical redness too.',
                ],
                [
                    'How do I treat conjunctivitis at home?',
                    'Clean away crusting with cooled boiled water and clean cotton wool — one wipe per piece, from the inner corner outwards. A cold compress soothes; lubricating drops from the pharmacy ease the grittiness. Don\'t wear contact lenses until it has fully cleared, and bin the pair you were wearing when it started.',
                ],
                [
                    'Does conjunctivitis need antibiotic drops?',
                    'Usually not — viral and allergic cases don\'t respond to them, and even most bacterial cases clear on their own within a week or two. Antibiotic drops such as chloramphenicol are worth it for clearly bacterial infections with heavy discharge; our pharmacist can assess and supply them over the counter where appropriate.',
                ],
                [
                    'Do children need to stay off school with conjunctivitis?',
                    'No — public health guidance doesn\'t require time off school or nursery for conjunctivitis. It does spread easily, so reinforce hand washing, discourage eye rubbing, and give the child their own towel and pillowcase.',
                ],
                [
                    'When is a red eye urgent?',
                    'Seek help the same day for eye pain (rather than grittiness), sensitivity to light, changes to vision, a red eye in a baby under 28 days old, an eye that won\'t open, or redness after an injury or chemical splash. Contact lens wearers with a painful red eye should be seen urgently — that can be an ulcer.',
                ],
            ],
            'nhs_note' => 'Acute Bacterial Conjunctivitis is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Red, Sticky Eye: Is It Conjunctivitis — and Do You Need Drops?Bacterial, viral or allergic — and the symptoms that mean same-day help.',
        ],
        [
            'name' => 'Constipation',
            'slug' => 'constipation',
            'az' => [
                'Constipation',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'hard stools difficulty pooing bloated laxative',
            'intro' => 'Constipation is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-constipation.webp',
            'icon' => 'pf/pf-constipation.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Going less often than usual',
                'Hard or lumpy stools',
                'Straining or pain when going',
                'Feeling bloated or full',
            ],
            'dur' => [
                'q' => 'How long has it been going on?',
                'opts' => [
                    'A few days',
                    '1–2 weeks',
                    'Longer than 2 weeks',
                ],
                'score' => [
                    0,
                    0,
                    1,
                ],
                'note' => null,
            ],
            'screening' => null,
            'what_heading' => 'What is constipation?',
            'what' => [
                'Constipation means opening your bowels less often than is normal for you, or passing stools that are hard, dry and difficult or painful to pass. There\'s no single \'right\' frequency — anywhere from three times a day to three times a week can be normal — what matters is a change from your usual pattern.',
                'It\'s one of the most common problems we see, and in most people it responds well to changes in fibre, fluid and activity, with laxatives as a short-term bridge. Our pharmacists can recommend the right type of laxative for your situation — they are not all the same — and spot the cases that need a GP.',
            ],
            'symptoms_heading' => 'You may have constipation if you have:',
            'symptoms' => [
                'Fewer bowel movements than is usual for you',
                'Hard, dry, lumpy stools that are difficult or painful to pass',
                'Straining, or feeling you haven\'t fully emptied',
                'Bloating, discomfort or cramping in your tummy',
                'Feeling sluggish, and in children: soiling, tummy ache and avoiding the toilet',
            ],
            'faqs' => [
                [
                    'What causes constipation?',
                    'Most often: not enough fibre or fluid, not enough movement, ignoring the urge to go, and changes to routine like travel or shift work. Pregnancy, stress and anxiety play a big role. Many medicines cause it too — codeine and other opioids, iron tablets, some antidepressants and antacids — so bring your medicines list and we\'ll check.',
                ],
                [
                    'How can I get things moving naturally?',
                    'Build up fibre gradually — wholegrains, fruit, vegetables, beans — and drink plenty of water alongside it (fibre without fluid makes things worse). Move daily, even a brisk walk. Never ignore the urge to go, give yourself unhurried time on the toilet, and try raising your feet on a small stool so your knees sit above your hips — it genuinely helps.',
                ],
                [
                    'Which laxative should I use?',
                    'It depends on the problem. Bulk-forming laxatives (like ispaghula) are the gentlest first step if fibre is low; osmotic laxatives (like macrogol) soften hard stools by drawing in water; stimulant laxatives (like senna) get a lazy bowel moving but are for short-term use. Picking the wrong one is why laxatives \'don\'t work\' — ask us to match one to you.',
                ],
                [
                    'What about constipation in children?',
                    'Very common and very treatable, but worth doing properly — a constipated child often starts withholding because passing stools hurts, which makes everything worse. Plenty of fluids, fruit and encouragement help, but persistent constipation in a child should be reviewed; treatment usually needs a proper course of macrogol, not just a few days.',
                ],
                [
                    'When is constipation a red flag?',
                    'See a GP promptly if constipation is a new, persistent change lasting more than a few weeks, or comes with blood in your stool, unexplained weight loss, constant tummy pain, a lump in your tummy, or alternating constipation and diarrhoea — especially over 50. Sudden complete blockage with vomiting needs same-day help.',
                ],
            ],
            'nhs_note' => 'Constipation is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Constipation: Which Laxative Does What, and Which to Try FirstThe four types of laxative explained, and when constipation needs a GP.',
        ],
        [
            'name' => 'Coughs & Colds',
            'slug' => 'coughs-colds',
            'az' => [
                'Coughs',
                'Colds',
                'Nasal congestion',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'cough cold blocked runny nose sore chest congestion phlegm',
            'intro' => 'Coughs & Colds is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-coughs-colds.webp',
            'icon' => 'pf/pf-coughs-colds.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Cough',
                'Sore throat',
                'Runny or blocked nose',
                'Sneezing',
                'Feeling tired or achy',
            ],
            'dur' => [
                'q' => 'How long have you had it?',
                'opts' => [
                    'Less than a week',
                    '1–3 weeks',
                    'More than 3 weeks',
                ],
                'score' => [
                    0,
                    0,
                    2,
                ],
                'note' => 'A cough lasting more than three weeks needs a GP appointment rather than pharmacy treatment.',
            ],
            'screening' => null,
            'what_heading' => 'What are coughs & colds?',
            'what' => [
                'Colds are viral infections of the nose and throat, and the cough that often tags along is your airway clearing mucus and irritation. Adults average two to four colds a year, children many more — and because well over 200 viruses cause them, immunity to one doesn\'t protect you from the next.',
                'Antibiotics don\'t work on colds and most coughs, but the right pharmacy treatment makes the week or so of symptoms far more bearable. Our pharmacists can recommend the best combination for your symptoms, check anything that\'s lingering, and spot the chest infections and red flags that need more than self-care.',
            ],
            'symptoms_heading' => 'You may have a cough or cold if you have:',
            'symptoms' => [
                'A blocked or runny nose and sneezing',
                'A sore throat, often the first sign',
                'Coughing — dry and tickly, or chesty with mucus',
                'Headache, mild fever and aching muscles',
                'Tiredness and reduced sense of taste and smell',
            ],
            'faqs' => [
                [
                    'How long should a cough or cold last?',
                    'A cold typically runs its course in about a week to ten days, though the cough can linger up to three weeks after everything else has cleared — that\'s normal and doesn\'t mean infection is still active. A cough lasting more than three weeks, or one that\'s getting worse rather than better, should be checked.',
                ],
                [
                    'What actually helps a cold?',
                    'Rest, fluids and paracetamol or ibuprofen for the aches and fever. Decongestant sprays clear a blocked nose (max one week of use), saline drops work at any age, and honey and lemon in warm water genuinely soothes a cough for anyone over one. We can help you pick products that don\'t double-up on the same ingredients.',
                ],
                [
                    'Do I need antibiotics for a cough or cold?',
                    'No — colds and the vast majority of coughs are viral, and antibiotics do nothing against viruses. They\'re only useful in genuine bacterial chest infections, which your pharmacist or GP can help identify. Taking them \'just in case\' fuels resistance and side effects with no benefit.',
                ],
                [
                    'How do I know if it\'s a cold, flu or Covid?',
                    'Colds build gradually and stay mostly above the neck. Flu hits fast and hard — fever, exhaustion, aching everywhere, needing to lie down. Covid overlaps with both; if it matters for someone vulnerable around you, test. And when autumn comes, we run NHS flu and Covid jabs in store — book online.',
                ],
                [
                    'When should I worry about a cough?',
                    'Get same-day advice for chest pain, coughing up blood, real breathlessness or wheeze, a fever that won\'t settle, or a cough in someone with COPD, asthma flare-up or a weakened immune system. A cough lasting over three weeks needs a GP check even if you feel otherwise fine.',
                ],
            ],
            'nhs_note' => 'Acute Cough is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Cough and Cold Remedies: What Actually WorksWhich treatments have evidence behind them — and why antibiotics do not.',
        ],
        [
            'name' => 'Dandruff & Itchy Scalp',
            'slug' => 'dandruff',
            'az' => [
                'Dandruff',
                'Itchy scalp',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'dandruff flakes scalp itchy seborrhoeic dermatitis shampoo',
            'intro' => 'Flakes on your shoulders are usually a mild scalp condition, not a hygiene problem. Our pharmacists can recommend a medicated shampoo that actually clears it and tell you when it is something else.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Thick, scaly, silvery plaques on the scalp or elsewhere',
                'Patches of hair loss',
                'A scalp that is weeping, crusted, painful or smells',
                'A rash spreading onto the face, ears or chest',
            ],
            'sym' => [
                'White or grey flakes in the hair and on the shoulders',
                'An itchy scalp',
                'A dry or, conversely, greasy scalp',
                'Flaking that gets worse in winter or when stressed',
                'Mild redness of the scalp',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What causes dandruff?',
            'what' => [
                'Dandruff is usually mild seborrhoeic dermatitis — an overgrowth of a yeast that lives harmlessly on everyone’s skin, which irritates the scalp and speeds up skin-cell turnover so flakes appear. It is not caused by dirty hair, and washing more with an ordinary shampoo rarely fixes it.',
                'Medicated shampoos containing ketoconazole, selenium sulphide, zinc pyrithione or coal tar target the cause. Our pharmacist can recommend the right one for you, explain how to use it (leave it on, do not rinse straight off) and spot the scalp conditions that need a different approach, such as psoriasis or ringworm.',
            ],
            'symptoms_heading' => 'You may have dandruff if you have:',
            'symptoms' => [
                'Visible white or grey flakes in the hair and on clothing',
                'An itchy scalp, sometimes with mild redness',
                'Dry-feeling or greasy-feeling scalp skin',
                'Flaking that worsens in cold weather or with stress',
                'No sore patches, hair loss or thick plaques',
            ],
            'faqs' => [
                [
                    'Which shampoo should I use?',
                    'Ketoconazole 2% is the usual first choice; selenium sulphide or coal tar suit others. Use it two or three times a week, leave it on for five minutes before rinsing, and expect two to four weeks for a clear result. Then drop to once a week to keep it away.',
                ],
                [
                    'Is it psoriasis or dandruff?',
                    'Dandruff flakes are fine and loose; scalp psoriasis forms thick, silvery, well-defined plaques and often appears at the elbows or knees too. If that sounds like you, ask us — psoriasis needs different treatment.',
                ],
                [
                    'What about cradle cap in babies?',
                    'Cradle cap is the infant version and is harmless. Soften the scales with baby oil or a cradle-cap shampoo and brush gently — do not pick. See us if it spreads, looks infected or the baby seems bothered by it.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Diarrhoea',
            'slug' => 'diarrhoea',
            'az' => [
                'Diarrhoea',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'loose stools runny poo upset stomach dehydration',
            'intro' => 'Diarrhoea is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-diarrhoea.webp',
            'icon' => 'pf/pf-diarrhoea.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Loose or watery stools',
                'Going more often than usual',
                'Tummy cramps',
                'Feeling sick',
            ],
            'dur' => [
                'q' => 'How long has it lasted?',
                'opts' => [
                    'Less than 3 days',
                    '3–7 days',
                    'More than a week',
                ],
                'score' => [
                    0,
                    1,
                    2,
                ],
                'note' => 'Diarrhoea lasting more than a week should be assessed by a GP.',
            ],
            'screening' => null,
            'what_heading' => 'What is diarrhoea?',
            'what' => [
                'Diarrhoea is passing loose, watery stools more often than normal, usually caused by a gut infection — viral \'stomach bugs\' like norovirus, or bacteria from food. In adults it typically settles within five to seven days; the main job while it runs its course is staying hydrated.',
                'Our pharmacists can supply oral rehydration sachets, advise when loperamide is appropriate (and when it isn\'t), and crucially spot the situations that need medical attention — young children, older adults, blood in the stool, or diarrhoea after travel or antibiotics.',
            ],
            'symptoms_heading' => 'You may have diarrhoea if you have:',
            'symptoms' => [
                'Loose or watery stools, more frequent than usual',
                'Stomach cramps and an urgent need to go',
                'Nausea, and sometimes vomiting alongside',
                'Fever, headache and aching in infectious causes',
                'Signs of dehydration: thirst, dark urine, dizziness, dry mouth',
            ],
            'faqs' => [
                [
                    'What causes diarrhoea?',
                    'Most short-lived diarrhoea is infectious — norovirus and rotavirus, or food-poisoning bacteria like campylobacter and salmonella. Other causes include antibiotics disturbing the gut, anxiety, too much caffeine or sweeteners, and conditions like IBS. Diarrhoea that keeps recurring or persists beyond a couple of weeks needs looking into rather than just managing.',
                ],
                [
                    'How should I treat diarrhoea at home?',
                    'Drink little and often — water, diluted squash, and oral rehydration sachets which replace salts as well as fluid (especially important for children and older adults). Eat when you feel able; plain food is easiest. Loperamide can reduce adult diarrhoea when you need to function, but avoid it if there\'s blood in your stool or a high fever, and don\'t give it to children.',
                ],
                [
                    'How do I stop it spreading to the rest of the house?',
                    'Wash hands thoroughly with soap and water after the toilet and before food — alcohol gel does not kill norovirus. Don\'t share towels, clean bathroom surfaces frequently, and don\'t prepare food for others while symptomatic. Stay off work or school until 48 hours after the last episode of diarrhoea or vomiting.',
                ],
                [
                    'What are the signs of dehydration to watch for?',
                    'In adults: dark, strong-smelling urine, dizziness, dry mouth, tiredness and passing little urine. In babies and children: fewer wet nappies, no tears when crying, a sunken soft spot, drowsiness and irritability. Dehydration in babies, young children and the elderly can develop fast — treat these groups with rehydration sachets early and seek help if in doubt.',
                ],
                [
                    'When does diarrhoea need medical attention?',
                    'Same-day advice for blood or black colour in the stool, severe or constant tummy pain, signs of dehydration that aren\'t improving, diarrhoea after recent travel abroad or a course of antibiotics, or in anyone very young, elderly, pregnant or immunosuppressed. Adult diarrhoea lasting beyond about 7 days, or vomiting beyond 2, should also be checked.',
                ],
            ],
            'nhs_note' => 'Diarrhoea is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Diarrhoea: When Loperamide Helps — and When You Should Not Take ItRehydration first, and the situations where anti-diarrhoeals are unsafe.',
        ],
        [
            'name' => 'Dry Eyes',
            'slug' => 'dry-eyes',
            'az' => [
                'Dry eyes',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => '',
            'intro' => 'Gritty, tired, stinging eyes are one of the conditions our pharmacists treat through the free NHS minor ailments scheme — with the right drops for your pattern, not just the first box on the shelf.',
            'image' => 'cond/cond-dry-eyes.webp',
            'icon' => null,
            'flags' => [
                'Sudden loss of vision, or a marked change in vision',
                'Severe eye pain, or a red eye with light sensitivity',
                'A possible injury, or something stuck in the eye',
            ],
            'sym' => [
                'A gritty, sandy or burning feeling, as though something is in the eye',
                'Tired, heavy eyes that are worse after screens, driving or a long day',
                'Watery eyes that overflow despite feeling dry',
                'Blurred vision that clears when you blink',
                'Red, crusted or sore lid margins, particularly first thing in the morning',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'About dry, gritty eyes',
            'what' => [
                'Dry eye happens when the tear film is not doing its job — either not enough tears, or tears that evaporate too quickly because the oily layer is poor. The result is a gritty, sandy, tired feeling, often worse at the end of the day. Confusingly, watery eyes are a classic dry eye symptom: the surface irritation triggers a reflex flood of watery tears that runs straight off.',
                'Screens are the modern driver — we blink far less while concentrating — along with air conditioning, car heaters, contact lens wear and windy weather. Blepharitis, where the oil glands along the lid margin become blocked, is behind a great many stubborn cases, and it needs lid hygiene rather than more drops. Some medicines dry the eyes too, including certain antihistamines and antidepressants, which is worth checking.',
            ],
            'symptoms_heading' => 'Come and see us if you have:',
            'symptoms' => [
                'A gritty, sandy or burning feeling, as though something is in the eye',
                'Tired, heavy eyes that are worse after screens, driving or a long day',
                'Watery eyes that overflow despite feeling dry',
                'Blurred vision that clears when you blink',
                'Red, crusted or sore lid margins, particularly first thing in the morning',
            ],
            'faqs' => [
                [
                    'Why are my eyes watering if they are dry?',
                    'Because irritation on the eye surface triggers a reflex burst of watery tears. Those tears lack the oily layer that holds them in place, so they run off rather than coating the eye — leaving it dry again. Treating the dryness usually settles the watering.',
                ],
                [
                    'Which eye drops should I use?',
                    'Thin drops suit mild daytime dryness and are easy to use often; thicker gels and ointments last longer and suit night-time or more marked dryness, though they blur vision briefly. If you use drops more than four to six times a day, choose a preservative-free product. Contact lens wearers need drops specifically suitable for lenses — ask us.',
                ],
                [
                    'What is blepharitis and how is it treated?',
                    'It is inflammation of the eyelid margins where the oil glands sit, causing crusting, redness and stubborn dryness. Treatment is lid hygiene rather than drops alone: a warm compress for five to ten minutes, gentle massage along the lid, then cleaning the lid margin. It needs doing daily and for weeks, not days, to work.',
                ],
                [
                    'How do I stop screens making it worse?',
                    'Follow the 20-20-20 habit — every 20 minutes, look 20 feet away for 20 seconds — and blink deliberately and fully. Drop the screen slightly below eye level so your lids cover more of the eye, and move away from direct air conditioning or heater vents.',
                ],
                [
                    'When should I see an optician or doctor instead?',
                    'Get seen the same day for sudden vision change, severe pain, a red eye with light sensitivity, or a suspected injury or foreign body. Persistent dryness that is not improving after a few weeks of proper treatment is worth an optometrist appointment.',
                ],
            ],
            'nhs_note' => null,
            'guide' => 'Dry, Gritty Eyes: Choosing Between Drops, Gels and OintmentsWhy dry eyes water, and when preservative-free actually matters.',
        ],
        [
            'name' => 'Eczema & Dry Skin',
            'slug' => 'eczema-dry-skin',
            'az' => [
                'Eczema',
                'Dry skin',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'dry itchy flaky skin eczema dermatitis emollient',
            'intro' => 'Eczema & Dry Skin is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-eczema.webp',
            'icon' => 'pf/pf-eczema-dry-skin.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Dry, rough or flaky skin',
                'Itching',
                'Red or inflamed patches',
                'Skin that cracks or weeps',
                'Worse in certain weather or after washing',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is eczema?',
            'what' => [
                'Eczema (atopic dermatitis) is a condition that makes skin dry, itchy, red and cracked because the skin barrier doesn\'t hold moisture in or keep irritants out as it should. It\'s most common in children — often in the creases of elbows and knees — but affects plenty of adults too, and tends to flare and settle in cycles.',
                'The foundation of managing eczema never changes: generous, frequent emollients to repair the barrier, and prompt treatment of flares before they take hold. Our pharmacists can help you find an emollient you\'ll actually use, advise on mild steroid creams for flares, and spot infected eczema that needs more.',
            ],
            'symptoms_heading' => 'You may have eczema if you have:',
            'symptoms' => [
                'Dry, rough, flaky or scaly patches of skin',
                'Itching — often intense, and worse at night',
                'Redness and inflammation; on darker skin, patches may look grey, purple or darker brown',
                'Cracked, weeping or bleeding skin in bad flares',
                'Thickened, leathery skin in areas scratched over time',
            ],
            'faqs' => [
                [
                    'What causes eczema and what triggers flares?',
                    'Eczema runs in families alongside asthma and hayfever, and comes from a leaky skin barrier plus an over-reactive immune response. Common flare triggers: soaps and bubble baths, fragranced products, wool against skin, heat and sweating, cold dry weather, dust mites, pet dander, stress — and in some children, certain foods. Spotting your triggers is half the battle.',
                ],
                [
                    'How should I use emollients properly?',
                    'Generously and constantly — not just when skin looks bad. Apply at least twice a day (more in winter), smoothed on in the direction of hair growth rather than rubbed in, and always within a few minutes of washing to lock moisture in. Use the emollient as a soap substitute too; ordinary soap strips the barrier you\'re trying to rebuild.',
                ],
                [
                    'When should I use a steroid cream?',
                    'At the first sign of a flare — used promptly and correctly, a short course of mild steroid (like hydrocortisone 1%) settles inflammation before it escalates, and that\'s safer than weeks of under-treated, itchy, damaged skin. Apply a thin layer once or twice daily to the flared areas only, and keep emollients going alongside (leave a gap between the two). Ask us about use on faces and in children.',
                ],
                [
                    'How do I know if eczema is infected?',
                    'Suspect infection when eczema suddenly worsens, weeps fluid, develops yellow-golden crusts, becomes hot and painful, or you feel feverish and unwell. Widespread painful blisters can signal eczema herpeticum, which is an emergency. Infected eczema needs assessment the same day — come in or call your GP.',
                ],
                [
                    'Can eczema be cured?',
                    'There\'s no cure, but it can be controlled well — and many children grow out of the worst of it. The realistic goal is long stretches of calm skin with quick, confident treatment of flares. If flares are frequent or severe despite good emollient use and steroid courses, ask your GP about stronger treatments and referral; options have improved enormously.',
                ],
            ],
            'nhs_note' => 'Dry Skin (Simple Eczema) is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Eczema: Getting Emollients and Steroid Creams RightHow much emollient to really use, and why fear of steroids makes eczema worse.',
        ],
        [
            'name' => 'Fever (Pyrexia)',
            'slug' => 'fever',
            'az' => [
                'Fever',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => '',
            'intro' => 'A high temperature in an adult or child is one of the conditions our pharmacists can assess and treat through the free NHS minor ailments scheme — including advice on when it needs more than a pharmacy.',
            'image' => 'cond/cond-fever.webp',
            'icon' => null,
            'flags' => [
                'A baby under 3 months with any temperature of 38°C or above',
                'A rash that does not fade when pressed, a stiff neck, or dislike of bright light',
                'A child who is floppy, unusually drowsy, breathing hard, or very hard to wake',
            ],
            'sym' => [
                'A temperature of 38°C or above',
                'Feeling hot to touch, flushed cheeks, sweating or shivering',
                'Aching muscles, headache and general tiredness',
                'In children: being clingy, off food, quieter than usual or poorly settled',
                'Reduced drinking, fewer wet nappies, or passing less urine than normal',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'About fever',
            'what' => [
                'A fever is the body raising its own temperature to fight infection — usually 38°C or above. It is a symptom, not an illness, and in most cases it means a virus is being dealt with exactly as it should be. The number on the thermometer matters far less than how the person looks and behaves: a child with 39°C who is drinking and still playing is generally less worrying than one at 38°C who is listless and refusing fluids.',
                'Treatment is about comfort rather than driving the number down. Paracetamol or ibuprofen help someone feel well enough to drink and rest, which is what actually speeds recovery. Fluids come first, always. Our pharmacists can confirm the right medicine and dose for age and weight, and are equally clear about the situations that need a GP, 111 or 999 instead.',
            ],
            'symptoms_heading' => 'Come and see us if you have:',
            'symptoms' => [
                'A temperature of 38°C or above',
                'Feeling hot to touch, flushed cheeks, sweating or shivering',
                'Aching muscles, headache and general tiredness',
                'In children: being clingy, off food, quieter than usual or poorly settled',
                'Reduced drinking, fewer wet nappies, or passing less urine than normal',
            ],
            'faqs' => [
                [
                    'What temperature counts as a fever?',
                    '38°C or above is generally treated as a fever. In babies under three months, a temperature of 38°C or above always needs same-day medical assessment, regardless of how well they otherwise seem.',
                ],
                [
                    'Should I always bring a fever down?',
                    'No — the aim is comfort, not a particular number. If someone is drinking, resting and reasonably settled, a fever can be left alone. Paracetamol or ibuprofen are worth giving when discomfort is stopping them drinking or sleeping.',
                ],
                [
                    'Can I give paracetamol and ibuprofen together?',
                    'They should not be given at the same moment as a routine, but where one alone is not controlling discomfort they can sometimes be alternated. Do that only on advice — ask us and we will set out the timings and doses clearly for the age and weight involved.',
                ],
                [
                    'How do I keep someone comfortable with a fever?',
                    'Offer small amounts of fluid often, keep the room at a normal comfortable temperature, and use light bedding and clothing. Do not cold-sponge, use a fan on the skin, or wrap someone up to “sweat it out” — none of these help and cold sponging can make shivering worse.',
                ],
                [
                    'When should a fever be seen urgently?',
                    'Call 999 for a non-fading rash, a stiff neck, a fit, difficulty breathing, or a child who is floppy or hard to wake. Seek same-day advice for any fever in a baby under three months, a fever lasting more than five days, dehydration, or if you are simply worried — that instinct is worth trusting.',
                ],
            ],
            'nhs_note' => 'Acute Fever is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Fever in Children: What the Number Actually MeansWhy behaviour matters more than the thermometer, and the signs that need urgent help.',
        ],
        [
            'name' => 'Hayfever',
            'slug' => 'hayfever',
            'az' => [
                'Hay fever',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'sneezing itchy eyes runny nose pollen allergy antihistamine',
            'intro' => 'Hayfever is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => null,
            'icon' => 'pf/pf-hayfever.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Sneezing or a runny nose',
                'Itchy, red or watery eyes',
                'An itchy throat or roof of the mouth',
                'Blocked nose',
                'Symptoms that come and go with the weather or pollen',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is hayfever?',
            'what' => [
                'Hayfever is an allergic reaction to pollen — from grasses, trees or weeds — that inflames the lining of your nose, eyes and throat. In the UK it\'s at its worst from late March to September, peaking when the grass pollen count soars in June and July.',
                'There\'s no cure, but the right combination of treatments controls it well for almost everyone. Our pharmacists can match you to the right antihistamine, steroid nasal spray and eye drops for your symptoms — and advise when a prescription-strength option or a different approach is worth trying.',
            ],
            'symptoms_heading' => 'You may have hayfever if you get:',
            'symptoms' => [
                'Sneezing fits and a runny or blocked nose',
                'Itchy, red, watering eyes',
                'An itchy throat, mouth, nose or ears',
                'Loss of smell, headache or pressure around the face',
                'Tiredness — poor sleep during high pollen counts is common',
            ],
            'faqs' => [
                [
                    'What causes hayfever and when is it worst?',
                    'It\'s your immune system overreacting to pollen proteins. Tree pollen runs roughly March to May, grass pollen May to July (the most common trigger), and weed pollen June to September. Warm, dry, windy days send counts up; rain washes pollen out of the air.',
                ],
                [
                    'Which hayfever treatment should I use?',
                    'For most people a once-daily non-drowsy antihistamine (cetirizine, loratadine or fexofenadine) is the starting point. If a blocked, runny nose dominates, a steroid nasal spray used daily is the most effective single treatment — but it takes days to build up, so start before your season hits. Itchy eyes respond best to antihistamine eye drops. Many people need a combination; ask us to tailor it.',
                ],
                [
                    'How can I reduce my pollen exposure?',
                    'Check the forecast and plan around high counts. Keep windows closed in the early morning and evening when pollen peaks, shower and change clothes after being outside, and dry washing indoors on high-count days. A smear of barrier balm around the nostrils traps pollen, and wraparound sunglasses genuinely help.',
                ],
                [
                    'My antihistamines aren\'t working — what next?',
                    'First check the basics: steroid nasal sprays need daily use and correct technique (aim away from the middle of your nose), and antihistamines work better taken before exposure. If a full combination still isn\'t controlling it, come and see us — switching antihistamine, adding eye drops, or a GP referral for further options may be the answer.',
                ],
                [
                    'Is it hayfever or a cold?',
                    'Hayfever itches — eyes, throat, ears — and colds generally don\'t. Hayfever symptoms flare within minutes of exposure and persist for weeks in season, while a cold builds over a day or two and clears within about ten. No fever with hayfever, and the discharge stays clear and watery.',
                ],
            ],
            'nhs_note' => 'Hay Fever is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Hay Fever Treatment in West BromwichMatching the treatment to the symptom — and why tablets fail on a blocked nose.',
        ],
        [
            'name' => 'Head Lice',
            'slug' => 'head-lice',
            'az' => [
                'Head lice',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'nits itchy scalp lice eggs school children hair',
            'intro' => 'Head Lice is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-head-lice.webp',
            'icon' => 'pf/pf-head-lice.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Itchy scalp',
                'Live lice found when combing',
                'Eggs or nits stuck to the hair',
                'Someone else in the household has them',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What are head lice?',
            'what' => [
                'Head lice are tiny insects that live in hair and feed on blood from the scalp. They\'re a fact of life in primary schools — they spread by direct head-to-head contact, can\'t jump or fly, and having them says nothing about how clean anyone\'s hair is.',
                'The eggs (nits) glue themselves to hair shafts close to the scalp and hatch within about a week, which is why one-off treatments so often \'fail\'. Our pharmacists can confirm live lice, recommend the right lotion or wet-combing routine, and make sure the crucial repeat treatment actually happens.',
            ],
            'symptoms_heading' => 'Your child may have head lice if they have:',
            'symptoms' => [
                'An itchy scalp — though many children don\'t itch at all',
                'A feeling of something moving in the hair',
                'Small white or brown eggs (nits) stuck to hairs near the scalp',
                'Live lice the size of a sesame seed, seen when wet-combing',
                'Red marks or rash on the scalp or back of the neck',
            ],
            'faqs' => [
                [
                    'How do I check for head lice properly?',
                    'Wet the hair, add plenty of conditioner, and comb through section by section with a fine-toothed detection comb, wiping the comb on white paper after each stroke. Finding a live moving louse confirms infestation — old empty egg cases alone don\'t. Check the whole household the same day.',
                ],
                [
                    'What\'s the best treatment for head lice?',
                    'Two solid options: a medicated lotion or spray (usually dimeticone-based, which suffocates lice rather than poisoning them), or systematic wet-combing with conditioner every 3–4 days for two weeks. Whichever you choose, treat again after 7 days to catch lice hatched from surviving eggs — skipping that second round is the number one reason lice come back.',
                ],
                [
                    'Why do head lice keep coming back?',
                    'Usually one of three things: the second treatment was missed, close contacts weren\'t checked and treated at the same time, or the child was reinfected at school. Only treat people with confirmed live lice — but check everyone, and tell school so other parents check too.',
                ],
                [
                    'Do I need to wash all the bedding and boil the hairbrushes?',
                    'Lice can\'t survive long away from a scalp, so deep-cleaning the house is unnecessary. Washing pillowcases and soaking combs and brushes in hot water on treatment day is plenty. Your energy is far better spent on thorough combing and the repeat treatment.',
                ],
                [
                    'Should my child stay off school with head lice?',
                    'No — there\'s no need to keep children off school for head lice. Treat promptly, tie long hair back, and let the school know so classmates get checked. Discouraging head-to-head contact (and shared selfies!) is the most effective prevention there is.',
                ],
            ],
            'nhs_note' => 'Headlice is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Head Lice: Wet Combing, Treatments, and Why They Keep Coming BackHow to check properly, and why the seven-day repeat is non-negotiable.',
        ],
        [
            'name' => 'Headache & Mild Pain',
            'slug' => 'headache-mild-pain',
            'az' => [
                'Headache',
                'Mild pain',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => '',
            'intro' => 'Headaches, dental pain, period pain and muscle aches are among the everyday problems our pharmacists treat through the free NHS minor ailments scheme — no GP appointment needed.',
            'image' => 'cond/cond-headache.webp',
            'icon' => null,
            'flags' => [
                'A sudden, severe headache unlike any you have had before',
                'Headache with a stiff neck, rash, fever, confusion or a dislike of bright light',
                'Headache after a head injury, or one that is worse when you cough, bend or lie down',
            ],
            'sym' => [
                'A dull, tight or pressing ache across the forehead, temples or back of the head',
                'Pain that builds through the day and eases with rest, food or fluids',
                'Neck and shoulder tightness alongside the headache',
                'Throbbing one-sided pain with nausea or light sensitivity, suggesting migraine',
                'Period, dental or muscle pain that is mild to moderate and short-lived',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'About headache and mild pain',
            'what' => [
                'Most headaches are tension-type: a dull, tight band-like ache across the forehead or around the head, often building through the day. They are driven by everyday things — poor sleep, skipped meals, dehydration, screen posture, stress and, ironically, painkillers taken too often. Migraine is different: usually one-sided, throbbing, worse with movement, and often with nausea or light sensitivity.',
                'Mild pain elsewhere — period pain, dental pain while you wait for a dentist, a pulled muscle — responds to the same short-term approach. What matters is choosing the right painkiller for you, at the right dose, and not taking it for so long that it becomes the problem itself. That last point catches a lot of people out, and it is the main reason a two-minute conversation with a pharmacist is worth having.',
            ],
            'symptoms_heading' => 'Come and see us if you have:',
            'symptoms' => [
                'A dull, tight or pressing ache across the forehead, temples or back of the head',
                'Pain that builds through the day and eases with rest, food or fluids',
                'Neck and shoulder tightness alongside the headache',
                'Throbbing one-sided pain with nausea or light sensitivity, suggesting migraine',
                'Period, dental or muscle pain that is mild to moderate and short-lived',
            ],
            'faqs' => [
                [
                    'What causes most headaches?',
                    'Everyday triggers rather than anything sinister: not drinking enough, missing meals, poor or broken sleep, stress, eye strain and neck tension from screens or driving. Alcohol, caffeine withdrawal and hormonal changes are common culprits too. Keeping a short note of when headaches hit often reveals the pattern faster than anything else.',
                ],
                [
                    'Which painkiller should I take?',
                    'Paracetamol is the usual first choice and is gentle on the stomach. Ibuprofen often works better for period pain, dental pain and muscle injuries because it tackles inflammation, but it does not suit everyone — particularly with stomach ulcers, asthma, kidney problems, or alongside some blood pressure and blood-thinning medicines. Ask us to match one to you and your medicines list.',
                ],
                [
                    'Can painkillers themselves cause headaches?',
                    'Yes, and it is more common than people realise. Taking painkillers for headache on more days than not — especially those containing codeine or caffeine — can lead to medication-overuse headache, where the treatment sustains the problem. If you are reaching for tablets most days, come and talk to us rather than increasing the dose.',
                ],
                [
                    'How can I prevent headaches without tablets?',
                    'Drink water steadily through the day, eat regularly, keep a consistent sleep pattern, take screen breaks, and loosen the neck and shoulders. Fresh air and a short walk help more than most people expect. If you grind your teeth or wake with jaw ache, that is worth mentioning too.',
                ],
                [
                    'When is a headache an emergency?',
                    'Call 999 for a sudden severe headache that peaks within seconds or minutes, or a headache with a stiff neck, rash, fever, confusion, weakness, slurred speech or vision loss. Seek same-day advice for a headache following a head injury, or one that is consistently worse when you cough, bend forward or lie flat.',
                ],
            ],
            'nhs_note' => 'Acute Pain/Earache/Headache/Temperature is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Headaches: When Painkillers Are the Problem, Not the CureTension headache, migraine, and the medication-overuse trap.',
        ],
        [
            'name' => 'Indigestion & Heartburn',
            'slug' => 'indigestion-heartburn',
            'az' => [
                'Indigestion',
                'Heartburn',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => '',
            'intro' => 'Heartburn, acid reflux, indigestion and trapped wind can all be assessed and treated by our pharmacists through the free NHS minor ailments scheme — no GP appointment needed.',
            'image' => 'cond/cond-indigestion.webp',
            'icon' => null,
            'flags' => [
                'Difficulty or pain when swallowing, or food sticking',
                'Unintentional weight loss, or vomiting blood or dark, tarry stools',
                'Chest pain with sweating, breathlessness, or pain spreading to the jaw or arm — call 999',
            ],
            'sym' => [
                'A burning feeling behind the breastbone, often after eating or when lying down',
                'An acidic or bitter taste at the back of the throat',
                'Uncomfortable fullness, gnawing or discomfort in the upper tummy',
                'Bloating, pressure and frequent burping',
                'Symptoms that are worse with large, late, fatty or spicy meals, or alcohol',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'About indigestion and heartburn',
            'what' => [
                'Heartburn is acid from the stomach rising into the food pipe, giving that hot, burning feeling behind the breastbone, often worse lying down or after a large meal. Indigestion is a fuller, gnawing discomfort in the upper tummy, sometimes with nausea or an early feeling of fullness. Trapped wind is the pressure and bloating that shifts with movement or burping.',
                'Most cases respond well to timing and portion changes plus a short course of treatment. The important part is knowing which treatment fits: antacids neutralise acid on the spot, alginates form a raft that keeps acid down, and acid-reducing tablets cut production over days. Persistent symptoms, or symptoms that need treatment continuously, should be reviewed properly rather than managed indefinitely from the shelf.',
            ],
            'symptoms_heading' => 'Come and see us if you have:',
            'symptoms' => [
                'A burning feeling behind the breastbone, often after eating or when lying down',
                'An acidic or bitter taste at the back of the throat',
                'Uncomfortable fullness, gnawing or discomfort in the upper tummy',
                'Bloating, pressure and frequent burping',
                'Symptoms that are worse with large, late, fatty or spicy meals, or alcohol',
            ],
            'faqs' => [
                [
                    'What triggers heartburn and indigestion?',
                    'Large or late meals, fatty and spicy food, alcohol, coffee, chocolate, smoking, being overweight and pregnancy are the common ones. Stress and eating in a rush play a bigger role than people credit. Some medicines contribute too — anti-inflammatories such as ibuprofen, and certain heart and bone tablets — so bring your medicines list.',
                ],
                [
                    'Which treatment should I use?',
                    'It depends on the pattern. Antacids work within minutes for occasional symptoms; alginate preparations suit reflux that comes on lying down; acid-reducing tablets are better for symptoms occurring several days a week, taken as a short course. Matching the treatment to the pattern is the difference between relief and disappointment.',
                ],
                [
                    'What can I change without medicine?',
                    'Eat smaller meals and leave three hours before bed, raise the head of the bed slightly, cut back alcohol and late coffee, stop smoking, and lose a little weight if you carry extra around the middle. Loosen tight waistbands — a small thing that genuinely helps reflux.',
                ],
                [
                    'Is heartburn ever something more serious?',
                    'Occasional heartburn is very common and rarely serious. Get it looked at properly if it is new and persistent over 55, if you need treatment continuously, or if there is difficulty swallowing, unexplained weight loss, or any sign of bleeding.',
                ],
                [
                    'How do I know it is not my heart?',
                    'Reflux is usually burning, linked to meals or lying down, and eases with an antacid. Cardiac pain tends to be a tight, heavy or crushing pressure, may spread to the jaw, neck, back or arm, and can come with sweating, nausea or breathlessness. If there is any doubt at all, call 999 — that is always the right call.',
                ],
            ],
            'nhs_note' => 'Heartburn/Indigestion is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Heartburn and Indigestion: Antacids, Alginates or a PPI?What each does, how fast it works, and when to see a GP instead.',
        ],
        [
            'name' => 'Mouth Ulcers',
            'slug' => 'mouth-ulcers',
            'az' => [
                'Mouth ulcers',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'painful ulcer mouth sore tongue gum cheek',
            'intro' => 'Mouth Ulcers is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-mouth-ulcers.webp',
            'icon' => 'pf/pf-mouth-ulcers.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'One or more sore spots inside the mouth',
                'Pain when eating or drinking',
                'A white, yellow or grey centre with a red edge',
            ],
            'dur' => [
                'q' => 'How long has it been there?',
                'opts' => [
                    'Less than 3 weeks',
                    '3 weeks or more',
                ],
                'score' => [
                    0,
                    2,
                ],
                'note' => 'An ulcer lasting three weeks or more should always be checked by a dentist or GP.',
            ],
            'screening' => null,
            'what_heading' => 'What are mouth ulcers?',
            'what' => [
                'Mouth ulcers are small, shallow, painful sores inside the mouth — on the cheeks, lips, tongue or gums. Most are minor aphthous ulcers: round or oval, white-yellow with a red border, and healed within one to two weeks without leaving a mark.',
                'They\'re annoying rather than dangerous, but they can make eating, drinking and even talking uncomfortable. Pharmacy treatments — protective gels, anaesthetic and antimicrobial rinses — take the edge off and speed healing, and our pharmacist can spot the ulcers that need a doctor or dentist\'s opinion.',
            ],
            'symptoms_heading' => 'You may have a mouth ulcer if you have:',
            'symptoms' => [
                'A round or oval sore inside the mouth, white or yellow with a red edge',
                'Pain or stinging, worse with hot, spicy, salty or acidic food',
                'Swelling or tenderness around the ulcer',
                'Sometimes several ulcers at once',
                'Larger ulcers may make speaking and eating awkward',
            ],
            'faqs' => [
                [
                    'What causes mouth ulcers?',
                    'Often a simple knock — biting your cheek, a sharp tooth, rough brushing or ill-fitting dentures. Stress, tiredness, hormonal changes, stopping smoking and certain foods (chocolate, coffee, tomatoes, citrus) trigger them in some people. Recurrent crops can be linked to low iron, B12 or folate, coeliac disease or Crohn\'s — worth investigating if they keep coming.',
                ],
                [
                    'How can I treat a mouth ulcer?',
                    'Protective gels and pastes shield the ulcer while it heals; anaesthetic gels, sprays and lozenges numb the pain; and antimicrobial mouthwash keeps it clean and can shorten healing. A soft toothbrush, avoiding trigger foods and drinking through a straw all help. Most heal in 1–2 weeks whatever you do — treatment is about comfort.',
                ],
                [
                    'Are mouth ulcers contagious?',
                    'No — ordinary aphthous ulcers can\'t be passed on by kissing or sharing cutlery. Cold sores are the contagious lookalike, but they\'re caused by a virus and appear on the lips and outside of the mouth rather than inside.',
                ],
                [
                    'Why do I keep getting mouth ulcers?',
                    'Recurrent ulcers usually run in families and flare with stress, poor sleep or hormonal cycles. But repeated crops deserve a check of iron, B12 and folate levels, and a review of any medicines you take. Keep a note of when they appear — patterns help your pharmacist or GP find the trigger.',
                ],
                [
                    'When should a mouth ulcer be checked by a professional?',
                    'Any ulcer lasting more than three weeks needs a dentist or GP review — persistent ulcers must be checked to rule out anything serious, especially in smokers and drinkers. Also seek advice for an ulcer that keeps growing, bleeds, is unusually painless, or comes with unexplained weight loss or lumps in the neck.',
                ],
            ],
            'nhs_note' => 'Mouth Ulcers is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Mouth Ulcers: What Helps, and the One That Needs CheckingWhy the three-week rule matters more than any treatment.',
        ],
        [
            'name' => 'Nappy Rash',
            'slug' => 'nappy-rash',
            'az' => [
                'Nappy rash',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'baby red sore bottom nappy skin irritation',
            'intro' => 'Nappy Rash is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-nappy-rash.webp',
            'icon' => 'pf/pf-nappy-rash.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Red or sore skin in the nappy area',
                'Spots or blotches',
                'Skin that looks broken or raw',
                'Discomfort during nappy changes',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is nappy rash?',
            'what' => [
                'Nappy rash is red, sore, inflamed skin in the nappy area, caused by skin sitting against wetness, urine and poo — sometimes made worse by friction, or by thrush taking hold on already-irritated skin. Most babies get it at some point, however carefully they\'re looked after.',
                'Mild nappy rash usually clears in a few days with frequent changes, gentle cleaning and a barrier cream. Our pharmacists can tell ordinary nappy rash from thrush (which needs an antifungal cream) and from the rashes that need a doctor — and recommend exactly what to use at each stage.',
            ],
            'symptoms_heading' => 'Your baby may have nappy rash if they have:',
            'symptoms' => [
                'Red or raw patches on the bottom, thighs or genitals',
                'Skin that looks sore, feels warm, or has spots and blisters',
                'A baby who\'s unsettled, especially at nappy changes',
                'Bright red rash with satellite spots may suggest thrush',
                'In darker skin, the rash may look darker rather than red',
            ],
            'faqs' => [
                [
                    'What causes nappy rash?',
                    'Wet or soiled nappies left against the skin, friction from the nappy itself, and the change in skin pH when urine and poo mix. Teething, illness, antibiotics and the move to solid foods can all make a baby\'s skin more reactive for a while. Thrush (a yeast) often moves in on skin that\'s already sore.',
                ],
                [
                    'How do I treat nappy rash at home?',
                    'Change nappies promptly and frequently, clean with water or fragrance-free wipes, pat dry, and give as much nappy-free time as you can — air is the best healer. Apply a thin layer of barrier cream at each change. Most mild rashes improve within about three days of this routine.',
                ],
                [
                    'How do I know if it\'s thrush rather than ordinary nappy rash?',
                    'Thrush tends to be brighter red, lasts beyond a few days despite good care, involves the skin folds, and shows small \'satellite\' spots around the edge of the main rash. It needs an antifungal cream, which we can supply — barrier cream alone won\'t clear it.',
                ],
                [
                    'Which cream should I use — and how much?',
                    'For prevention and mild rash: a simple barrier cream (zinc or petroleum-based) applied thinly — a thick layer stops the nappy absorbing moisture and can make things worse. For confirmed thrush: an antifungal cream, used for the full course. Steroid creams should only be used on a doctor or pharmacist\'s advice in a baby.',
                ],
                [
                    'When should nappy rash be seen by a professional?',
                    'If it hasn\'t improved after a week of good care, keeps coming back, spreads beyond the nappy area, blisters, weeps, bleeds or crusts, or your baby has a fever and seems unwell. Bright yellow crusting can mean a bacterial infection needing treatment — get it checked promptly.',
                ],
            ],
            'nhs_note' => 'Nappy Rash is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Nappy Rash: Barrier Creams, and How to Spot When It Is ThrushThe difference is the skin folds — and it changes the treatment.',
        ],
        [
            'name' => 'Oral Thrush',
            'slug' => 'oral-thrush',
            'az' => [
                'Oral thrush',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'thrush mouth white patches tongue candida baby dentures inhaler',
            'intro' => 'Oral thrush is a yeast infection of the mouth — common in babies, denture wearers and anyone using a steroid inhaler. Our pharmacists can supply treatment and help stop it coming back.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Difficulty or pain swallowing, or symptoms spreading down the throat',
                'A weakened immune system, chemotherapy, or HIV',
                'Thrush that keeps returning, or is not clearing with treatment',
                'A baby who is refusing feeds, has a fever or is losing weight',
            ],
            'sym' => [
                'Creamy white patches on the tongue, cheeks, gums or roof of the mouth',
                'Patches that can be wiped off leaving a red, sore area',
                'A sore mouth or a burning feeling',
                'Loss of taste or an unpleasant taste',
                'Cracked, red skin at the corners of the mouth',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is oral thrush?',
            'what' => [
                'Oral thrush is an overgrowth of Candida, a yeast that lives in most people’s mouths without causing trouble. It takes hold when the balance is upset — after antibiotics, with steroid inhalers, in dentures worn overnight, with diabetes, or in babies whose immune systems are still developing.',
                'It is not serious in itself and clears with an antifungal gel or drops from the pharmacy, but repeated bouts are worth a proper look. Our pharmacist can supply treatment, check your inhaler technique and denture care, and advise when a GP review is needed.',
            ],
            'symptoms_heading' => 'You may have oral thrush if you have:',
            'symptoms' => [
                'White or cream patches inside the mouth that look like milk residue but do not wipe away easily',
                'Redness or soreness underneath the patches',
                'A cottony feeling or burning in the mouth',
                'Reduced taste, or a bad taste',
                'Cracks at the corners of the mouth',
            ],
            'faqs' => [
                [
                    'How is it treated?',
                    'Miconazole oral gel or nystatin drops used for a week or so clear most cases. Keep going for two days after it looks better. Babies can be treated too — we will check the age and dose with you.',
                ],
                [
                    'Why do I keep getting it?',
                    'Common culprits: not rinsing after a steroid inhaler, wearing dentures overnight or not cleaning them, uncontrolled diabetes, dry mouth, recent antibiotics or smoking. Fix the cause and it usually stops recurring.',
                ],
                [
                    'My baby has thrush — should I stop breastfeeding?',
                    'No. Thrush can pass between baby’s mouth and your nipples, so both usually need treating at the same time. Sterilise dummies and bottle teats daily. Ask us about treating you both.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Scabies',
            'slug' => 'scabies',
            'az' => [
                'Scabies',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'scabies itch mites burrows rash night household permethrin',
            'intro' => 'Scabies is intensely itchy, very contagious and completely treatable — as long as everyone in the household is treated on the same day. Our pharmacists supply the treatment and talk you through doing it properly.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Thick, crusted, scaly patches of skin (crusted scabies)',
                'Signs of infection — weeping, pus, spreading redness or fever',
                'A baby under 2 months, or you are pregnant or breastfeeding',
                'A weakened immune system',
            ],
            'sym' => [
                'Intense itching, much worse at night',
                'A rash of tiny red spots or bumps',
                'Thin, wavy, greyish lines (burrows) between the fingers, on the wrists or waist',
                'Itching in others in your household',
                'Itching around the fingers, wrists, armpits, waist or genitals',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is scabies?',
            'what' => [
                'Scabies is caused by tiny mites that burrow into the top layer of skin to lay eggs. The relentless itch is an allergic reaction to the mites — which is why it can take up to eight weeks to start after you catch it, and can carry on for a few weeks after successful treatment.',
                'It spreads by prolonged skin contact, so it moves through families, couples and care settings easily. Treatment is a permethrin cream applied to the whole body, repeated a week later, with everyone in close contact treated at the same time whether or not they itch. Our pharmacist supplies it and explains the routine, which is where most treatment failures happen.',
            ],
            'symptoms_heading' => 'You may have scabies if you have:',
            'symptoms' => [
                'Severe itching that is worst at night or after a hot bath',
                'A spotty rash, often on the hands, wrists, waist, buttocks or genitals',
                'Tiny burrow lines between the fingers or around the wrists',
                'Itching that started a few weeks after contact with someone who has it',
                'Other people in your home starting to itch',
            ],
            'faqs' => [
                [
                    'How do I use the treatment?',
                    'Apply permethrin 5% cream to cool, dry skin over the whole body from the neck down (and the scalp and face in young children and older adults), leave for 8–12 hours, then wash off. Repeat exactly seven days later. Everyone in close contact does the same on the same day.',
                ],
                [
                    'Why am I still itching after treatment?',
                    'The itch is an allergy to the dead mites and can last two to four weeks after successful treatment. It should be improving, not spreading. A soothing cream or antihistamine helps; new burrows or a worsening rash mean it needs another look.',
                ],
                [
                    'Do I need to fumigate the house?',
                    'No. Wash bedding, towels and clothes worn in the last three days on a hot wash (60°C) or seal them in a bag for 72 hours — mites cannot survive long off the skin. Furniture and carpets do not need special treatment.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Sleep Problems',
            'slug' => 'sleep-problems',
            'az' => [
                'Sleep problems',
            ],
            'tag' => 'Advice',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => '',
            'intro' => 'Trouble falling asleep or staying asleep is one of the things our pharmacists are asked about most — and one where the right advice matters far more than the right tablet.',
            'image' => 'cond/cond-sleep-problems.webp',
            'icon' => null,
            'flags' => [
                'Loud snoring with pauses in breathing, or waking gasping — possible sleep apnoea',
                'Persistent low mood, hopelessness, or anxiety that is affecting daily life',
                'Sleepiness so severe that driving or work is becoming unsafe',
            ],
            'sym' => [
                'Lying awake for a long time before falling asleep',
                'Waking repeatedly in the night, or waking very early and not getting back off',
                'Feeling unrefreshed in the morning however long you were in bed',
                'Daytime tiredness, poor concentration, irritability or low mood',
                'Lying awake worrying about not sleeping',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'About trouble sleeping',
            'what' => [
                'Short spells of poor sleep are normal and usually settle on their own. Insomnia is when trouble falling or staying asleep persists for weeks and starts affecting your days — concentration, mood, appetite, patience. The most common pattern we see is a cycle: a stressful period disturbs sleep, then worry about not sleeping keeps the disturbance going long after the original stress has passed.',
                'That is why the evidence is so firmly behind behavioural approaches rather than medication. Pharmacy sleep aids are sedating antihistamines — they can bridge a difficult few nights, but they are not a long-term answer, they leave many people groggy the next morning, and they do not suit everyone, particularly older adults and anyone with glaucoma or prostate problems. Fixing the routine is slower and considerably more effective.',
            ],
            'symptoms_heading' => 'Come and see us if you have:',
            'symptoms' => [
                'Lying awake for a long time before falling asleep',
                'Waking repeatedly in the night, or waking very early and not getting back off',
                'Feeling unrefreshed in the morning however long you were in bed',
                'Daytime tiredness, poor concentration, irritability or low mood',
                'Lying awake worrying about not sleeping',
            ],
            'faqs' => [
                [
                    'What actually helps insomnia long term?',
                    'Keeping the same wake-up time every day, including weekends, is the single most powerful change. Alongside it: get out of bed if you have been awake 20 minutes rather than lying there, use the bed only for sleep, get daylight in the morning, keep the room cool and dark, and stop clock-watching. This approach is called CBT for insomnia and it outperforms tablets over time.',
                ],
                [
                    'Do pharmacy sleeping tablets work?',
                    'They are sedating antihistamines and can help for a few difficult nights, but they are not a long-term solution. Many people feel groggy the following morning, the effect fades with regular use, and they are unsuitable for some people — including many older adults and anyone with glaucoma or prostate problems. Always check with us first.',
                ],
                [
                    'Does melatonin help?',
                    'In the UK melatonin is a prescription medicine, used mainly for jet lag or specific sleep disorders rather than general insomnia. Products bought online are unregulated and their content is not guaranteed. If you think melatonin is relevant to your situation, that is a GP conversation.',
                ],
                [
                    'How do caffeine and alcohol affect sleep?',
                    'Caffeine lingers far longer than most people assume — half of an afternoon coffee can still be in your system at bedtime, so stop by early afternoon. Alcohol helps people fall asleep but fragments the second half of the night, which is why you wake at 3am unrefreshed after a few drinks.',
                ],
                [
                    'When should I see a GP about sleep?',
                    'If poor sleep has persisted for weeks despite a solid routine, if it is affecting your mood or safety, or if there is loud snoring with breathing pauses or gasping — that last one points to sleep apnoea and needs proper assessment.',
                ],
            ],
            'nhs_note' => null,
            'guide' => 'Sleep Problems: What Pharmacy Sleep Aids Can and Cannot DoWhy tablets are a short-term measure, and what actually treats insomnia.',
        ],
        [
            'name' => 'Sprains & Strains',
            'slug' => 'sprains',
            'az' => [
                'Sprains',
                'Strains',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'sprain strain ankle wrist twisted swelling bruising ligament muscle',
            'intro' => 'Most twisted ankles and pulled muscles heal well with the right first few days of care. Our pharmacists can advise on pain relief, supports and the signs that mean it needs an X-ray instead.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'Unable to put weight on the limb or use the joint at all',
                'The joint or limb looks deformed or out of shape',
                'Numbness, tingling, or the skin looking pale or blue below the injury',
                'You heard a crack or snap, or the pain is severe and getting worse',
            ],
            'sym' => [
                'Pain around a joint or muscle after a twist, fall or over-stretch',
                'Swelling that came on within hours',
                'Bruising',
                'Difficulty moving or using the area',
                'Tenderness when pressed',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is a sprain or strain?',
            'what' => [
                'A sprain is a stretched or torn ligament — the bands holding a joint together, most often at the ankle, wrist or knee. A strain is the same injury to a muscle or tendon, typically in the back, hamstring or calf. Both cause pain, swelling and bruising, and both usually heal within a couple of weeks with sensible care.',
                'The first 48–72 hours matter: protect it, rest it relatively, ice it for 15–20 minutes at a time, keep it lightly compressed and raised. After that, gentle movement heals better than complete rest. Our pharmacist can supply the right pain relief, a support if it helps, and tell you when a fracture needs ruling out.',
            ],
            'symptoms_heading' => 'You may have a sprain or strain if you have:',
            'symptoms' => [
                'Pain that started with a twist, knock, fall or over-stretch',
                'Swelling around the joint or muscle',
                'Bruising appearing over the following day or two',
                'Stiffness and difficulty using the area',
                'You can still put some weight on it or move it, even if it hurts',
            ],
            'faqs' => [
                [
                    'Sprain or break — how can I tell?',
                    'You can usually put some weight on a sprain, even if it is painful; being unable to take four steps, bone tenderness, an obvious deformity or numbness point towards a fracture and need an X-ray at urgent care or A&E.',
                ],
                [
                    'What pain relief is best?',
                    'Paracetamol is fine from the start. Ibuprofen gel or tablets help swelling but are best from around 48 hours onwards, and are not suitable for everyone — ask us. Avoid heat and alcohol in the first two days, which increase swelling.',
                ],
                [
                    'How long until it heals?',
                    'Mild sprains settle in one to two weeks; more severe ones can take six to eight. Start gentle movement as the pain allows. If it is no better after two weeks, or keeps giving way, get it assessed.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Teething',
            'slug' => 'teething',
            'az' => [
                'Teething',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'baby teething sore gums dribbling irritable infant',
            'intro' => 'Teething is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-teething.webp',
            'icon' => 'pf/pf-teething.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Sore or red gums',
                'More dribbling than usual',
                'Chewing on things',
                'Being unusually irritable',
                'A flushed cheek',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is teething?',
            'what' => [
                'Teething is the process of a baby\'s first teeth pushing through the gums, usually starting around six months — though anywhere from three months to after the first birthday is normal. The bottom front teeth generally arrive first, and the full set of 20 baby teeth is usually through by age two and a half.',
                'Some babies sail through it; others have days of sore gums, dribbling and grumpiness before each tooth. Our pharmacists can recommend safe, age-appropriate relief — from teething rings to sugar-free gels and the right doses of infant paracetamol or ibuprofen — and help you tell teething apart from illness.',
            ],
            'symptoms_heading' => 'Your baby may be teething if they have:',
            'symptoms' => [
                'Red, swollen or tender gums where a tooth is coming',
                'More dribbling than usual, sometimes with a mild chin rash',
                'Chewing and gnawing on fists, toys and anything in reach',
                'A flushed cheek on one side',
                'Grumpiness, unsettled sleep and mild temperature (below 38°C)',
            ],
            'faqs' => [
                [
                    'How can I soothe a teething baby?',
                    'Cold works best: a chilled (not frozen) teething ring, a cold wet flannel to chew, or chilled soft fruit in a mesh feeder for babies on solids. Gently rubbing the gum with a clean finger comforts many babies. If they\'re clearly in pain, infant paracetamol (from 2 months) or ibuprofen (from 3 months, over 5kg) at the correct dose helps — we\'ll check the dose with you.',
                ],
                [
                    'Are teething gels and powders safe?',
                    'Choose sugar-free teething gels made for babies and use them sparingly — evidence for how well they work is limited, but they\'re safe used as directed. Avoid gels containing salicylate, and be cautious with herbal granules and amber necklaces: necklaces are a genuine choking and strangulation hazard and are not recommended.',
                ],
                [
                    'Does teething cause fever or diarrhoea?',
                    'Teething can cause a slightly raised temperature, but not a true fever of 38°C or above — and it doesn\'t cause diarrhoea, vomiting or a rash. Those symptoms mean something else is going on, so treat them as you would any illness and get your baby checked rather than blaming the teeth.',
                ],
                [
                    'When do teeth come through, and what if they\'re late?',
                    'Typically: bottom front teeth around 5–7 months, top front 6–8 months, molars 12–16 months, and canines 16–20 months. Plenty of healthy babies are later at every stage. If there are no teeth at all by 18 months, mention it to your health visitor or dentist — usually all is well.',
                ],
                [
                    'How do I care for baby teeth once they arrive?',
                    'Start brushing as soon as the first tooth appears — twice a day with a smear of fluoride toothpaste (at least 1,000ppm). Avoid sugary drinks and never put juice in a bottle. Take your baby along to your own dental check-ups early, and get them registered with the dentist before their first birthday — NHS dental care is free for children.',
                ],
            ],
            'nhs_note' => 'Mouth Ulcers and Teething is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Teething: What Helps, What to Avoid, and What Is Not TeethingSafe relief, products to steer clear of, and symptoms never to blame on teeth.',
        ],
        [
            'name' => 'Threadworm',
            'slug' => 'threadworm',
            'az' => [
                'Threadworm',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS treatment is available if you are registered with a participating local GP and do not pay for prescriptions; everyone else can still get expert advice and low-cost treatment.',
            'kw' => 'itchy bottom worms children night anus',
            'intro' => 'Threadworm is one of the everyday conditions our pharmacists treat through our free NHS minor illness service — advice and treatment without a GP appointment.',
            'image' => 'cond/cond-threadworm.webp',
            'icon' => 'pf/pf-threadworm.webp',
            'flags' => [
                'Symptoms that are severe, rapidly worsening or not improving with treatment',
                'A very high temperature or feeling seriously unwell',
                'Symptoms in a baby under 3 months',
            ],
            'sym' => [
                'Itching around the bottom, worse at night',
                'Tiny white worms seen',
                'Disturbed sleep',
                'Someone else in the household has them',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What are threadworms?',
            'what' => [
                'Threadworms are tiny white worms — like short pieces of cotton thread — that live in the gut and lay eggs around the bottom at night, causing intense itching. They\'re extremely common in children and spread through swallowed eggs picked up from hands, surfaces and bedding.',
                'The itch-scratch-swallow cycle is how they keep going: scratching collects eggs under fingernails, and the eggs find their way back to the mouth. Treatment is a simple single-dose medicine for the whole household, paired with two weeks of strict hygiene to break the cycle — our pharmacist can supply everything and talk you through it.',
            ],
            'symptoms_heading' => 'Your child may have threadworms if they have:',
            'symptoms' => [
                'Intense itching around the bottom, worse at night',
                'Small white thread-like worms in poo or around the bottom',
                'Disturbed sleep, irritability and restlessness',
                'In girls, itching or redness around the vulva',
                'Sometimes no symptoms at all — spotting worms is the giveaway',
            ],
            'faqs' => [
                [
                    'How do children catch threadworms?',
                    'By swallowing the microscopic eggs — from contaminated hands, food, toys or surfaces. Eggs survive up to two weeks outside the body, so they pass easily around nurseries, schools and households. It has nothing to do with pets: threadworms only live in humans.',
                ],
                [
                    'How is threadworm treated?',
                    'A single dose of mebendazole for everyone in the household aged 2 and over (pregnant women, breastfeeding mums and under-2s need advice first — ask us). It kills the worms but not the eggs, so many people take a second dose after two weeks, combined with the hygiene measures that stop reinfection.',
                ],
                [
                    'What hygiene measures actually matter?',
                    'For two weeks: hands washed and nails scrubbed after every toilet trip and before every meal, nails kept short, a bath or shower each morning to wash away overnight eggs, underwear changed daily, and no nail-biting or thumb-sucking. Damp-dust bedrooms and wash bedding on treatment day.',
                ],
                [
                    'Does the whole family really need treating?',
                    'Yes — threadworm spreads so easily within a household that treating only the itchy child almost guarantees reinfection. Everyone takes the dose on the same day, whether they have symptoms or not, with the exceptions above who need pharmacist or GP advice first.',
                ],
                [
                    'Do threadworms cause any harm?',
                    'They\'re more unpleasant than dangerous — the main problems are itching, broken sleep and sore skin from scratching. Rarely, heavy or persistent infestations can cause weight loss or, in girls, urinary symptoms; if worms keep returning despite proper treatment and hygiene, see your GP.',
                ],
            ],
            'nhs_note' => 'Threadworm is covered by the NHS Minor Ailments Scheme. If you are exempt from prescription charges and registered with a Black Country GP, our pharmacist can supply the medicine free of charge — no GP appointment needed.',
            'guide' => 'Threadworm: Why You Treat the Whole House, Not Just the ChildThe hygiene fortnight that stops it coming straight back.',
        ],
        [
            'name' => 'Vaginal Thrush',
            'slug' => 'vaginal-thrush',
            'az' => [
                'Vaginal thrush',
                'Thrush (vaginal)',
            ],
            'tag' => 'Minor Ailments',
            'min' => 16,
            'max' => 60,
            'sex' => 'F',
            'age_label' => 'Women 16–60',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'thrush vaginal itching discharge candida fluconazole pessary',
            'intro' => 'Thrush is a common yeast infection that our pharmacists treat confidentially, with a single-dose tablet, pessary or cream — and no appointment.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'This is your first ever episode, or you are under 16 or over 60',
                'You are pregnant, or think you might be',
                'More than two episodes in the last six months',
                'Unusual bleeding, sores, lower tummy pain, a high temperature or an unusual-smelling discharge',
            ],
            'sym' => [
                'Itching, soreness or irritation around the vagina or vulva',
                'Thick, white, cottage-cheese-like discharge',
                'Stinging when you wee',
                'Discomfort or pain during sex',
                'Redness or swelling of the vulva',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What is vaginal thrush?',
            'what' => [
                'Thrush is an overgrowth of the Candida yeast that normally lives harmlessly in the vagina. Antibiotics, pregnancy, diabetes, tight synthetic clothing and a run-down immune system can all tip the balance. It is not a sexually transmitted infection, though sex can make the soreness worse.',
                'Treatment from the pharmacy is straightforward: a single fluconazole capsule, a clotrimazole pessary, or both with a soothing cream. Our pharmacist will check the symptoms fit, rule out the things that need a GP or sexual-health clinic instead, and supply treatment discreetly in the consultation room.',
            ],
            'symptoms_heading' => 'You may have thrush if you have:',
            'symptoms' => [
                'Itching and irritation around the vagina and vulva',
                'A thick white discharge that does not usually smell',
                'Soreness, redness or swelling of the vulva',
                'Stinging when passing urine or during sex',
                'Symptoms you recognise from a previous, diagnosed episode',
            ],
            'faqs' => [
                [
                    'Tablet, pessary or cream?',
                    'The single fluconazole tablet is the simplest; a clotrimazole pessary works locally and suits people who cannot take the tablet; the cream soothes external itching and is usually used alongside one of the others. They work equally well — it is a matter of preference and your other medicines.',
                ],
                [
                    'My partner has symptoms too — do they need treating?',
                    'Only if they have symptoms. Thrush is not an STI, but it can cause itching or redness in a male partner, which clears with a short course of cream.',
                ],
                [
                    'It keeps coming back — what should I do?',
                    'Four or more episodes a year needs a GP review to confirm it really is thrush, check for diabetes or other causes, and consider a longer preventive course. Avoid perfumed products, wear cotton underwear, and finish any treatment fully.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
        [
            'name' => 'Vitamins & Supplements',
            'slug' => 'vitamins-supplements',
            'az' => [
                'Vitamins & supplements',
            ],
            'tag' => 'Advice',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => '',
            'intro' => 'Which supplements are genuinely worth taking, which are not, and which interact with your medicines — straight answers from our pharmacists, free of charge.',
            'image' => 'cond/cond-vitamins.webp',
            'icon' => null,
            'flags' => [
                'Unexplained tiredness, breathlessness or weight loss — these need investigating, not supplementing',
                'Pregnancy, or trying to conceive — some supplements must be avoided, and vitamin A in particular',
                'Taking blood thinners, thyroid, epilepsy or transplant medicines — several supplements interact',
            ],
            'sym' => [
                'Wondering whether you need a supplement at all, and which',
                'Taking several supplements and unsure whether they overlap or clash',
                'Prescribed medicines alongside supplements, with no one having checked the combination',
                'Pregnancy, breastfeeding, or planning a pregnancy',
                'A restricted diet — vegan, vegetarian, or limited by illness or appetite',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'About vitamins and supplements',
            'what' => [
                'A balanced diet supplies most of what most people need, and the supplement aisle sells a great deal that healthy adults simply do not require. There are real exceptions, though, and they matter. Everyone in the UK is advised to consider a daily vitamin D supplement through autumn and winter, because we cannot make enough from sunlight in those months. Folic acid before and in early pregnancy genuinely prevents harm. Vitamin B12 matters if you eat no animal products, and iron matters if you are deficient — but only if you actually are.',
                'The part that gets overlooked is interactions. Iron and calcium block the absorption of several antibiotics and thyroid tablets. Vitamin K affects warfarin. St John’s wort interferes with a long list of medicines including contraception and antidepressants. High-dose supplements are not automatically safer than medicines just because they are sold on a shelf — and more is not better. That is exactly the conversation to have with a pharmacist rather than a search engine.',
            ],
            'symptoms_heading' => 'Come and see us if you have:',
            'symptoms' => [
                'Wondering whether you need a supplement at all, and which',
                'Taking several supplements and unsure whether they overlap or clash',
                'Prescribed medicines alongside supplements, with no one having checked the combination',
                'Pregnancy, breastfeeding, or planning a pregnancy',
                'A restricted diet — vegan, vegetarian, or limited by illness or appetite',
            ],
            'faqs' => [
                [
                    'Does everyone need vitamin D?',
                    'From October to March, UK adults and children over one are advised to consider a daily vitamin D supplement, because sunlight here is too weak for us to make our own. People who are rarely outdoors, who cover their skin, or who have darker skin may benefit all year round.',
                ],
                [
                    'Are multivitamins worth taking?',
                    'For most people eating reasonably well, they are unnecessary — and they often contain small, token amounts of the things that would actually help alongside large amounts of things you already get plenty of. Targeting a specific need is almost always better value than a broad multivitamin.',
                ],
                [
                    'Can supplements interfere with my medicines?',
                    'Yes, and some interactions are significant. Iron and calcium reduce absorption of certain antibiotics and thyroid medication; vitamin K affects warfarin; St John’s wort interacts with contraception, antidepressants and more. Bring everything you take and we will check it properly.',
                ],
                [
                    'Which supplements matter in pregnancy?',
                    'Folic acid before conception and through the first twelve weeks, plus vitamin D throughout, are the recommended ones. Vitamin A supplements and high-dose fish liver oils should be avoided in pregnancy. Ask us before starting anything else.',
                ],
                [
                    'Is it possible to take too much?',
                    'Definitely. Fat-soluble vitamins A, D, E and K accumulate in the body, and high-dose iron, zinc and selenium can all cause harm. Stacking several products that each contain the same ingredient is the usual way people end up over the safe limit without realising.',
                ],
            ],
            'nhs_note' => null,
            'guide' => 'Vitamin D and the Rest: Which Supplements Are Actually Worth TakingThe ones with NHS backing, and the ones that are mostly expensive urine.',
        ],
        [
            'name' => 'Warts & Verrucas',
            'slug' => 'warts',
            'az' => [
                'Warts',
                'Verrucas',
            ],
            'tag' => 'Minor Ailments',
            'min' => 0,
            'max' => 120,
            'sex' => null,
            'age_label' => 'All ages',
            'elig' => 'Free NHS advice and, where appropriate, treatment under the local minor ailments scheme if you are registered with a participating GP and do not pay for prescriptions. Everyone else still gets expert advice and low-cost treatment.',
            'kw' => 'wart verruca foot hand salicylic acid freeze plantar',
            'intro' => 'Warts and verrucas are harmless, stubborn and eventually go away on their own — but if one is painful or bothering you, our pharmacists can supply treatment that speeds things up.',
            'image' => null,
            'icon' => null,
            'flags' => [
                'A wart on the face, genitals or near the eye',
                'A lump that bleeds, changes colour or shape, or has an irregular edge',
                'You have diabetes, poor circulation or a weakened immune system',
                'No improvement after 12 weeks of treatment',
            ],
            'sym' => [
                'A small, rough, raised lump on the hands, fingers or knees',
                'A flat, hard patch on the sole of the foot that hurts to walk on (verruca)',
                'Tiny black dots within the lump',
                'A cluster of several small lumps',
                'Skin lines that bend around the lump rather than through it',
            ],
            'dur' => null,
            'screening' => null,
            'what_heading' => 'What are warts and verrucas?',
            'what' => [
                'Warts are caused by the human papillomavirus infecting the top layer of skin, producing a rough, firm growth. A verruca is simply a wart on the sole of the foot, pushed flat by walking and often painful because of it. They spread by skin contact and via damp floors — hence swimming pools and changing rooms.',
                'Left alone, most disappear within two years as the immune system catches up, so treatment is optional and about comfort. Salicylic acid applied daily for up to twelve weeks, or freezing with a pharmacy cryotherapy spray, are the two evidence-based options; our pharmacist can advise which suits you and check it is not something else.',
            ],
            'symptoms_heading' => 'You may have a wart or verruca if you have:',
            'symptoms' => [
                'A rough, firm, skin-coloured lump, often on the hands or knees',
                'A flat, hard, sometimes painful patch on the sole of the foot',
                'Small black dots in the surface',
                'Several lumps grouped together',
                'A lump that has been there for weeks and is slowly growing',
            ],
            'faqs' => [
                [
                    'How do I treat a wart?',
                    'Soak, file the surface gently with an emery board, apply salicylic acid, cover, and repeat every day for up to twelve weeks — consistency is everything. Freezing sprays are an alternative but can be painful and are not suitable for young children or the face.',
                ],
                [
                    'Can I still go swimming?',
                    'Yes — cover a verruca with a waterproof plaster or a verruca sock, do not share towels, and avoid walking barefoot in changing rooms to protect others.',
                ],
                [
                    'Is it a wart or something else?',
                    'Corns, calluses, skin tags and moles all get mistaken for warts. Anything that bleeds, changes, has an irregular edge or is on the face or genitals should be checked rather than treated over the counter.',
                ],
            ],
            'nhs_note' => null,
            'guide' => null,
        ],
    ],
];
