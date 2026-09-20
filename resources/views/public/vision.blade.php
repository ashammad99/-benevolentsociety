{{--
    BSD Public Site — Vision & Mission
    resources/views/public/vision.blade.php

    Phase 4: converted from the old static resources/views/vision.html.
    Content is bespoke marketing copy (vision/mission cards, strategic aims,
    core values) with no CMS model behind it, so it stays static — only the
    layout wrapper and navigation links were fixed to use real routes.
--}}
@extends('layouts.public')

@section('title', 'Vision & Mission | جمعية المعاقين الخيرية')
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
      <div style="font-size:2rem;margin-bottom:1rem;">🎯</div>
      <div class="section-title" style="font-size:1.5rem;margin-bottom:.75rem;"><span class="en">Our Vision</span><span class="ar">رؤيتنا</span></div>
      <p style="font-size:15px;color:var(--muted);line-height:1.75;">
        <span class="en">A society where every person with a disability lives with full dignity, equal rights, and genuine opportunity — contributing to and participating fully in community life without barriers.</span>
        <span class="ar">مجتمع يعيش فيه كل شخص ذي إعاقة بكرامة كاملة وحقوق متساوية وفرص حقيقية — يُسهم ويشارك بشكل كامل في الحياة المجتمعية دون حواجز.</span>
      </p>
    </div>
    <div style="background:var(--blue-light);border-radius:20px;padding:2.5rem;border:1px solid rgba(36,113,163,.15);">
      <div style="font-size:2rem;margin-bottom:1rem;">🤝</div>
      <div class="section-title" style="font-size:1.5rem;margin-bottom:.75rem;"><span class="en">Our Mission</span><span class="ar">مهمتنا</span></div>
      <p style="font-size:15px;color:var(--muted);line-height:1.75;">
        <span class="en">To empower persons with disabilities across Nablus and the West Bank through high-quality rehabilitation, education, vocational training, and social inclusion programs — delivered with compassion, professionalism, and respect for human dignity.</span>
        <span class="ar">تمكين ذوي الإعاقة في نابلس والضفة الغربية من خلال برامج تأهيل وتعليم وتدريب مهني وإدماج اجتماعي عالية الجودة — تُقدَّم بتعاطف ومهنية واحترام للكرامة الإنسانية.</span>
      </p>
    </div>
  </div>

  <div class="section-label"><span class="en">What We Strive For</span><span class="ar">ما نسعى إليه</span></div>
  <div class="section-title"><span class="en">Our Strategic Aims</span><span class="ar">أهدافنا الاستراتيجية</span></div>
  <div class="aims-grid">
    <div class="aim-card" style="border-top:4px solid var(--red);">
      <div class="aim-num" style="color:var(--red);">01</div>
      <div class="aim-title"><span class="en">Expand Access to Rehabilitation</span><span class="ar">توسيع الوصول إلى التأهيل</span></div>
      <div class="aim-desc"><span class="en">Ensure every person with a physical disability in the Nablus region can access quality physiotherapy, occupational therapy, and assistive devices — regardless of where they live or their financial situation.</span><span class="ar">ضمان وصول كل شخص ذي إعاقة جسدية في منطقة نابلس إلى علاج طبيعي ووظيفي وأجهزة مساعدة عالية الجودة — بصرف النظر عن مكان إقامته أو وضعه المالي.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--blue);">
      <div class="aim-num" style="color:var(--blue);">02</div>
      <div class="aim-title"><span class="en">Champion Inclusive Education</span><span class="ar">تعزيز التعليم الدامج</span></div>
      <div class="aim-desc"><span class="en">Build a local education ecosystem where children with all types of disabilities receive the individualized academic support they need to develop, grow, and eventually transition into productive adult lives.</span><span class="ar">بناء منظومة تعليمية محلية يحصل فيها الأطفال ذوو الإعاقات المختلفة على الدعم الأكاديمي الفردي لينمووا ويتطوروا وينتقلوا إلى حياة بالغة منتجة.</span></div>
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
      <div class="aim-title"><span class="en">Reach the Unreachable</span><span class="ar">الوصول إلى من لا يُوصل إليهم</span></div>
      <div class="aim-desc"><span class="en">Extend our services beyond the city to remote villages through mobile rehabilitation clinics, home-based care, and community outreach — ensuring geography is never a barrier to support.</span><span class="ar">توسيع خدماتنا خارج المدينة إلى القرى النائية من خلال عيادات تأهيل متنقلة ورعاية منزلية وتوعية مجتمعية — لضمان أن الجغرافيا لا تكون أبداً حاجزاً أمام الدعم.</span></div>
    </div>
    <div class="aim-card" style="border-top:4px solid var(--teal);">
      <div class="aim-num" style="color:var(--teal);">06</div>
      <div class="aim-title"><span class="en">Strengthen Families &amp; Caregivers</span><span class="ar">تقوية الأسر ومقدمي الرعاية</span></div>
      <div class="aim-desc"><span class="en">Invest in the families and caregivers of persons with disabilities through counseling, practical training, and peer support — recognizing that strong families are the foundation of lasting progress.</span><span class="ar">الاستثمار في أسر ومقدمي رعاية ذوي الإعاقة من خلال الإرشاد والتدريب العملي والدعم بين الأقران — إدراكاً بأن الأسر القوية هي أساس التقدم الدائم.</span></div>
    </div>
  </div>
</div>

<div style="background:var(--gray-50);padding:1px 0;">
  <div class="section-wrap">
    <div class="section-label"><span class="en">How We Work</span><span class="ar">كيف نعمل</span></div>
    <div class="section-title"><span class="en">Our Core Values</span><span class="ar">قيمنا الأساسية</span></div>
    <div class="values-grid">
      <div class="value-card"><div class="value-icon">🤲</div><div class="value-title"><span class="en">Dignity</span><span class="ar">الكرامة</span></div><div class="value-desc"><span class="en">Every individual is treated with unconditional respect, regardless of their disability type or severity.</span><span class="ar">كل فرد يُعامَل باحترام غير مشروط.</span></div></div>
      <div class="value-card"><div class="value-icon">⚖️</div><div class="value-title"><span class="en">Equity</span><span class="ar">العدالة</span></div><div class="value-desc"><span class="en">We allocate resources based on need, ensuring those with the greatest barriers receive the most support.</span><span class="ar">نخصص الموارد بناءً على الحاجة لضمان أكبر دعم لمن يواجهون أشد العقبات.</span></div></div>
      <div class="value-card"><div class="value-icon">🌱</div><div class="value-title"><span class="en">Empowerment</span><span class="ar">التمكين</span></div><div class="value-desc"><span class="en">We build capacity in individuals, families, and communities — not dependency on our organization.</span><span class="ar">نبني القدرات في الأفراد والأسر والمجتمعات — لا الاعتماد على مؤسستنا.</span></div></div>
      <div class="value-card"><div class="value-icon">🔬</div><div class="value-title"><span class="en">Evidence-Based</span><span class="ar">قائم على الأدلة</span></div><div class="value-desc"><span class="en">Our programs follow internationally recognized clinical standards and are continuously evaluated.</span><span class="ar">برامجنا تتبع المعايير السريرية الدولية وتُقيَّم باستمرار.</span></div></div>
      <div class="value-card"><div class="value-icon">🤝</div><div class="value-title"><span class="en">Partnership</span><span class="ar">الشراكة</span></div><div class="value-desc"><span class="en">We collaborate with government, NGOs, private sector, and communities to multiply our impact.</span><span class="ar">نتعاون مع الحكومة والمنظمات غير الحكومية والقطاع الخاص والمجتمعات لتضخيم أثرنا.</span></div></div>
      <div class="value-card"><div class="value-icon">📣</div><div class="value-title"><span class="en">Accountability</span><span class="ar">المساءلة</span></div><div class="value-desc"><span class="en">We are transparent with our donors, partners, and beneficiaries about what we do and how we spend.</span><span class="ar">نحن شفافون مع المانحين والشركاء والمستفيدين حول ما نفعله وكيف ننفق.</span></div></div>
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
