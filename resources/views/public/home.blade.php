{{--
    BSD Public Site — Home
    resources/views/public/home.blade.php

    Phase 4: converted from the old static resources/views/index.blade.php.
    Hero slider, stats bar, and program cards are preserved exactly as they
    were (no CMS model backs them). The "Latest News" section is new — it
    reuses the .projects-grid/.project-card CSS that already shipped in the
    stylesheet but was unused in the old markup, and is fed by
    HomeController@index ($featuredPosts, $latestPosts, $bannerAd).
--}}
@extends('layouts.public')

@section('title', 'Home')
@section('active_nav', 'home')

@section('content')

@if($bannerAd)
<div class="ad-banner">
  @if($bannerAd->image_url)<img src="{{ $bannerAd->image_url }}" alt="">@endif
  <div class="ad-text">
    <strong>{{ $bannerAd->title }}</strong>
    @if($bannerAd->body)<span> — {{ $bannerAd->body }}</span>@endif
  </div>
  @if($bannerAd->url)<a href="{{ $bannerAd->url }}" class="btn-white" style="padding:6px 16px;font-size:13px;" target="_blank" rel="noopener">
    <span class="en">Learn More</span><span class="ar">اعرف أكثر</span>
  </a>@endif
</div>
@endif

<div class="hero-slider" id="slider">
  <div class="slides-track" id="track">
    <div class="slide active" id="slide-0">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1576765608866-5b51046452be?w=1600&q=85');"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <span class="slide-badge"><span class="en">Rehabilitation</span><span class="ar">التأهيل</span></span>
        <div class="slide-title">Mobile Clinics Reaching<br>Every Neighborhood</div>
        <div class="slide-title-ar">العيادات المتنقلة تصل<br>إلى كل حي</div>
        <div class="slide-desc">Our rehabilitation teams travel to neighborhoods and refugee camps, bringing physiotherapy and care directly to those who need it most.</div>
        <div class="slide-desc-ar">فرق التأهيل تتنقل إلى الأحياء والمخيمات لتقديم الرعاية لمن يحتاجها.</div>
        <button class="slide-btn" onclick="location.href='{{ route('programs.show', 'rehab') }}'"><span class="en">Learn More →</span><span class="ar">اعرف أكثر ←</span></button>
      </div>
    </div>
    <div class="slide" id="slide-1">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1509099836639-18ba1795216d?w=1600&q=85');"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <span class="slide-badge"><span class="en">Special Education</span><span class="ar">التعليم الخاص</span></span>
        <div class="slide-title">Every Child Deserves<br>Quality Education</div>
        <div class="slide-title-ar">كل طفل يستحق<br>تعليماً جيداً</div>
        <div class="slide-desc">Tailored classrooms and trained educators ensure every child receives the education they deserve.</div>
        <div class="slide-desc-ar">فصول مُكيَّفة ومعلمون متخصصون لكل طفل.</div>
        <button class="slide-btn" onclick="location.href='{{ route('programs.show', 'education') }}'"><span class="en">Learn More →</span><span class="ar">اعرف أكثر ←</span></button>
      </div>
    </div>
    <div class="slide" id="slide-2">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1544027993-37dbfe43562a?w=1600&q=85');"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <span class="slide-badge"><span class="en">Vocational Training</span><span class="ar">التدريب المهني</span></span>
        <div class="slide-title">Skills That Build<br>Independent Lives</div>
        <div class="slide-title-ar">مهارات تبني<br>حياة مستقلة</div>
        <div class="slide-desc">Vocational programs open doors to economic independence for hundreds each year.</div>
        <div class="slide-desc-ar">برامج مهنية تفتح أبواب الاستقلالية الاقتصادية.</div>
        <button class="slide-btn" onclick="location.href='{{ route('programs.show', 'vocational') }}'"><span class="en">Learn More →</span><span class="ar">اعرف أكثر ←</span></button>
      </div>
    </div>
    <div class="slide" id="slide-3">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=1600&q=85');"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <span class="slide-badge"><span class="en">Community</span><span class="ar">المجتمع</span></span>
        <div class="slide-title">Inclusion Starts<br>in Our Community</div>
        <div class="slide-title-ar">الإدماج يبدأ<br>في مجتمعنا</div>
        <div class="slide-desc">Awareness campaigns and events break barriers and foster belonging for persons with disabilities.</div>
        <div class="slide-desc-ar">حملات التوعية تهدم الحواجز وتعزز الانتماء.</div>
        <button class="slide-btn" onclick="location.href='{{ route('programs.show', 'social') }}'"><span class="en">Learn More →</span><span class="ar">اعرف أكثر ←</span></button>
      </div>
    </div>
    <div class="slide" id="slide-4">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1593113630400-ea4288922559?w=1600&q=85');"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <span class="slide-badge"><span class="en">Assistive Devices</span><span class="ar">الأجهزة المساعدة</span></span>
        <div class="slide-title">Mobility & Dignity<br>for Every Person</div>
        <div class="slide-title-ar">الحركة والكرامة<br>لكل شخص</div>
        <div class="slide-desc">Assistive devices that restore independence and dignity across the region.</div>
        <div class="slide-desc-ar">أجهزة مساعدة تعيد الاستقلالية والكرامة.</div>
        <button class="slide-btn" onclick="location.href='{{ route('programs.show', 'devices') }}'"><span class="en">Learn More →</span><span class="ar">اعرف أكثر ←</span></button>
      </div>
    </div>
  </div>
  <button class="slider-arrow prev" onclick="moveSlide(-1)">&#8249;</button>
  <button class="slider-arrow next" onclick="moveSlide(1)">&#8250;</button>
  <div class="slider-dots" id="dots"></div>
  <div class="slide-counter" id="counter">1 / 5</div>
</div>
<div class="thumb-strip" id="thumbs"></div>
@if (config('society.stats'))
<div class="stats-bar">
  @foreach (config('society.stats') as $stat)
  <div class="stat"><div class="stat-num">{{ $stat['value'] }}</div><div class="stat-label"><span class="en">{{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['label']['ar'] }}</span></div></div>
  @endforeach
</div>
@endif

<div class="section-wrap" id="programs">
  <div class="section-label"><span class="en">What We Do</span><span class="ar">ما نفعله</span></div>
  <div class="section-title"><span class="en">Our Programs</span><span class="ar">برامجنا</span></div>
  <p class="section-sub"><span class="en">Six pillars of support, each targeting a critical need of the disability community in {{ config('society.city.en') }} and beyond.</span><span class="ar">ستة محاور دعم، كل منها يستهدف حاجة أساسية لمجتمع ذوي الإعاقة.</span></p>
  <div class="programs-grid">
    <div class="program-card" onclick="location.href='{{ route('programs.show', 'rehab') }}'">
      <div class="card-bar" style="background:var(--red);"></div>
      <div class="card-body">
        <div class="card-icon" style="background:var(--red-light);">
          <svg viewBox="0 0 24 24" stroke="var(--red)"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="card-title"><span class="en">Medical Rehabilitation</span><span class="ar">التأهيل الطبي</span></div>
        <div class="card-desc"><span class="en">Physiotherapy, occupational therapy, and assistive device provision for people with physical disabilities.</span><span class="ar">علاج طبيعي وعلاج وظيفي وتوفير أجهزة مساعدة.</span></div>
        @if ($stat = config('society.program_stats.rehab.0'))
        <span class="tag" style="background:var(--red-light);color:var(--red-dark);"><span class="en">{{ $stat['value'] }} {{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['value'] }} {{ $stat['label']['ar'] }}</span></span>
        @endif
        <div style="margin-top:.85rem;"><span class="card-link"><span class="en">View Program →</span><span class="ar">← عرض البرنامج</span></span></div>
      </div>
    </div>
    <div class="program-card" onclick="location.href='{{ route('programs.show', 'education') }}'">
      <div class="card-bar" style="background:var(--blue);"></div>
      <div class="card-body">
        <div class="card-icon" style="background:var(--blue-light);">
          <svg viewBox="0 0 24 24" stroke="var(--blue)"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div class="card-title"><span class="en">Special Education</span><span class="ar">التعليم الخاص</span></div>
        <div class="card-desc"><span class="en">Tailored curricula for children with intellectual, hearing, and visual impairments.</span><span class="ar">مناهج مُكيَّفة للأطفال ذوي الإعاقات المختلفة.</span></div>
        @if ($stat = config('society.program_stats.education.0'))
        <span class="tag" style="background:var(--blue-light);color:var(--blue-dark);"><span class="en">{{ $stat['value'] }} {{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['value'] }} {{ $stat['label']['ar'] }}</span></span>
        @endif
        <div style="margin-top:.85rem;"><span class="card-link" style="color:var(--blue);"><span class="en">View Program →</span><span class="ar">← عرض البرنامج</span></span></div>
      </div>
    </div>
    <div class="program-card" onclick="location.href='{{ route('programs.show', 'vocational') }}'">
      <div class="card-bar" style="background:var(--green);"></div>
      <div class="card-body">
        <div class="card-icon" style="background:var(--green-light);">
          <svg viewBox="0 0 24 24" stroke="var(--green)"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div class="card-title"><span class="en">Vocational Training</span><span class="ar">التدريب المهني</span></div>
        <div class="card-desc"><span class="en">Skills-based training in tailoring, computing, and crafts for economic independence.</span><span class="ar">تدريب مهاري للاستقلالية الاقتصادية.</span></div>
        @if ($stat = config('society.program_stats.vocational.0'))
        <span class="tag" style="background:var(--green-light);color:var(--green-dark);"><span class="en">{{ $stat['value'] }} {{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['value'] }} {{ $stat['label']['ar'] }}</span></span>
        @endif
        <div style="margin-top:.85rem;"><span class="card-link" style="color:var(--green);"><span class="en">View Program →</span><span class="ar">← عرض البرنامج</span></span></div>
      </div>
    </div>
    <div class="program-card" onclick="location.href='{{ route('programs.show', 'social') }}'">
      <div class="card-bar" style="background:var(--purple);"></div>
      <div class="card-body">
        <div class="card-icon" style="background:var(--purple-light);">
          <svg viewBox="0 0 24 24" stroke="var(--purple)"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="card-title"><span class="en">Social Inclusion</span><span class="ar">الإدماج الاجتماعي</span></div>
        <div class="card-desc"><span class="en">Community integration, family counseling, and awareness campaigns to reduce stigma.</span><span class="ar">أنشطة اندماج مجتمعي وحملات توعية.</span></div>
        @if ($stat = config('society.program_stats.social.0'))
        <span class="tag" style="background:var(--purple-light);color:var(--purple-dark);"><span class="en">{{ $stat['value'] }} {{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['value'] }} {{ $stat['label']['ar'] }}</span></span>
        @endif
        <div style="margin-top:.85rem;"><span class="card-link" style="color:var(--purple);"><span class="en">View Program →</span><span class="ar">← عرض البرنامج</span></span></div>
      </div>
    </div>
    <div class="program-card" onclick="location.href='{{ route('programs.show', 'homecare') }}'">
      <div class="card-bar" style="background:var(--gold);"></div>
      <div class="card-body">
        <div class="card-icon" style="background:var(--gold-light);">
          <svg viewBox="0 0 24 24" stroke="var(--gold)"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </div>
        <div class="card-title"><span class="en">Home-Based Care</span><span class="ar">الرعاية المنزلية</span></div>
        <div class="card-desc"><span class="en">Visiting care teams for those unable to travel to our centers.</span><span class="ar">فرق رعاية متنقلة لمن لا يستطيع الوصول إلى مراكزنا.</span></div>
        @if ($stat = config('society.program_stats.homecare.0'))
        <span class="tag" style="background:var(--gold-light);color:var(--gold-dark);"><span class="en">{{ $stat['value'] }} {{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['value'] }} {{ $stat['label']['ar'] }}</span></span>
        @endif
        <div style="margin-top:.85rem;"><span class="card-link" style="color:var(--gold);"><span class="en">View Program →</span><span class="ar">← عرض البرنامج</span></span></div>
      </div>
    </div>
    <div class="program-card" onclick="location.href='{{ route('programs.show', 'devices') }}'">
      <div class="card-bar" style="background:var(--teal);"></div>
      <div class="card-body">
        <div class="card-icon" style="background:var(--teal-light);">
          <svg viewBox="0 0 24 24" stroke="var(--teal)"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <div class="card-title"><span class="en">Assistive Devices</span><span class="ar">الأجهزة المساعدة</span></div>
        <div class="card-desc"><span class="en">Subsidized wheelchairs, hearing aids, and prosthetics for eligible beneficiaries.</span><span class="ar">أجهزة مساعدة مدعومة للمستفيدين المؤهلين.</span></div>
        @if ($stat = config('society.program_stats.devices.0'))
        <span class="tag" style="background:var(--teal-light);color:var(--teal-dark);"><span class="en">{{ $stat['value'] }} {{ $stat['label']['en'] }}</span><span class="ar">{{ $stat['value'] }} {{ $stat['label']['ar'] }}</span></span>
        @endif
        <div style="margin-top:.85rem;"><span class="card-link" style="color:var(--teal);"><span class="en">View Program →</span><span class="ar">← عرض البرنامج</span></span></div>
      </div>
    </div>
  </div>
</div>

@if($latestPosts->isNotEmpty())
<div class="projects-bg">
  <div class="projects-inner">
    <div class="section-label"><span class="en">Stay Updated</span><span class="ar">ابق على اطلاع</span></div>
    <div class="section-title"><span class="en">Latest News</span><span class="ar">آخر الأخبار</span></div>
    <p class="section-sub"><span class="en">Updates from our programs, projects, and community.</span><span class="ar">آخر مستجدات برامجنا ومشاريعنا ومجتمعنا.</span></p>
    <div class="projects-grid">
      @foreach($latestPosts as $post)
      <a class="project-card" href="{{ route('news.show', $post) }}">
        <div class="project-header">
          <div class="project-year">{{ $post->category->name_en ?? 'News' }}</div>
          <div class="project-year">{{ optional($post->published_at)->format('M j, Y') }}</div>
        </div>
        <div class="project-title">{{ $post->title_en }}</div>
        <div class="project-desc">{{ Str::limit($post->excerpt_en ?: strip_tags($post->content_en), 120) }}</div>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endif

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

@push('scripts')
<script>
var TOTAL=5,current=0,autoTimer;
var thumbBgs=['https://images.unsplash.com/photo-1576765608866-5b51046452be?w=300&q=70','https://images.unsplash.com/photo-1509099836639-18ba1795216d?w=300&q=70','https://images.unsplash.com/photo-1544027993-37dbfe43562a?w=300&q=70','https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=300&q=70','https://images.unsplash.com/photo-1593113630400-ea4288922559?w=300&q=70'];
var track=document.getElementById('track'),dotsEl=document.getElementById('dots'),thumbsEl=document.getElementById('thumbs'),counterEl=document.getElementById('counter');
for(var i=0;i<TOTAL;i++){
  var d=document.createElement('div');d.className='dot'+(i===0?' active':'');d.onclick=(function(n){return function(){goTo(n)}})(i);dotsEl.appendChild(d);
  var t=document.createElement('div');t.className='thumb'+(i===0?' active':'');t.style.backgroundImage="url('"+thumbBgs[i]+"')";t.onclick=(function(n){return function(){goTo(n)}})(i);thumbsEl.appendChild(t);
}
function goTo(n){
  document.getElementById('slide-'+current).classList.remove('active');
  dotsEl.children[current].classList.remove('active');
  thumbsEl.children[current].classList.remove('active');
  current=(n+TOTAL)%TOTAL;
  document.getElementById('slide-'+current).classList.add('active');
  dotsEl.children[current].classList.add('active');
  thumbsEl.children[current].classList.add('active');
  track.style.transform='translateX(-'+current*100+'%)';
  counterEl.textContent=(current+1)+' / '+TOTAL;
  clearInterval(autoTimer);autoTimer=setInterval(function(){goTo(current+1)},5500);
}
function moveSlide(d){goTo(current+d)}
autoTimer=setInterval(function(){goTo(current+1)},5500);
var sx=0;
document.getElementById('slider').addEventListener('touchstart',function(e){sx=e.touches[0].clientX},{passive:true});
document.getElementById('slider').addEventListener('touchend',function(e){var dx=e.changedTouches[0].clientX-sx;if(Math.abs(dx)>40)moveSlide(dx<0?1:-1)});
</script>
@endpush
