{{--
    BSD Public Site — Contact
    resources/views/public/contact.blade.php

    Phase 4: converted from the old static resources/views/contact.html.
    Address/phone/email now come from the Settings CMS ($contact, from
    PageController@contact) instead of being hardcoded; everything else
    (working hours, the contact form) is unchanged static content — there is
    no backend to receive the form (no Contact model/mailer exists), so it
    stays a front-end-only demo as it was before, per Phase 4 scope.
--}}
@extends('layouts.public')

@section('title', 'Contact Us')
@section('active_nav', 'contact')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);">
  <div style="max-width:700px;margin:0 auto;text-align:center;padding:4rem 2.5rem 3rem;">
    <div class="breadcrumb" style="justify-content:center;">
      <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
      <span>›</span>
      <span><span class="en">Contact Us</span><span class="ar">تواصل معنا</span></span>
    </div>
    <div class="page-title"><span class="en">Contact Us</span><span class="ar">تواصل معنا</span></div>
    <p class="page-subtitle"><span class="en">Reach out to us for inquiries, referrals, volunteering or partnerships. We are here to help.</span><span class="ar">تواصل معنا للاستفسارات والإحالات والتطوع أو الشراكات. نحن هنا للمساعدة.</span></p>
  </div>
</div>

<div class="section-wrap">
  <div class="contact-grid">
    <div>
      <div class="contact-info-card">
        <div class="section-label" style="margin-bottom:1rem;"><span class="en">Get in Touch</span><span class="ar">تواصل معنا</span></div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Address</span><span class="ar">العنوان</span></div>
            <div class="contact-value">
              <span class="en">{{ $contact['address_en'] ?: config('society.fallback.address.en') }}</span>
              <span class="ar">{{ $contact['address_ar'] ?: config('society.fallback.address.ar') }}</span>
            </div>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Phone</span><span class="ar">الهاتف</span></div>
            <div class="contact-value"><span dir="ltr">{{ $contact['phone'] ?: config('society.fallback.phone') }}</span></div>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Email</span><span class="ar">البريد الإلكتروني</span></div>
            <div class="contact-value">{{ $contact['email'] ?: config('society.fallback.email') }}</div>
          </div>
        </div>
        @if (config('society.office_hours'))
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Working Hours</span><span class="ar">ساعات العمل</span></div>
            <div class="contact-value"><span class="en">{{ config('society.office_hours.en') }}</span><span class="ar">{{ config('society.office_hours.ar') }}</span></div>
          </div>
        </div>
        @endif
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Follow Us</span><span class="ar">تابعنا</span></div>
            <div class="contact-value"><span class="en">Facebook · Instagram · WhatsApp</span><span class="ar">فيسبوك · إنستغرام · واتساب</span></div>
          </div>
        </div>
      </div>

    </div>

    <form class="contact-form" id="contact-form" method="POST" action="{{ route('contact.send') }}">
      @csrf
      <div class="section-label" style="margin-bottom:1rem;"><span class="en">Send a Message</span><span class="ar">أرسل رسالة</span></div>

      @if (session('contact_sent'))
        <div class="form-alert form-alert-success" role="status"><span class="en">Thank you, your message has been sent. We will get back to you soon.</span><span class="ar">شكراً لك، تم إرسال رسالتك. سنتواصل معك قريباً.</span></div>
      @endif
      @if (session('contact_error'))
        <div class="form-alert form-alert-error" role="alert">{{ session('contact_error') }}</div>
      @endif

      {{-- Spam trap: hidden from people, filled in by bots. --}}
      <div class="hp-field" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <div class="form-row">
        <div class="form-group">
          <label for="cf-first"><span class="en">First Name</span><span class="ar">الاسم الأول</span></label>
          <input id="cf-first" type="text" name="first_name" value="{{ old('first_name') }}" maxlength="100" required autocomplete="given-name" placeholder="{{ __('Your first name') }}">
          @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label for="cf-last"><span class="en">Last Name</span><span class="ar">اسم العائلة</span></label>
          <input id="cf-last" type="text" name="last_name" value="{{ old('last_name') }}" maxlength="100" required autocomplete="family-name" placeholder="{{ __('Your last name') }}">
          @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-group">
        <label for="cf-email"><span class="en">Email Address</span><span class="ar">البريد الإلكتروني</span></label>
        <input id="cf-email" type="email" name="email" value="{{ old('email') }}" maxlength="191" required autocomplete="email" dir="ltr" placeholder="your@email.com">
        @error('email')<div class="field-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label for="cf-phone"><span class="en">Phone Number</span><span class="ar">رقم الهاتف</span></label>
        <input id="cf-phone" type="tel" name="phone" value="{{ old('phone') }}" maxlength="30" autocomplete="tel" dir="ltr" placeholder="+970 ...">
        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label for="cf-reason"><span class="en">Reason for Contact</span><span class="ar">سبب التواصل</span></label>
        <select id="cf-reason" name="reason" required>
          <option value="">{{ __('Select a reason…') }}</option>
          @foreach (config('society.contact_reasons') as $key => $label)
            <option value="{{ $key }}" @selected(old('reason') === $key)>{{ $label[app()->getLocale()] ?? $label['en'] }}</option>
          @endforeach
        </select>
        @error('reason')<div class="field-error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label for="cf-message"><span class="en">Message</span><span class="ar">الرسالة</span></label>
        <textarea id="cf-message" name="message" maxlength="5000" required placeholder="{{ __('Write your message here…') }}">{{ old('message') }}</textarea>
        @error('message')<div class="field-error">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="form-submit"><span class="en">Send Message</span><span class="ar">إرسال الرسالة</span></button>
    </form>
  </div>
</div>


@endsection
