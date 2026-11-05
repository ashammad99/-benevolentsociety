{{--
    BSD Public Site — Program: Vocational Training
    resources/views/public/programs/vocational.blade.php
    Phase 4: converted from resources/views/prog-vocational.html (static
    content preserved verbatim; only the layout wrapper and links fixed).
--}}
@extends('layouts.public')

@section('title', 'Vocational Training')
@section('active_nav', 'vocational')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);padding:0 0 0 0;">
  <div class="program-hero" style="padding-top:3.5rem;padding-bottom:3.5rem;">
    <div class="program-hero-text">
      <div class="breadcrumb">
        <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
        <span>›</span>
        <span><span class="en">Programs</span><span class="ar">البرامج</span></span>
        <span>›</span>
        <span><span class="en">Vocational Training</span><span class="ar">التدريب المهني</span></span>
      </div>
      <div class="program-badge" style="background:#27AE6022;color:#27AE60;">
        <span class="en">Livelihood</span><span class="ar">معيشة</span>
      </div>
      <div class="program-big-title" style="color:var(--red-dark);">
        <span class="en">Vocational Training</span><span class="ar">التدريب المهني</span>
      </div>
      <p class="program-lead">
        <span class="en">Our vocational training program teaches adults with disabilities practical skills that employers need, including tailoring, computer skills, crafts and small business management.</span><span class="ar">يعلّم برنامج التدريب المهني البالغين ذوي الإعاقة مهارات عملية يحتاجها سوق العمل، منها الخياطة ومهارات الحاسوب والحرف اليدوية وإدارة المشاريع الصغيرة.</span>
      </p>
      @if (config('society.program_stats.vocational'))
      <div class="program-stats-row">
        @foreach (config('society.program_stats.vocational') as $stat)
        <div class="pstat"><div class="pstat-num" style="color:#27AE60;">{{ $stat['value'] }}</div><div class="pstat-label"><span class="en">{{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['label']['ar'] }}</span></div></div>
        @endforeach
      </div>
      @endif
    </div>
    <div class="program-image" style="background-image:url('{{ asset('images/site/program-vocational.jpg') }}');"></div>
  </div>
</div>
<div class="section-wrap">
  <div class="section-label"><span class="en">What We Offer</span><span class="ar">ما نقدمه</span></div>
  <div class="section-title"><span class="en">Program Services</span><span class="ar">خدمات البرنامج</span></div>
  <div class="services-list">
        <div class="service-item">
          <div class="service-dot" style="background:#27AE60;"></div>
          <div class="service-text">
            <strong><span class="en">Tailoring & Textiles</span><span class="ar">الخياطة والمنسوجات</span></strong>
            <span><span class="en">Professional sewing, pattern-making, and garment production for individuals and small workshops.</span><span class="ar">خياطة احترافية ورسم أنماط وإنتاج ملابس للأفراد وورش العمل الصغيرة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#27AE60;"></div>
          <div class="service-text">
            <strong><span class="en">Computer & Digital Skills</span><span class="ar">مهارات الحاسوب والرقمنة</span></strong>
            <span><span class="en">MS Office, internet skills, data entry, graphic design basics, and social media for business.</span><span class="ar">مايكروسوفت أوفيس، مهارات الإنترنت، إدخال البيانات، أساسيات التصميم الجرافيكي.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#27AE60;"></div>
          <div class="service-text">
            <strong><span class="en">Handicrafts & Artisanship</span><span class="ar">الحرف اليدوية</span></strong>
            <span><span class="en">Embroidery, ceramics, woodwork, and traditional crafts of {{ config('society.country.en') }} for commercial sale.</span><span class="ar">تطريز وخزف ونجارة وحرف تراثية من {{ config('society.country.ar') }} للبيع التجاري.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#27AE60;"></div>
          <div class="service-text">
            <strong><span class="en">Small Business Management</span><span class="ar">إدارة المشاريع الصغيرة</span></strong>
            <span><span class="en">Basics of budgeting, marketing, and customer service for aspiring entrepreneurs.</span><span class="ar">أساسيات الميزانية والتسويق وخدمة العملاء لرواد الأعمال الطموحين.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#27AE60;"></div>
          <div class="service-text">
            <strong><span class="en">Microgrant Program</span><span class="ar">برنامج المنح الصغيرة</span></strong>
            <span><span class="en">Competitive grants for graduates ready to launch or expand their own business.</span><span class="ar">منح تنافسية للخريجين الجاهزين لإطلاق مشاريعهم أو توسيعها.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#27AE60;"></div>
          <div class="service-text">
            <strong><span class="en">Job Placement Support</span><span class="ar">دعم التوظيف</span></strong>
            <span><span class="en">Partnerships with local employers and cooperatives to place trained graduates.</span><span class="ar">شراكات مع أصحاب العمل المحليين والتعاونيات لتوظيف الخريجين.</span></span>
          </div>
        </div></div>
</div>
@if (isset($ads['inline']))
<div class="section-wrap" style="padding-top:0;padding-bottom:0">
  @include('public.partials.ad', ['ad' => $ads['inline'], 'variant' => 'card'])
</div>
@endif

<div style="background:var(--gray-50);padding:1px 0;">
  <div class="section-wrap">
    <div class="section-label"><span class="en">Quick Facts</span><span class="ar">حقائق سريعة</span></div>
    <div class="section-title"><span class="en">Program Details</span><span class="ar">تفاصيل البرنامج</span></div>
    <div class="info-grid">
        <div class="info-card">
          <h4><span class="en">Duration</span><span class="ar">المدة</span></h4>
          <p><span class="en">Course length depends on the track, with flexible morning and afternoon schedules.</span><span class="ar">تختلف مدة الدورات حسب المسار، بجداول صباحية ومسائية مرنة.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Who Can Apply</span><span class="ar">من يمكنه التقديم</span></h4>
          <p><span class="en">Adults with any disability type, with basic literacy. Exceptions considered case by case.</span><span class="ar">البالغون ذوو الإعاقة بمختلف أنواعها مع محو أمية أساسي.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Cost</span><span class="ar">التكلفة</span></h4>
          <p><span class="en">Free of charge. Materials, tools, and certificates provided at no cost to participants.</span><span class="ar">مجاني. المواد والأدوات والشهادات تُقدَّم دون أي تكلفة.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Certification</span><span class="ar">الشهادات</span></h4>
          <p><span class="en">Graduates receive a recognized vocational certificate endorsed by the national vocational training authority of {{ config('society.country.en') }}.</span><span class="ar">يحصل الخريجون على شهادة مهنية معترف بها من الجهة الوطنية للتدريب المهني في {{ config('society.country.ar') }}.</span></p>
        </div></div>
  </div>
</div>


@endsection
