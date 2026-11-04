{{--
    BSD Public Site — Program: Home-Based Care
    resources/views/public/programs/homecare.blade.php
    Phase 4: converted from resources/views/prog-homecare.html (static
    content preserved verbatim; only the layout wrapper and links fixed).
--}}
@extends('layouts.public')

@section('title', 'Home-Based Care')
@section('active_nav', 'homecare')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);padding:0 0 0 0;">
  <div class="program-hero" style="padding-top:3.5rem;padding-bottom:3.5rem;">
    <div class="program-hero-text">
      <div class="breadcrumb">
        <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
        <span>›</span>
        <span><span class="en">Programs</span><span class="ar">البرامج</span></span>
        <span>›</span>
        <span><span class="en">Home-Based Care</span><span class="ar">الرعاية المنزلية</span></span>
      </div>
      <div class="program-badge" style="background:#D4AC0D22;color:#D4AC0D;">
        <span class="en">Care</span><span class="ar">رعاية</span>
      </div>
      <div class="program-big-title" style="color:var(--red-dark);">
        <span class="en">Home-Based Care</span><span class="ar">الرعاية المنزلية</span>
      </div>
      <p class="program-lead">
        <span class="en">For individuals with severe or complex disabilities who cannot travel to our centers, our home-based care program delivers professional rehabilitation, nursing support, and family training directly at home.</span><span class="ar">لذوي الإعاقات الشديدة أو المعقدة الذين لا يستطيعون التنقل، يُقدّم برنامجنا التأهيل المهني والدعم التمريضي والتدريب الأسري مباشرةً في المنزل.</span>
      </p>
      @if (config('society.program_stats.homecare'))
      <div class="program-stats-row">
        @foreach (config('society.program_stats.homecare') as $stat)
        <div class="pstat"><div class="pstat-num" style="color:#D4AC0D;">{{ $stat['value'] }}</div><div class="pstat-label"><span class="en">{{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['label']['ar'] }}</span></div></div>
        @endforeach
      </div>
      @endif
    </div>
    <div class="program-image" style="background-image:url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=900&q=80');"></div>
  </div>
</div>
<div class="section-wrap">
  <div class="section-label"><span class="en">What We Offer</span><span class="ar">ما نقدمه</span></div>
  <div class="section-title"><span class="en">Program Services</span><span class="ar">خدمات البرنامج</span></div>
  <div class="services-list">
        <div class="service-item">
          <div class="service-dot" style="background:#D4AC0D;"></div>
          <div class="service-text">
            <strong><span class="en">In-Home Physiotherapy</span><span class="ar">علاج طبيعي منزلي</span></strong>
            <span><span class="en">Licensed physiotherapists visit to provide sessions tailored to the home environment.</span><span class="ar">يزور معالجون طبيعيون مرخصون المنزل لتقديم جلسات مُكيَّفة للبيئة المنزلية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#D4AC0D;"></div>
          <div class="service-text">
            <strong><span class="en">Nursing Care</span><span class="ar">الرعاية التمريضية</span></strong>
            <span><span class="en">Medical monitoring, wound care, catheter management, and medication support for complex cases.</span><span class="ar">مراقبة طبية وعناية بالجروح وإدارة القسطرة ودعم الدواء للحالات المعقدة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#D4AC0D;"></div>
          <div class="service-text">
            <strong><span class="en">Family Training</span><span class="ar">تدريب الأسرة</span></strong>
            <span><span class="en">Teaching caregivers positioning, transfers, hygiene, and emergency procedures.</span><span class="ar">تعليم مقدمي الرعاية تقنيات التمركز والنقل والنظافة وإجراءات الطوارئ.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#D4AC0D;"></div>
          <div class="service-text">
            <strong><span class="en">Adaptive Equipment Supply</span><span class="ar">توفير المعدات التكيفية</span></strong>
            <span><span class="en">Provision and fitting of home-use adaptive equipment such as hospital beds, commodes, and positioning aids.</span><span class="ar">توفير وتركيب معدات تكيفية للاستخدام المنزلي كالأسرة الطبية والأجهزة المساعدة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#D4AC0D;"></div>
          <div class="service-text">
            <strong><span class="en">Psychological Support</span><span class="ar">الدعم النفسي</span></strong>
            <span><span class="en">Regular visits by social workers to support both the individual and their caregiving family.</span><span class="ar">زيارات منتظمة من الأخصائيين الاجتماعيين لدعم الفرد وأسرته المقدِّمة للرعاية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#D4AC0D;"></div>
          <div class="service-text">
            <strong><span class="en">Case Management</span><span class="ar">إدارة الحالة</span></strong>
            <span><span class="en">A dedicated case manager coordinates all services, tracks progress, and links families to additional resources.</span><span class="ar">مدير حالة مخصص ينسق جميع الخدمات ويتابع التقدم ويربط الأسر بموارد إضافية.</span></span>
          </div>
        </div></div>
</div>
<div style="background:var(--gray-50);padding:1px 0;">
  <div class="section-wrap">
    <div class="section-label"><span class="en">Quick Facts</span><span class="ar">حقائق سريعة</span></div>
    <div class="section-title"><span class="en">Program Details</span><span class="ar">تفاصيل البرنامج</span></div>
    <div class="info-grid">
        <div class="info-card">
          <h4><span class="en">Eligibility</span><span class="ar">الأهلية</span></h4>
          <p><span class="en">Individuals with severe mobility limitations or complex medical conditions who cannot safely travel.</span><span class="ar">الأفراد ذوو الإعاقات الحركية الشديدة أو الحالات الطبية المعقدة التي تمنع التنقل الآمن.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Geographic Coverage</span><span class="ar">التغطية الجغرافية</span></h4>
          <p><span class="en">{{ config('society.city.en') }} and its neighborhoods, including nearby refugee camps. Expanding coverage is in progress.</span><span class="ar">{{ config('society.city.ar') }} وأحياؤها، بما فيها المخيمات القريبة. التوسع جارٍ.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Referral</span><span class="ar">الإحالة</span></h4>
          <p><span class="en">Referrals accepted from hospitals, physicians, community health workers, or self-referral.</span><span class="ar">تُقبل الإحالات من المستشفيات والأطباء والعاملين في صحة المجتمع أو الإحالة الذاتية.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Cost</span><span class="ar">التكلفة</span></h4>
          <p><span class="en">Home visits are provided free of charge or with a small subsidized contribution depending on income assessment.</span><span class="ar">الزيارات المنزلية مجانية أو بمساهمة مدعومة صغيرة حسب تقييم الدخل.</span></p>
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
