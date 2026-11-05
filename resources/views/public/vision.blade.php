{{--
    BSD Public Site — Vision & Mission
    resources/views/public/vision.blade.php

    Phase 4: converted from the old static resources/views/vision.html.
    Content is bespoke marketing copy (vision/mission cards, strategic aims,
    core values) with no CMS model behind it, so it stays static — only the
    layout wrapper and navigation links were fixed to use real routes.
--}}
@extends('layouts.public')

@section('title', 'Vision & Mission')
@section('active_nav', 'vision')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);">
  <div style="max-width:820px;margin:0 auto;text-align:center;padding:4rem 2.5rem 3rem;">
    <div class="breadcrumb" style="justify-content:center;">
      <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
      <span>›</span>
      <span><span class="en">Vision &amp; Mission</span><span class="ar">رؤيتنا ومهمتنا</span></span>
    </div>
    <div class="vision-icon">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
    </div>
    <div class="page-title"><span class="en">Our Vision &amp; Mission</span><span class="ar">رؤيتنا ومهمتنا</span></div>
    <p class="page-subtitle"><span class="en">The principles and aspirations that guide every program, project, and decision we make.</span><span class="ar">المبادئ والتطلعات التي توجه كل برنامج ومشروع وقرار نتخذه.</span></p>
  </div>
</div>

<div class="section-wrap">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:3rem;">
    <div style="background:var(--red-light);border-radius:20px;padding:2.5rem;border:1px solid rgba(192,57,43,.15);">
      <div style="margin-bottom:1rem;color:var(--red);"><svg class="icon" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></div>
      <div class="section-title" style="font-size:1.5rem;margin-bottom:.75rem;"><span class="en">Our Vision</span><span class="ar">رؤيتنا</span></div>
      <p style="font-size:15px;color:var(--muted);line-height:1.75;">
        <span class="en">A society where people with disabilities have equal rights and opportunities and take a full part in community life.</span>
        <span class="ar">مجتمع يتمتع فيه ذوو الإعاقة بحقوق وفرص متساوية ويشاركون مشاركة كاملة في حياة المجتمع.</span>
      </p>
    </div>
    <div style="background:var(--blue-light);border-radius:20px;padding:2.5rem;border:1px solid rgba(36,113,163,.15);">
      <div style="margin-bottom:1rem;color:var(--blue);"><svg class="icon" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><path d="M4 22v-7"/></svg></div>
      <div class="section-title" style="font-size:1.5rem;margin-bottom:.75rem;"><span class="en">Our Mission</span><span class="ar">مهمتنا</span></div>
      <p style="font-size:15px;color:var(--muted);line-height:1.75;">
        <span class="en">To support people with disabilities across {{ config('society.city.en') }} and {{ config('society.region.en') }} through rehabilitation, education, vocational training and social inclusion programs, delivered with respect and professionalism.</span>
        <span class="ar">دعم ذوي الإعاقة في {{ config('society.city.ar') }} و{{ config('society.region.ar') }} من خلال برامج التأهيل والتعليم والتدريب المهني والإدماج الاجتماعي، بمهنية واحترام.</span>
      </p>
    </div>
  </div>

  <div class="section-label"><span class="en">What We Strive For</span><span class="ar">ما نسعى إليه</span></div>
  <div class="section-title"><span class="en">Our Strategic Aims</span><span class="ar">أهدافنا الاستراتيجية</span></div>
  <div class="aims-grid">
    <div class="aim-card" style="border-top:4px solid var(--red);">
      <div class="aim-num" style="color:var(--red);">01</div>
      <div class="aim-title"><span class="en">Expand Access to Rehabilitation</span><span class="ar">توسيع الوصول إلى التأهيل</span></div>
      <div class="aim-desc"><span class="en">Make physiotherapy, occupational therapy and assistive devices available to every person with a physical disability in the {{ config('society.city.en') }} area, whatever their address or income.</span><span class="ar">توفير العلاج الطبيعي والوظيفي والأجهزة المساعدة لكل شخص ذي إعاقة جسدية في منطقة {{ config('society.city.ar') }}، بغض النظر عن مكان سكنه أو دخله.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--blue);">
      <div class="aim-num" style="color:var(--blue);">02</div>
      <div class="aim-title"><span class="en">Inclusive Education</span><span class="ar">التعليم الدامج</span></div>
      <div class="aim-desc"><span class="en">Give children with all types of disabilities the individual academic support they need to learn and grow into independent adults.</span><span class="ar">تقديم الدعم الأكاديمي الفردي للأطفال ذوي الإعاقات المختلفة ليتعلموا وينموا حتى يصبحوا بالغين مستقلين.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--green);">
      <div class="aim-num" style="color:var(--green);">03</div>
      <div class="aim-title"><span class="en">Drive Economic Independence</span><span class="ar">تعزيز الاستقلالية الاقتصادية</span></div>
      <div class="aim-desc"><span class="en">Create pathways to dignified employment and entrepreneurship for persons with disabilities through practical vocational training, microgrants, and business support that reflect actual market demand.</span><span class="ar">إيجاد مسارات للعمل الكريم وريادة الأعمال لذوي الإعاقة من خلال تدريب مهني عملي ومنح صغيرة ودعم أعمال يعكس الطلب الفعلي في السوق.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--purple);">
      <div class="aim-num" style="color:var(--purple);">04</div>
      <div class="aim-title"><span class="en">Break Down Social Barriers</span><span class="ar">هدم الحواجز الاجتماعية</span></div>
      <div class="aim-desc"><span class="en">Combat stigma and discrimination at the community level through sustained awareness campaigns, inclusive events, and direct advocacy for disability rights with local institutions and authorities.</span><span class="ar">مكافحة الوصمة والتمييز على المستوى المجتمعي من خلال حملات توعية مستمرة وفعاليات دامجة ومناصرة مباشرة لحقوق ذوي الإعاقة مع المؤسسات المحلية.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--gold);">
      <div class="aim-num" style="color:var(--gold);">05</div>
      <div class="aim-title"><span class="en">Wider Reach</span><span class="ar">توسيع نطاق الخدمة</span></div>
      <div class="aim-desc"><span class="en">Extend our services beyond {{ config('society.city.en') }} to communities across {{ config('society.region.en') }} through mobile clinics, home-based care and community outreach.</span><span class="ar">توسيع خدماتنا خارج {{ config('society.city.ar') }} إلى المجتمعات في أنحاء {{ config('society.region.ar') }} من خلال العيادات المتنقلة والرعاية المنزلية والتوعية المجتمعية.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--teal);">
      <div class="aim-num" style="color:var(--teal);">06</div>
      <div class="aim-title"><span class="en">Strengthen Families &amp; Caregivers</span><span class="ar">تقوية الأسر ومقدمي الرعاية</span></div>
      <div class="aim-desc"><span class="en">Support the families and caregivers of people with disabilities through counseling, practical training and peer support groups.</span><span class="ar">دعم أسر ذوي الإعاقة ومقدمي الرعاية لهم من خلال الإرشاد والتدريب العملي ومجموعات الدعم بين الأقران.</span></div>
    </div>
  </div>
</div>

<div style="background:var(--gray-50);padding:1px 0;">
  <div class="section-wrap">
    <div class="section-label"><span class="en">How We Work</span><span class="ar">كيف نعمل</span></div>
    <div class="section-title"><span class="en">Our Core Values</span><span class="ar">قيمنا الأساسية</span></div>
    <div class="values-grid">
      <div class="value-card"><div class="value-icon"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div><div class="value-title"><span class="en">Dignity</span><span class="ar">الكرامة</span></div><div class="value-desc"><span class="en">Every individual is treated with unconditional respect, regardless of their disability type or severity.</span><span class="ar">كل فرد يُعامَل باحترام غير مشروط.</span></div></div>
      <div class="value-card"><div class="value-icon"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/></svg></div><div class="value-title"><span class="en">Equity</span><span class="ar">العدالة</span></div><div class="value-desc"><span class="en">We allocate resources based on need, ensuring those with the greatest barriers receive the most support.</span><span class="ar">نخصص الموارد بناءً على الحاجة لضمان أكبر دعم لمن يواجهون أشد العقبات.</span></div></div>
      <div class="value-card"><div class="value-icon"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/></svg></div><div class="value-title"><span class="en">Empowerment</span><span class="ar">التمكين</span></div><div class="value-desc"><span class="en">We help individuals, families and communities build their own skills and independence.</span><span class="ar">نساعد الأفراد والأسر والمجتمعات على بناء مهاراتهم واستقلاليتهم.</span></div></div>
      <div class="value-card"><div class="value-icon"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="m9 14 2 2 4-4"/></svg></div><div class="value-title"><span class="en">Evidence-Based</span><span class="ar">قائم على الأدلة</span></div><div class="value-desc"><span class="en">Our programs follow internationally recognized clinical standards and are continuously evaluated.</span><span class="ar">برامجنا تتبع المعايير السريرية الدولية وتُقيَّم باستمرار.</span></div></div>
      <div class="value-card"><div class="value-icon"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="value-title"><span class="en">Partnership</span><span class="ar">الشراكة</span></div><div class="value-desc"><span class="en">We work with government bodies, other NGOs, the private sector and local communities.</span><span class="ar">نعمل مع الجهات الحكومية والمؤسسات الأهلية والقطاع الخاص والمجتمعات المحلية.</span></div></div>
      <div class="value-card"><div class="value-icon"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div><div class="value-title"><span class="en">Accountability</span><span class="ar">المساءلة</span></div><div class="value-desc"><span class="en">We are transparent with our donors, partners, and beneficiaries about what we do and how we spend.</span><span class="ar">نحن شفافون مع المانحين والشركاء والمستفيدين حول ما نفعله وكيف ننفق.</span></div></div>
    </div>
  </div>
</div>


@endsection
