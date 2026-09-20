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

@section('title', 'Contact Us | جمعية المعاقين الخيرية')
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
    <p class="page-subtitle"><span class="en">Reach out to us for inquiries, referrals, volunteering, partnerships, or donations. We are here to help.</span><span class="ar">تواصل معنا للاستفسارات والإحالات والتطوع والشراكات أو التبرعات. نحن هنا للمساعدة.</span></p>
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
              <span class="en">{{ $contact['address_en'] ?: 'Main Street, Nablus, West Bank, Palestine' }}</span>
              <span class="ar">{{ $contact['address_ar'] ?: 'الشارع الرئيسي، نابلس، الضفة الغربية، فلسطين' }}</span>
            </div>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Phone</span><span class="ar">الهاتف</span></div>
            <div class="contact-value">{{ $contact['phone'] ?: '+970 9 XXX XXXX' }}</div>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Email</span><span class="ar">البريد الإلكتروني</span></div>
            <div class="contact-value">{{ $contact['email'] ?: 'info@bsd-nablus.ps' }}</div>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Working Hours</span><span class="ar">ساعات العمل</span></div>
            <div class="contact-value"><span class="en">Sunday – Thursday, 8:00 AM – 4:00 PM</span><span class="ar">الأحد – الخميس، 8:00 ص – 4:00 م</span></div>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9"/></svg></div>
          <div>
            <div class="contact-label"><span class="en">Follow Us</span><span class="ar">تابعنا</span></div>
            <div class="contact-value"><span class="en">Facebook · Instagram · WhatsApp</span><span class="ar">فيسبوك · إنستغرام · واتساب</span></div>
          </div>
        </div>
      </div>

      <div style="background:var(--red-dark);border-radius:16px;padding:1.75rem;margin-top:1.25rem;color:white;">
        <div style="font-weight:700;font-size:16px;margin-bottom:.5rem;"><span class="en">Want to Support Us?</span><span class="ar">تريد دعمنا؟</span></div>
        <p style="color:rgba(255,255,255,.75);font-size:14px;line-height:1.65;margin-bottom:1rem;"><span class="en">Your donation directly funds wheelchairs, therapy sessions, school materials, and more for persons with disabilities in need.</span><span class="ar">تبرعك يموّل مباشرةً الكراسي المتحركة وجلسات العلاج والمستلزمات الدراسية وغيرها لذوي الإعاقة المحتاجين.</span></p>
        <button style="padding:10px 22px;background:white;color:var(--red-dark);border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit;"><span class="en">Donate Now</span><span class="ar">تبرع الآن</span></button>
      </div>
    </div>

    <div class="contact-form">
      <div class="section-label" style="margin-bottom:1rem;"><span class="en">Send a Message</span><span class="ar">أرسل رسالة</span></div>
      <div class="form-row">
        <div class="form-group"><label><span class="en">First Name</span><span class="ar">الاسم الأول</span></label><input type="text" placeholder="Your first name / اسمك الأول"></div>
        <div class="form-group"><label><span class="en">Last Name</span><span class="ar">اسم العائلة</span></label><input type="text" placeholder="Your last name / اسم العائلة"></div>
      </div>
      <div class="form-group"><label><span class="en">Email Address</span><span class="ar">البريد الإلكتروني</span></label><input type="email" placeholder="your@email.com"></div>
      <div class="form-group"><label><span class="en">Phone Number</span><span class="ar">رقم الهاتف</span></label><input type="tel" placeholder="+970 ..."></div>
      <div class="form-group">
        <label><span class="en">Reason for Contact</span><span class="ar">سبب التواصل</span></label>
        <select>
          <option value=""><span class="en">Select a reason…</span></option>
          <option value="services"><span class="en">Inquire about services</span></option>
          <option value="referral"><span class="en">Patient / Beneficiary referral</span></option>
          <option value="volunteer"><span class="en">Volunteering</span></option>
          <option value="donate"><span class="en">Donation / Partnership</span></option>
          <option value="media"><span class="en">Media / Press inquiry</span></option>
          <option value="other"><span class="en">Other</span></option>
        </select>
      </div>
      <div class="form-group"><label><span class="en">Message</span><span class="ar">الرسالة</span></label><textarea placeholder="Write your message here… / اكتب رسالتك هنا…"></textarea></div>
      <button class="form-submit" onclick="alert('Thank you! We will get back to you soon. شكراً! سنتواصل معك قريباً.');"><span class="en">Send Message</span><span class="ar">إرسال الرسالة</span></button>
    </div>
  </div>
</div>

<div class="contact-strip">
  <div class="contact-strip-inner">
    <div class="cs-text">
      <h3><span class="en">Get in Touch or Support Our Work</span><span class="ar">تواصل معنا أو ادعم عملنا</span></h3>
      <p><span class="en">We welcome volunteers, donors, partners, and community members.</span><span class="ar">نرحب بالمتطوعين والمتبرعين والشركاء وأفراد المجتمع.</span></p>
    </div>
    <div class="cs-actions">
      <button class="btn-white" onclick="location.href='{{ route('contact') }}'"><span class="en">Contact Us</span><span class="ar">تواصل معنا</span></button>
      <button class="btn-outline-white"><span class="en">Donate Now</span><span class="ar">تبرع الآن</span></button>
    </div>
  </div>
</div>

@endsection
