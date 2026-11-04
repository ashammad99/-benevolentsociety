{{--
    BSD Public Site — Program: Assistive Devices
    resources/views/public/programs/devices.blade.php
    Phase 4: converted from resources/views/prog-devices.html (static
    content preserved verbatim; only the layout wrapper and links fixed).
--}}
@extends('layouts.public')

@section('title', 'Assistive Devices Aid')
@section('active_nav', 'devices')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);padding:0 0 0 0;">
  <div class="program-hero" style="padding-top:3.5rem;padding-bottom:3.5rem;">
    <div class="program-hero-text">
      <div class="breadcrumb">
        <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
        <span>›</span>
        <span><span class="en">Programs</span><span class="ar">البرامج</span></span>
        <span>›</span>
        <span><span class="en">Assistive Devices Aid</span><span class="ar">دعم الأجهزة المساعدة</span></span>
      </div>
      <div class="program-badge" style="background:#1ABC9C22;color:#1ABC9C;">
        <span class="en">Devices</span><span class="ar">أجهزة</span>
      </div>
      <div class="program-big-title" style="color:var(--red-dark);">
        <span class="en">Assistive Devices Aid</span><span class="ar">دعم الأجهزة المساعدة</span>
      </div>
      <p class="program-lead">
        <span class="en">Our assistive devices program ensures that no one is left behind due to inability to afford life-changing mobility and communication aids — distributing wheelchairs, hearing aids, prosthetics, and more to those who need them.</span><span class="ar">يضمن برنامج الأجهزة المساعدة أن لا يُحرم أحد من الأجهزة المُحوِّلة للحياة بسبب العجز المالي — من كراسي متحركة وسماعات وأطراف اصطناعية وغيرها.</span>
      </p>
      <div class="program-stats-row">
        <div class="pstat"><div class="pstat-num" style="color:#1ABC9C;">850+</div><div class="pstat-label"><span class="en">Devices/Year</span><span class="ar">جهاز/سنة</span></div></div>
        <div class="pstat"><div class="pstat-num" style="color:#1ABC9C;">15+</div><div class="pstat-label"><span class="en">Device Types</span><span class="ar">نوع جهاز</span></div></div>
        <div class="pstat"><div class="pstat-num" style="color:#1ABC9C;">100%</div><div class="pstat-label"><span class="en">Locally Fitted</span><span class="ar">مُجهَّز محلياً</span></div></div>
      </div>
    </div>
    <div class="program-image" style="background-image:url('https://images.unsplash.com/photo-1593113630400-ea4288922559?w=900&q=80');"></div>
  </div>
</div>
<div class="section-wrap">
  <div class="section-label"><span class="en">What We Offer</span><span class="ar">ما نقدمه</span></div>
  <div class="section-title"><span class="en">Program Services</span><span class="ar">خدمات البرنامج</span></div>
  <div class="services-list">
        <div class="service-item">
          <div class="service-dot" style="background:#1ABC9C;"></div>
          <div class="service-text">
            <strong><span class="en">Manual & Powered Wheelchairs</span><span class="ar">كراسي متحركة يدوية وكهربائية</span></strong>
            <span><span class="en">Standard, customized, and powered wheelchairs fitted and adjusted by trained technicians.</span><span class="ar">كراسي متحركة قياسية ومخصصة وكهربائية يُجهّزها ويضبطها فنيون مدربون.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#1ABC9C;"></div>
          <div class="service-text">
            <strong><span class="en">Hearing Aids & Accessories</span><span class="ar">سماعات الأذن وملحقاتها</span></strong>
            <span><span class="en">Digital hearing aids with audiologist assessment, fitting, and follow-up programming.</span><span class="ar">سماعات سمعية رقمية مع تقييم أمراض السمع والتجهيز والبرمجة المتابعة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#1ABC9C;"></div>
          <div class="service-text">
            <strong><span class="en">Prosthetic & Orthotic Devices</span><span class="ar">الأطراف الاصطناعية والتقويمية</span></strong>
            <span><span class="en">Upper and lower limb prosthetics and orthotic braces fabricated or procured to individual measurements.</span><span class="ar">أطراف اصطناعية علوية وسفلية وأجهزة تقويمية مُصنَّعة أو مُستورَدة وفق القياسات الفردية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#1ABC9C;"></div>
          <div class="service-text">
            <strong><span class="en">Walking Aids</span><span class="ar">أدوات المشي</span></strong>
            <span><span class="en">Crutches, walkers, canes, and rollators distributed based on clinical assessment.</span><span class="ar">عكازات ومشايات وعصي وجهاز رولاتور تُوزَّع بناءً على التقييم السريري.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#1ABC9C;"></div>
          <div class="service-text">
            <strong><span class="en">Communication Devices</span><span class="ar">أجهزة التواصل</span></strong>
            <span><span class="en">Augmentative and alternative communication (AAC) devices for those unable to speak.</span><span class="ar">أجهزة التواصل المُعزِّز والبديل للأشخاص غير القادرين على الكلام.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#1ABC9C;"></div>
          <div class="service-text">
            <strong><span class="en">Repair & Maintenance</span><span class="ar">الإصلاح والصيانة</span></strong>
            <span><span class="en">In-house workshop providing free repair, adjustment, and maintenance of previously distributed devices.</span><span class="ar">ورشة داخلية توفر إصلاحاً مجانياً وضبطاً وصيانةً للأجهزة الموزَّعة سابقاً.</span></span>
          </div>
        </div></div>
</div>
<div style="background:var(--gray-50);padding:1px 0;">
  <div class="section-wrap">
    <div class="section-label"><span class="en">Quick Facts</span><span class="ar">حقائق سريعة</span></div>
    <div class="section-title"><span class="en">Program Details</span><span class="ar">تفاصيل البرنامج</span></div>
    <div class="info-grid">
        <div class="info-card">
          <h4><span class="en">Assessment Process</span><span class="ar">عملية التقييم</span></h4>
          <p><span class="en">Each beneficiary undergoes a clinical needs assessment before a device is recommended and fitted.</span><span class="ar">يخضع كل مستفيد لتقييم احتياجات سريري قبل التوصية بجهاز وتجهيزه.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Cost</span><span class="ar">التكلفة</span></h4>
          <p><span class="en">Devices are fully subsidized for eligible beneficiaries based on income and disability documentation.</span><span class="ar">الأجهزة مدعومة بالكامل للمستفيدين المؤهلين بناءً على الدخل ووثائق الإعاقة.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Eligibility</span><span class="ar">الأهلية</span></h4>
          <p><span class="en">Open to all residents of the Nablus governorate with documented physical disability.</span><span class="ar">مفتوح لجميع سكان محافظة نابلس ذوي الإعاقة الجسدية الموثقة.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Wait Time</span><span class="ar">وقت الانتظار</span></h4>
          <p><span class="en">Most devices are available within 2–4 weeks. Custom-fitted items may take 6–10 weeks.</span><span class="ar">معظم الأجهزة متوفرة خلال 2–4 أسابيع. المنتجات المصنوعة خصيصاً قد تستغرق 6–10 أسابيع.</span></p>
        </div></div>
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
