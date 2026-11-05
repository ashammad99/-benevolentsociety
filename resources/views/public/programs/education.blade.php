{{--
    BSD Public Site — Program: Special Education
    resources/views/public/programs/education.blade.php
    Phase 4: converted from resources/views/prog-education.html (static
    content preserved verbatim; only the layout wrapper and links fixed).
--}}
@extends('layouts.public')

@section('title', 'Special Education')
@section('active_nav', 'education')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);padding:0 0 0 0;">
  <div class="program-hero" style="padding-top:3.5rem;padding-bottom:3.5rem;">
    <div class="program-hero-text">
      <div class="breadcrumb">
        <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
        <span>›</span>
        <span><span class="en">Programs</span><span class="ar">البرامج</span></span>
        <span>›</span>
        <span><span class="en">Special Education</span><span class="ar">التعليم الخاص</span></span>
      </div>
      <div class="program-badge" style="background:#2471A322;color:#2471A3;">
        <span class="en">Education</span><span class="ar">تعليم</span>
      </div>
      <div class="program-big-title" style="color:var(--red-dark);">
        <span class="en">Special Education</span><span class="ar">التعليم الخاص</span>
      </div>
      <p class="program-lead">
        <span class="en">Our special education program offers adapted curricula and individual learning support for children and young people with intellectual, hearing and visual impairments.</span><span class="ar">يقدّم برنامج التعليم الخاص مناهج أكاديمية مُكيَّفة ودعمًا تعليميًا فرديًا للأطفال والشباب ذوي الإعاقات الذهنية والسمعية والبصرية.</span>
      </p>
      @if (config('society.program_stats.education'))
      <div class="program-stats-row">
        @foreach (config('society.program_stats.education') as $stat)
        <div class="pstat"><div class="pstat-num" style="color:#2471A3;">{{ $stat['value'] }}</div><div class="pstat-label"><span class="en">{{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['label']['ar'] }}</span></div></div>
        @endforeach
      </div>
      @endif
    </div>
    <div class="program-image" style="background-image:url('https://images.unsplash.com/photo-1509099836639-18ba1795216d?w=900&q=80');"></div>
  </div>
</div>
<div class="section-wrap">
  <div class="section-label"><span class="en">What We Offer</span><span class="ar">ما نقدمه</span></div>
  <div class="section-title"><span class="en">Program Services</span><span class="ar">خدمات البرنامج</span></div>
  <div class="services-list">
        <div class="service-item">
          <div class="service-dot" style="background:#2471A3;"></div>
          <div class="service-text">
            <strong><span class="en">Individualized Education Plans</span><span class="ar">خطط تعليمية فردية</span></strong>
            <span><span class="en">Each student receives a custom learning plan based on their abilities and goals.</span><span class="ar">يحصل كل طالب على خطة تعليمية مخصصة وفق قدراته وأهدافه.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#2471A3;"></div>
          <div class="service-text">
            <strong><span class="en">Sign Language Classes</span><span class="ar">فصول لغة الإشارة</span></strong>
            <span><span class="en">Full sign language instruction for deaf and hard-of-hearing students and their families.</span><span class="ar">تعليم كامل للغة الإشارة للطلاب الصم وضعاف السمع وأسرهم.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#2471A3;"></div>
          <div class="service-text">
            <strong><span class="en">Braille & Visual Aids</span><span class="ar">برايل والمساعدات البصرية</span></strong>
            <span><span class="en">Braille reading, tactile learning materials, and screen reader training for visually impaired students.</span><span class="ar">تعليم برايل ومواد تعلم لمسية وتدريب على قارئ الشاشة للطلاب ضعاف البصر.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#2471A3;"></div>
          <div class="service-text">
            <strong><span class="en">Inclusive Education Support</span><span class="ar">دعم التعليم الدامج</span></strong>
            <span><span class="en">Coaching for mainstream school teachers to integrate students with disabilities into regular classrooms.</span><span class="ar">تدريب معلمي المدارس العادية على دمج الطلاب ذوي الإعاقة في الفصول الدراسية.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#2471A3;"></div>
          <div class="service-text">
            <strong><span class="en">Vocational Readiness</span><span class="ar">التهيؤ المهني</span></strong>
            <span><span class="en">Transition programs preparing older students for vocational training or supported employment.</span><span class="ar">برامج انتقال تُعدّ الطلاب الأكبر سناً للتدريب المهني أو التوظيف المدعوم.</span></span>
          </div>
        </div>
        <div class="service-item">
          <div class="service-dot" style="background:#2471A3;"></div>
          <div class="service-text">
            <strong><span class="en">Parent Training</span><span class="ar">تدريب الوالدين</span></strong>
            <span><span class="en">Workshops equipping parents to support learning at home and advocate for their child's rights.</span><span class="ar">ورش عمل تُمكّن الوالدين من دعم التعلم في المنزل والدفاع عن حقوق أطفالهم.</span></span>
          </div>
        </div></div>
</div>
<div style="background:var(--gray-50);padding:1px 0;">
  <div class="section-wrap">
    <div class="section-label"><span class="en">Quick Facts</span><span class="ar">حقائق سريعة</span></div>
    <div class="section-title"><span class="en">Program Details</span><span class="ar">تفاصيل البرنامج</span></div>
    <div class="info-grid">
        <div class="info-card">
          <h4><span class="en">Age Range</span><span class="ar">الفئة العمرية</span></h4>
          <p><span class="en">Serves children and youth with various disabilities.</span><span class="ar">يخدم الأطفال والشباب ذوي الإعاقات المختلفة.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Curriculum</span><span class="ar">المنهج</span></h4>
          <p><span class="en">The national curriculum of {{ config('society.country.en') }}, adapted and supplemented with specialized therapeutic content.</span><span class="ar">المنهج الوطني في {{ config('society.country.ar') }} مُكيَّفاً مع محتوى علاجي متخصص.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Enrollment</span><span class="ar">التسجيل</span></h4>
          <p><span class="en">Contact us for an intake assessment. New registrations open every September and February.</span><span class="ar">تواصل معنا لتقييم القبول. يفتح التسجيل كل سبتمبر وفبراير.</span></p>
        </div>
        <div class="info-card">
          <h4><span class="en">Teacher Qualifications</span><span class="ar">مؤهلات المعلمين</span></h4>
          <p><span class="en">All teachers hold university degrees in special education or equivalent certified training.</span><span class="ar">جميع المعلمين حاصلون على شهادات جامعية في التربية الخاصة.</span></p>
        </div></div>
  </div>
</div>


@endsection
