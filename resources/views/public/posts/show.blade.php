{{--
    BSD Public Site — Post detail
    resources/views/public/posts/show.blade.php

    Phase 4: new page fed by PostController@show (published-only, 404
    otherwise). Reuses .page-hero/.section-wrap and the existing bilingual
    .en/.ar toggle rather than inventing new patterns.
--}}
@extends('layouts.public')

@section('title', $post->title ?: 'News')
@section('meta_description', Str::limit(strip_tags($post->excerpt ?: $post->content), 160))

@push('styles')
.post-body{font-size:16px;line-height:1.85;color:var(--text)}
.post-body p{margin-bottom:1rem}
.post-meta-row{display:flex;gap:1.5rem;flex-wrap:wrap;color:var(--muted);font-size:13px;margin-bottom:2rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border)}
.post-cover{border-radius:16px;overflow:hidden;margin-bottom:2rem;max-height:420px}
.post-cover img{width:100%;height:100%;object-fit:cover;display:block}
.related-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem;margin-top:1.5rem}
@endpush

@section('content')

<div class="page-hero" style="padding-bottom:2rem;text-align:start">
  <div class="breadcrumb" style="justify-content:flex-start">
    <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
    <span>›</span>
    <a href="{{ route('news.index') }}"><span class="en">News</span><span class="ar">الأخبار</span></a>
    @if($post->category)
    <span>›</span>
    <a href="{{ route('news.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>
    @endif
  </div>
  <div class="page-title" style="font-size:2rem;text-align:start">
    <span class="en">{{ $post->title_en }}</span><span class="ar">{{ $post->title_ar }}</span>
  </div>
</div>

<div class="section-wrap" style="max-width:820px">
  <div class="post-meta-row">
    @if($post->category)<span>{{ $post->category->name }}</span>@endif
    <span>{{ optional($post->published_at)->translatedFormat('j F Y') }}</span>
    <span>{{ $post->user->name ?? '' }}</span>
    <span>{{ number_format($post->views_count) }} <span class="en">views</span><span class="ar">مشاهدة</span></span>
  </div>

  @if($post->image_path)
  <div class="post-cover">
    <img src="{{ $post->image_url }}" alt="">
  </div>
  @endif

  <div class="post-body">
    <div class="en">{!! $post->content_en !!}</div>
    <div class="ar">{!! $post->content_ar !!}</div>
  </div>
</div>

@if($relatedPosts->isNotEmpty())
<div class="projects-bg">
  <div class="projects-inner">
    <div class="section-label"><span class="en">Read Next</span><span class="ar">اقرأ أيضاً</span></div>
    <div class="section-title"><span class="en">Related News</span><span class="ar">أخبار ذات صلة</span></div>
    <div class="related-grid">
      @foreach($relatedPosts as $related)
      <a class="project-card" href="{{ route('news.show', $related) }}">
        <div class="project-header">
          <div class="project-year">{{ optional($related->published_at)->translatedFormat('j M Y') }}</div>
        </div>
        <div class="project-title">{{ $related->title }}</div>
        <div class="project-desc">{{ Str::limit($related->excerpt ?: strip_tags($related->content), 100) }}</div>
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
