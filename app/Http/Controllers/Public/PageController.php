<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class PageController extends Controller
{
    public function vision()
    {
        return view('public.vision');
    }

    public function contact()
    {
        $contact = [
            'email'      => Setting::get('email'),
            'phone'      => Setting::get('phone'),
            'address_en' => Setting::get('address', 'en'),
            'address_ar' => Setting::get('address', 'ar'),
        ];

        return view('public.contact', compact('contact'));
    }
}
