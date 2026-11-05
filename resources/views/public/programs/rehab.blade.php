{{--
    BSD Public Site — Program: Medical Rehabilitation
    resources/views/public/programs/rehab.blade.php
    Phase 4: converted from resources/views/prog-rehab.html (static content
    preserved verbatim; only the layout wrapper and links were fixed).
--}}
@extends('layouts.public')

@section('title', 'Medical Rehabilitation')
@section('active_nav', 'rehab')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);padding:0 0 0 0;">
  <div class="program-hero" style="padding-top:3.5rem;padding-bottom:3.5rem;">
    <div class="program-hero-text">
      <div class="breadcrumb">
        <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
        <span>›</span>
        <span><span class="en">Programs</span><span class="ar">البرامج</span></span>
        <span>›</span>
        <span><span class="en">Medical Rehabilitation</span><span class="ar">التأهيل الطبي</span></span>
      </div>
      <div class="program-badge" style="background:#C0392B22;color:#C0392B;">
        <span class="en">Rehabilitation</span><span class="ar">تأهيل</span>
      </div>
      <div class="program-big-title" style="color:var(--red-dark);">
        <span class="en">Medical Rehabilitation</span><span class="ar">التأهيل الطبي</span>
      </div>
      <p class="program-lead">
        <span class="en">Our medical rehabilitation program provides comprehensive physiotherapy, occupational therapy, and assistive device services to individuals with physical disabilities across {{ config('society.city.en') }}, its neighborhoods and nearby refugee camps.</span><span class="ar">يقدّم برنامج التأهيل الطبي خدمات شاملة من علاج طبيعي ووظيفي وتوفير أجهزة مساعدة لذوي الإعاقات الجسدية في {{ config('society.city.ar') }} وأحيائها والمخيمات القريبة.</span>
      </p>
      @if (config('society.program_stats.rehab'))
      <div class="program-stats-row">
        @foreach (config('society.program_stats.rehab') as $stat)
        <div class="pstat"><div class="pstat-num" style="color:#C0392B;">{{ $stat['value'] }}</div><div class="pstat-label"><span class="en">{{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['label']['ar'] }}</span></div></div>
        @endforeach
      </div>
      @endif
    </div>
    <div class="program-image" style="background-image:url('{{ asset('images/site/program-rehab.jpg') }}');"></div>
  </div>
</div>
<div class="section-wrap">
  <div class="section-label"><span class="en">What We Offer</span><span class="ar">ما نقدمه</span></div>
  <div class="section-title"><span class="en">Program Services</span><span class="ar">خدمات البرنامج</span></div>
  <div class="services-list">
        <div class="service-item">
          <div class="service-dot" style="background:#C0392B;"></div>
          <div class="service-text">
            <strong><span class="en">Physiotherapy Sessions</span><span class="ar">جلسات العلاج الطبيعي</span></strong>
            <span><span class="en">Individual and group therapy to improve movement, strength, and function.</span><span class="ar">جلسات فردية وجماعية لتحسين الحركة والقوة والوظيفة.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#C0392B;"></div>
          <div class="service-text">
            <strong><span class="en">Occupational Therapy</span><span class="ar">العلاج الوظيفي</span></strong>
            <span><span class="en">Training individuals in daily life activities and functional independence.</span><span class="ar">تدريب الأفراد على أنشطة الحياة اليومية والاستقلالية الوظيفية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#C0392B;"></div>
          <div class="service-text">
            <strong><span class="en">Mobile Clinic Visits</span><span class="ar">زيارات العيادة المتنقلة</span></strong>
            <span><span class="en">Specialized teams travel to underserved neighborhoods and camps to deliver rehabilitation care at home.</span><span class="ar">فرق متخصصة تتنقل إلى الأحياء والمخيمات الأقل حظاً لتقديم الرعاية التأهيلية في المنزل.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#C0392B;"></div>
          <div class="service-text">
            <strong><span class="en">Post-Surgery Support</span><span class="ar">دعم ما بعد الجراحة</span></strong>
            <span><span class="en">Structured recovery programs following orthopedic and neurological surgeries.</span><span class="ar">برامج تعافٍ منظمة بعد جراحات العظام والجهاز العصبي.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#C0392B;"></div>
          <div class="service-text">
            <strong><span class="en">Pediatric Rehabilitation</span><span class="ar">تأهيل الأطفال</span></strong>
            <span><span class="en">Specialized sessions for children with cerebral palsy, developmental delays, and congenital disabilities.</span><span class="ar">جلسات متخصصة للأطفال المصابين بالشلل الدماغي والتأخر التطوري والإعاقات الخلقية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#C0392B;"></div>
          <div class="service-text">
            <strong><span class="en">Progress Monitoring</span><span class="ar">متابعة التقدم</span></strong>
            <span><span class="en">Regular assessments and reports shared with families and referral physicians.</span><span class="ar">تقييمات دورية وتقارير تُشارَك مع الأسر والأطباء المحيلين.</span></span>
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
          <h4><span class="en">Eligibility</span><span class="ar">الأهلية</span></h4>
          <p><span class="en">Open to all residents of the {{ config('society.city.en') }} area with documented physical disabilities. No age restriction.</span><span class="ar">مفتوح لجميع سكان منطقة {{ config('society.city.ar') }} ذوي الإعاقات الجسدية الموثقة. لا قيود على العمر.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Cost</span><span class="ar">التكلفة</span></h4>
          <p><span class="en">Sessions are fully subsidized or provided free of charge based on a family income assessment.</span><span class="ar">الجلسات مدعومة بالكامل أو مجانية بناءً على تقييم الدخل الأسري.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Location</span><span class="ar">الموقع</span></h4>
          <p><span class="en">Main center in {{ config('society.city.en') }} + mobile clinics visiting neighborhoods and camps on a rotating weekly schedule.</span><span class="ar">المركز الرئيسي في {{ config('society.city.ar') }} + عيادات متنقلة تزور الأحياء والمخيمات أسبوعياً.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">How to Apply</span><span class="ar">كيفية التقديم</span></h4>
          <p><span class="en">Visit our center or call us to schedule an initial assessment. Referrals from physicians also accepted.</span><span class="ar">تفضل بزيارة مركزنا أو اتصل بنا لتحديد موعد التقييم الأولي. تُقبل الإحالات من الأطباء.</span></p>
        </div></div>
  </div>
</div>


@endsection
