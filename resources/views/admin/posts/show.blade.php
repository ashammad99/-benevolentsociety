{{--
    BSD Admin — Post Details
    resources/views/admin/posts/show.blade.php

    Phase 3 (Posts CRUD pilot): renders the real $post passed by
    PostController@show (eager-loaded with category, user, media).
--}}
@extends('admin.layouts.app')

@section('title', 'Post Details')
@section('active_nav', 'posts')
@section('breadcrumb')<span class="en">Post Details</span><span class="ar">تفاصيل المنشور</span>@endsection

@section('content')

    @php $canManage = auth()->user()->isEditor(); @endphp

    <div class="page-header">
      <div><h1 class="page-title"><span class="en">Post Details</span><span class="ar">تفاصيل المنشور</span></h1></div>
      <div class="page-actions">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg><span class="en">Back</span><span class="ar">رجوع</span>
        </a>
        @if($canManage)
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg><span class="en">Edit</span><span class="ar">تعديل</span>
        </a>
        @endif
      </div>
    </div>

    <div class="grid-2" style="align-items:start">
      <div class="card">
        @if($post->image_path)
          <div style="height:200px;overflow:hidden">
            <img src="{{ $post->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
          </div>
        @else
          <div style="height:200px;background:var(--bg2);display:flex;align-items:center;justify-content:center">
            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="var(--muted)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          </div>
        @endif
        <div class="card-body">
          <h2 style="font-family:var(--font-disp);font-size:1.45rem;font-weight:700;margin-bottom:12px">
            <span class="en">{{ $post->title_en }}</span><span class="ar">{{ $post->title_ar }}</span>
          </h2>
          @if($post->excerpt_en || $post->excerpt_ar)
          <p style="font-size:13.5px;color:var(--muted);margin-bottom:16px;line-height:1.75">
            <span class="en">{{ $post->excerpt_en }}</span>
            <span class="ar">{{ $post->excerpt_ar }}</span>
          </p>
          @endif
          <div style="font-size:14px;color:var(--text2);line-height:1.8">
            <div class="en">{!! $post->content_en !!}</div>
            <div class="ar">{!! $post->content_ar !!}</div>
          </div>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span class="en">Post Info</span><span class="ar">معلومات</span></div></div>
          <div class="metric-item">
            <span class="metric-label"><span class="en">Status</span><span class="ar">الحالة</span></span>
            <span class="badge badge-{{ $post->status }}">
              <span class="en">{{ ucfirst($post->status) }}</span>
              <span class="ar">@switch($post->status)@case('published')منشور@break@case('draft')مسودة@break@default مؤرشف@endswitch</span>
            </span>
          </div>
          <div class="metric-item"><span class="metric-label"><span class="en">Category</span><span class="ar">الفئة</span></span><span class="metric-value">{{ $post->category->name_en ?? '—' }}</span></div>
          <div class="metric-item"><span class="metric-label"><span class="en">Author</span><span class="ar">الكاتب</span></span><span class="metric-value">{{ $post->user->name ?? '—' }}</span></div>
          <div class="metric-item"><span class="metric-label"><span class="en">Published</span><span class="ar">نشر في</span></span><span class="metric-value">{{ optional($post->published_at)->format('M j, Y') ?? '—' }}</span></div>
          <div class="metric-item"><span class="metric-label"><span class="en">Views</span><span class="ar">المشاهدات</span></span><span class="metric-value">{{ number_format($post->views_count) }}</span></div>
          <div class="metric-item">
            <span class="metric-label"><span class="en">Featured</span><span class="ar">مميز</span></span>
            <span class="badge {{ $post->is_featured ? 'badge-published' : '' }}">
              <span class="en">{{ $post->is_featured ? 'Yes' : 'No' }}</span>
              <span class="ar">{{ $post->is_featured ? 'نعم' : 'لا' }}</span>
            </span>
          </div>
        </div>

        @if($canManage)
        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg><span class="en">Actions</span><span class="ar">إجراءات</span></div></div>
          <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
              <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg><span class="en">Edit Post</span><span class="ar">تعديل</span>
            </a>

            <form method="POST" action="{{ route('admin.posts.toggle-status', $post) }}">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-outline" style="width:100%">
                <span class="en">{{ $post->status === 'published' ? 'Move to Draft' : 'Publish' }}</span>
                <span class="ar">{{ $post->status === 'published' ? 'نقل إلى مسودة' : 'نشر' }}</span>
              </button>
            </form>

            <form method="POST" action="{{ route('admin.posts.toggle-featured', $post) }}">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-outline" style="width:100%">
                <span class="en">{{ $post->is_featured ? 'Unfeature' : 'Feature' }}</span>
                <span class="ar">{{ $post->is_featured ? 'إلغاء التمييز' : 'تمييز' }}</span>
              </button>
            </form>

            <form id="delete-post-{{ $post->id }}" method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display:none">
              @csrf
              @method('DELETE')
            </form>
            <button type="button" class="btn btn-danger"
                    onclick="confirmDelete('Delete this post?',function(){document.getElementById('delete-post-{{ $post->id }}').submit();})">
              <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg><span class="en">Delete Post</span><span class="ar">حذف</span>
            </button>
          </div>
        </div>
        @endif
      </div>
    </div>

@endsection
