<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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

    // ── Contact form → email to the organisation ─────────────────────────
    public function sendContact(Request $request)
    {
        // Hidden "website" field: people never see it, bots fill it in.
        // Pretend it worked so the bot learns nothing.
        if ($request->filled('website')) {
            return redirect()->to(route('contact').'#contact-form')->with('contact_sent', true);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:30'],
            'reason' => ['required', Rule::in(array_keys(config('society.contact_reasons')))],
            'message' => ['required', 'string', 'max:5000'],
        ]);
        // validate() leaves out optional fields that weren't submitted at all.
        $data += ['phone' => null];

        $to = Setting::get('email') ?: config('society.fallback.email');

        try {
            Mail::to($to)->send(new ContactMessage($data));
        } catch (\Throwable $e) {
            report($e);

            return redirect()->to(route('contact').'#contact-form')
                ->withInput()
                ->with('contact_error', __('Your message could not be sent. Please try again later or contact us by phone or email.'));
        }

        return redirect()->to(route('contact').'#contact-form')->with('contact_sent', true);
    }
}
