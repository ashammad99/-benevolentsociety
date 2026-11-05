{{--
    BSD Public Site — News listing
    resources/views/public/posts/index.blade.php

    Phase 4: new page — there was no static equivalent, but the admin CMS
    manages Posts/Categories with nothing on the public side to display
    them. Built from PostController@index ($posts paginated + published
    only, $categories, $category if filtering). Reuses .page-hero/
    .section-wrap/.projects-grid/.project-card already in the shared
    stylesheet rather than inventing a new visual language.
--}}
@extends('layouts.public')

@section('title', 'News')
@section('active_nav', 'news')

@push('styles')
.category-pills{display:flex;gap:8px;flex-wrap:wrap;margin-top:1.5rem}
.category-pill{padding:6px 16px;border-radius:20px;border:1.5px solid var(--border);font-size:13px;font-weight:600;color:var(--muted);transition:all .2s}
.category-pill:hover,.category-pill.active{background:var(--red);border-color:var(--red);color:white}
.post-empty{text-align:center;padding:4rem 1rem;color:var(--muted)}
.pagination-wrap{margin-top:2.5rem;display:flex;justify-content:center}
@endpush

@section('content')

<div class="page-hero">
  <div class="breadcrumb">
    <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
    <span>›</span>
    <span><span class="en">News</span><span class="ar">الأخبار</span></span>
  </div>
  <div class="page-title"><span class="en">News &amp; Updates</span><span class="ar">الأخبار والمستجدات</span></div>
  <p class="page-subtitle"><span class="en">The latest stories from our programs, projects, and community.</span><span class="ar">آخر الأخبار من برامجنا ومشاريعنا ومجتمعنا.</span></p>

  @if($categories->isNotEmpty())
  <div class="category-pills">
    <a class="category-pill {{ !$category ? 'active' : '' }}" href="{{ route('news.index') }}">
      <span class="en">All</span><span class="ar">الكل</span>
    </a>
    @foreach($categories as $cat)
      @if($cat->posts_count > 0 || ($category && $category->id === $cat->id))
      <a class="category-pill {{ $category && $category->id === $cat->id ? 'active' : '' }}" href="{{ route('news.index', ['category' => $cat->slug]) }}">
        {{ $cat->name }} ({{ $cat->posts_count }})
      </a>
      @endif
    @endforeach
  </div>
  @endif
</div>

<div class="section-wrap">
  @include('public.partials.ad', ['ad' => $ads['sidebar'] ?? null, 'variant' => 'card'])
  @if($posts->isEmpty())
    <div class="post-empty">
      <span class="en">No news has been posted yet.</span><span class="ar">لا توجد أخبار حتى الآن.</span>
    </div>
  @else
    <div class="projects-grid">
      @foreach($posts as $post)
      <a class="project-card" href="{{ route('news.show', $post) }}">
        @if($post->image_path)
          <div style="height:160px;border-radius:12px;overflow:hidden;margin:-1.6rem -1.6rem 1rem;">
            <img src="{{ $post->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
          </div>
        @endif
        <div class="project-header">
          <div class="project-year">{{ $post->category->name ?? __('News') }}</div>
          <div class="project-year">{{ optional($post->published_at)->translatedFormat('j M Y') }}</div>
        </div>
        <div class="project-title"><span class="en">{{ $post->title_en }}</span><span class="ar">{{ $post->title_ar }}</span></div>
        <div class="project-desc">
          <span class="en">{{ Str::limit($post->excerpt_en ?: strip_tags($post->content_en), 130) }}</span>
          <span class="ar">{{ Str::limit($post->excerpt_ar ?: strip_tags($post->content_ar), 130) }}</span>
        </div>
      </a>
      @endforeach
    </div>

    <div class="pagination-wrap">@include('public.partials.pagination', ['paginator' => $posts])</div>
  @endif
</div>


@endsection
