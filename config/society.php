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
$city = ['en' => '[City]', 'ar' => '[المدينة]'];
$region = ['en' => '[Region]', 'ar' => '[المنطقة]'];
$country = ['en' => '[Country]', 'ar' => '[الدولة]'];

return [

    'city' => $city,
    'region' => $region,
    'country' => $country,

    'name' => [
        'en' => 'Benevolent Society for Disabled',
        'ar' => 'جمعية المعاقين الخيرية',
    ],

    // Shown where space is tight (browser tab of the admin panel, logo alt text).
    'short_name' => 'BSD',

    // Path under public/.
    'logo' => 'images/logo.jpg',

    // One line under the name in the footer.
    'tagline' => [
        'en' => "Serving persons with disabilities and their families in {$city['en']} and {$region['en']} with dignity, care, and professionalism.",
        'ar' => "نخدم ذوي الإعاقة وأسرهم في {$city['ar']} و{$region['ar']} بكرامة وعناية واحترافية.",
    ],

    // Footer legal line (null hides it).
    'registration' => [
        'en' => "Registered under the NGO law of {$country['en']}",
        'ar' => "مرخصة بموجب قانون الجمعيات في {$country['ar']}",
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

    'fallback' => [
        'email' => 'info@bsd-nablus.ps',
        'phone' => '+970 9 XXX XXXX',
        'address' => [
            'en' => "{$city['en']}, {$region['en']}",
            'ar' => "{$city['ar']}، {$region['ar']}",
        ],
        'meta_description' => "A registered local NGO serving persons with disabilities and their families in {$city['en']} and {$region['en']}.",
    ],

];
