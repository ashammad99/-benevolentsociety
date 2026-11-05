<?php

/*
|--------------------------------------------------------------------------
| Organisation identity
|--------------------------------------------------------------------------
|
| The organisation's name, logo and fixed details, used by the public site,
| the login page and the admin panel. Edit this file to rebrand the site.
|
| Bilingual values are ['en' => ..., 'ar' => ...]. Set an optional value to
| null to hide it.
|
| Contact details and SEO meta are editable in Admin → Settings; the
| 'fallback' values below are shown only while those settings are empty.
|
| After editing on a server where config is cached: php artisan config:cache
|
*/

// Where the organisation works. Used in sentences on the home, vision and
// program pages ("…across [City] and [Region]…", "…under the law of
// [Country]") and in the defaults below.
$city = ['en' => 'Gaza City', 'ar' => 'مدينة غزة'];
$region = ['en' => 'the Gaza Strip', 'ar' => 'قطاع غزة'];
$country = ['en' => 'Palestine', 'ar' => 'فلسطين'];

return [

    'city' => $city,
    'region' => $region,
    'country' => $country,

    'name' => [
        'en' => 'Benevolent Society for Disabled',
        'ar' => 'جمعية دار المعاقين الخيرية',
    ],

    // Shown where space is tight (browser tab of the admin panel, logo alt text).
    'short_name' => 'BSD',

    // Path under public/.
    'logo' => 'images/logo.jpg',

    // One line under the name in the footer.
    'tagline' => [
        'en' => "Serving people with disabilities and their families in {$city['en']} and {$region['en']}.",
        'ar' => "نخدم ذوي الإعاقة وأسرهم في {$city['ar']} و{$region['ar']}.",
    ],

    // Footer legal line (null hides it).
    'registration' => [
        'en' => "Registered under the NGO law of {$country['en']}",
        'ar' => "مرخصة بموجب قانون الجمعيات في {$country['ar']}",
    ],

    // Website credit in the footer's bottom line (null hides it).
    'credit' => [
        'name' => ['en' => 'Ahmed Hammad', 'ar' => 'أحمد حماد'],
        'url' => 'https://www.linkedin.com/in/ahmed-hammad-461337177/',
    ],

    // Contact page (null hides it).
    'office_hours' => [
        'en' => 'Sunday – Thursday, 8:00 AM – 4:00 PM',
        'ar' => 'الأحد – الخميس، 8:00 ص – 4:00 م',
    ],

    // Headline numbers: the bar under the home page slider (all entries) and
    // the login page panel (first three). Empty hides both. Format:
    //   ['value' => '1,200+', 'label' => ['en' => 'Beneficiaries', 'ar' => 'مستفيد']],
    'stats' => [],

    // Three numbers at the top of each program page, same format as 'stats'.
    // The first one also appears as the tag on that program's home page card.
    // Empty hides both.
    'program_stats' => [
        'rehab' => [],
        'education' => [],
        'vocational' => [],
        'social' => [],
        'homecare' => [],
        'devices' => [],
    ],

    // Choices in the contact form's "Reason for Contact" list; the key is
    // what the form submits and what the email reports.
    'contact_reasons' => [
        'services' => ['en' => 'Inquire about services', 'ar' => 'الاستفسار عن الخدمات'],
        'referral' => ['en' => 'Patient / beneficiary referral', 'ar' => 'إحالة مريض / مستفيد'],
        'volunteer' => ['en' => 'Volunteering', 'ar' => 'التطوع'],
        'partnership' => ['en' => 'Partnership', 'ar' => 'الشراكة'],
        'media' => ['en' => 'Media / press inquiry', 'ar' => 'استفسار إعلامي / صحفي'],
        'other' => ['en' => 'Other', 'ar' => 'أخرى'],
    ],

    'fallback' => [
        'email' => 'info@benevolentsociety.ps',
        'phone' => '+972 8 284 4810',
        'address' => [
            'en' => 'Gaza City, Gaza Strip, Palestine',
            'ar' => 'مدينة غزة، قطاع غزة، فلسطين',
        ],
        'meta_description' => "A registered local NGO serving persons with disabilities and their families in {$city['en']} and {$region['en']}.",
    ],

];
