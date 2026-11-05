{{--
    BSD Public Site — Program: Social Inclusion
    resources/views/public/programs/social.blade.php
    Phase 4: converted from resources/views/prog-social.html (static
    content preserved verbatim; only the layout wrapper and links fixed).
--}}
@extends('layouts.public')

@section('title', 'Social Inclusion')
@section('active_nav', 'social')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);padding:0 0 0 0;">
  <div class="program-hero" style="padding-top:3.5rem;padding-bottom:3.5rem;">
    <div class="program-hero-text">
      <div class="breadcrumb">
        <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
        <span>›</span>
        <span><span class="en">Programs</span><span class="ar">البرامج</span></span>
        <span>›</span>
        <span><span class="en">Social Inclusion</span><span class="ar">الإدماج الاجتماعي</span></span>
      </div>
      <div class="program-badge" style="background:#8E44AD22;color:#8E44AD;">
        <span class="en">Community</span><span class="ar">مجتمع</span>
      </div>
      <div class="program-big-title" style="color:var(--red-dark);">
        <span class="en">Social Inclusion</span><span class="ar">الإدماج الاجتماعي</span>
      </div>
      <p class="program-lead">
        <span class="en">Our social inclusion program dismantles the barriers that isolate persons with disabilities from their communities through awareness campaigns, family support, recreational activities, and rights advocacy.</span><span class="ar">يزيل برنامج الإدماج الاجتماعي الحواجز التي تعزل ذوي الإعاقة عن مجتمعاتهم من خلال حملات التوعية ودعم الأسرة والأنشطة الترفيهية والمناصرة.</span>
      </p>
      @if (config('society.program_stats.social'))
      <div class="program-stats-row">
        @foreach (config('society.program_stats.social') as $stat)
        <div class="pstat"><div class="pstat-num" style="color:#8E44AD;">{{ $stat['value'] }}</div><div class="pstat-label"><span class="en">{{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['label']['ar'] }}</span></div></div>
        @endforeach
      </div>
      @endif
    </div>
    <div class="program-image" style="background-image:url('{{ asset('images/site/program-social.jpg') }}');"></div>
  </div>
</div>
<div class="section-wrap">
  <div class="section-label"><span class="en">What We Offer</span><span class="ar">ما نقدمه</span></div>
  <div class="section-title"><span class="en">Program Services</span><span class="ar">خدمات البرنامج</span></div>
  <div class="services-list">
        <div class="service-item">
          <div class="service-dot" style="background:#8E44AD;"></div>
          <div class="service-text">
            <strong><span class="en">Community Awareness Campaigns</span><span class="ar">حملات التوعية المجتمعية</span></strong>
            <span><span class="en">Public events, school visits, and media campaigns to reduce stigma and promote disability rights.</span><span class="ar">فعاليات عامة وزيارات مدرسية وحملات إعلامية لتقليل الوصمة وتعزيز حقوق ذوي الإعاقة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#8E44AD;"></div>
          <div class="service-text">
            <strong><span class="en">Family Counseling & Support</span><span class="ar">إرشاد الأسرة ودعمها</span></strong>
            <span><span class="en">Individual and group counseling sessions to help families navigate the emotional and practical challenges of disability.</span><span class="ar">جلسات إرشاد فردية وجماعية لمساعدة الأسر على مواجهة تحديات الإعاقة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#8E44AD;"></div>
          <div class="service-text">
            <strong><span class="en">Recreational Activities</span><span class="ar">الأنشطة الترفيهية</span></strong>
            <span><span class="en">Sports, arts, and cultural events enabling community participation and friendship.</span><span class="ar">رياضة وفنون وفعاليات ثقافية تُتيح المشاركة المجتمعية وبناء الصداقات.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#8E44AD;"></div>
          <div class="service-text">
            <strong><span class="en">Rights & Legal Advocacy</span><span class="ar">المناصرة الحقوقية والقانونية</span></strong>
            <span><span class="en">Guidance on disability rights, access to public services, and legal protections under the law of {{ config('society.country.en') }}.</span><span class="ar">إرشاد حول حقوق ذوي الإعاقة والوصول إلى الخدمات العامة والحماية القانونية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#8E44AD;"></div>
          <div class="service-text">
            <strong><span class="en">Peer Support Groups</span><span class="ar">مجموعات الدعم بين الأقران</span></strong>
            <span><span class="en">Facilitated groups where individuals with disabilities share experiences and build mutual support networks.</span><span class="ar">مجموعات تيسيرية يتشارك فيها ذوو الإعاقة التجارب ويبنون شبكات دعم متبادل.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#8E44AD;"></div>
          <div class="service-text">
            <strong><span class="en">School Integration Program</span><span class="ar">برنامج الاندماج المدرسي</span></strong>
            <span><span class="en">Working with local schools to create welcoming, accessible environments for students with disabilities.</span><span class="ar">العمل مع المدارس المحلية لخلق بيئات ترحيبية وميسّرة للطلاب ذوي الإعاقة.</span></span>
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
          <h4><span class="en">Who Benefits</span><span class="ar">من يستفيد</span></h4>
          <p><span class="en">Persons with disabilities of all ages, their families, and the broader community.</span><span class="ar">ذوو الإعاقة من جميع الأعمار وأسرهم والمجتمع الأوسع.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Schedule</span><span class="ar">الجدول</span></h4>
          <p><span class="en">Events and sessions run year-round. Check our calendar for upcoming activities.</span><span class="ar">الفعاليات والجلسات تعمل على مدار السنة. تحقق من التقويم للأنشطة القادمة.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Collaboration</span><span class="ar">التعاون</span></h4>
          <p><span class="en">We partner with local schools, municipalities, NGOs, and businesses to maximize reach.</span><span class="ar">نتشارك مع المدارس والبلديات والمنظمات غير الحكومية والشركات لتعظيم الأثر.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Volunteering</span><span class="ar">التطوع</span></h4>
          <p><span class="en">Volunteers are central to our events. Contact us to join our active volunteer community.</span><span class="ar">المتطوعون محور فعالياتنا. تواصل معنا للانضمام إلى مجتمع متطوعينا.</span></p>
        </div></div>
  </div>
</div>


@endsection
