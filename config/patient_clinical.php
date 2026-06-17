<?php

/*
|--------------------------------------------------------------------------
| Clinical dropdown options for the new / edit patient forms
|--------------------------------------------------------------------------
| Stored as comma-separated strings in the patients table to keep
| schema-compat with existing free-text columns. Add/remove freely —
| the chip multi-select widget renders whatever is listed here.
*/

return [
    'reasons' => [
        'Weight management',
        'Diabetes management',
        'Blood pressure / cardiovascular',
        'Cholesterol management',
        'Renal diet',
        'Gastrointestinal concerns',
        'Sports performance',
        'Pregnancy / lactation',
        'Pediatric nutrition',
        'Eating disorder support',
        'Food allergies / intolerances',
        'General wellness',
    ],

    'referrers' => [
        'GP / Family doctor',
        'Endocrinologist',
        'Cardiologist',
        'Gastroenterologist',
        'Nephrologist',
        'Oncologist',
        'Gynaecologist',
        'Paediatrician',
        'Hospital / Clinic',
        'Other dietitian',
        'Personal trainer',
        'Self-referral',
    ],

    'allergies' => [
        'Peanuts',
        'Tree nuts',
        'Dairy / Lactose',
        'Eggs',
        'Gluten / Wheat',
        'Soy',
        'Shellfish',
        'Fish',
        'Sesame',
        'Sulfites',
    ],

    'conditions' => [
        'Type 1 diabetes',
        'Type 2 diabetes',
        'Hypertension',
        'Hyperlipidemia',
        'Cardiovascular disease',
        'Obesity',
        'Chronic kidney disease',
        'Hypothyroidism',
        'PCOS',
        'IBS',
        'Crohn\'s disease',
        'Celiac disease',
        'GERD / Reflux',
        'Cancer',
        'Anaemia',
    ],

    'medications' => [
        'Metformin',
        'Insulin',
        'Statins',
        'ACE inhibitors',
        'Beta-blockers',
        'Diuretics',
        'NSAIDs',
        'PPIs',
        'Levothyroxine',
        'SSRIs',
        'Steroids',
        'Anticoagulants',
    ],
];
