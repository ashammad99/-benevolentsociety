{{--
    BSD Admin — Posts Index
    resources/views/admin/posts/index.blade.php

    Phase 3 (Posts CRUD pilot): now extends the shared admin.layouts.app
    layout and renders real data from PostController@index ($posts,
    $categories, $stats) instead of the old static mockup.
--}}
@extends('admin.layouts.app')

@section('title', 'Posts')
@section('active_nav', 'posts')
@section('breadcrumb')<span class="en">Posts</span><span class="ar">المنشورات</span>@endsection

@section('content')

    @php $canManage = auth()->user()->isEditor(); @endphp

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Posts</span><span class="ar">المنشورات</span></h1>
        <p class="page-subtitle"><span class="en">Manage all posts — published, drafts, and archived.</span><span class="ar">إدارة جميع المنشورات المنشورة والمسودات والمؤرشفة.</span></p>
      </div>
      @if($canManage)
      <div class="page-actions">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span class="en">New Post</span><span class="ar">منشور جديد</span>
        </a>
      </div>
      @endif
    </div>

    <div class="stats-row">
      <div class="stat-card blue"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div></div><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label"><span class="en">All Posts</span><span class="ar">الكل</span></div></div>
      <div class="stat-card green"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div></div><div class="stat-value">{{ number_format($stats['published']) }}</div><div class="stat-label"><span class="en">Published</span><span class="ar">منشور</span></div></div>
      <div class="stat-card amber"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/></svg></div></div><div class="stat-value">{{ number_format($stats['draft']) }}</div><div class="stat-label"><span class="en">Drafts</span><span class="ar">مسودات</span></div></div>
      <div class="stat-card red"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></div></div><div class="stat-value">{{ number_format($stats['archived']) }}</div><div class="stat-label"><span class="en">Archived</span><span class="ar">مؤرشف</span></div></div>
    </div>

    <form method="GET" action="{{ route('admin.posts.index') }}" class="filter-bar">
      <div class="search-inline">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search posts…">
      </div>
      <select class="filter-select" name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="published" @selected(request('status') === 'published')>Published</option>
        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
        <option value="archived" @selected(request('status') === 'archived')>Archived</option>
      </select>
      <select class="filter-select" name="category_id" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name_en }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-outline btn-sm">
        <span class="en">Filter</span><span class="ar">تصفية</span>
      </button>
      @if(request()->anyFilled(['q','status','category_id']))
        <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost btn-sm"><span class="en">Clear</span><span class="ar">مسح</span></a>
      @endif
    </form>

    @if($canManage)
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.posts.bulk-delete') }}">
      @csrf
    </form>
    @endif

    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr>
            @if($canManage)
            <th style="width:36px"><input type="checkbox" style="accent-color:var(--red);" onclick="this.closest('table').querySelectorAll('tbody input[type=checkbox]').forEach(function(cb){cb.checked=this.checked;}.bind(this))"></th>
            @endif
            <th><span class="en">Post</span><span class="ar">المنشور</span></th>
            <th><span class="en">Category</span><span class="ar">الفئة</span></th>
            <th><span class="en">Status</span><span class="ar">الحالة</span></th>
            <th><span class="en">Views</span><span class="ar">المشاهدات</span></th>
            <th><span class="en">Date</span><span class="ar">التاريخ</span></th>
            <th><span class="en">Actions</span><span class="ar">إجراءات</span></th>
          </tr></thead>
          <tbody>
            @forelse($posts as $post)
            <tr>
              @if($canManage)
              <td><input type="checkbox" name="ids[]" value="{{ $post->id }}" form="bulk-delete-form" style="accent-color:var(--red);"></td>
              @endif
              <td>
                <div class="post-title-cell">
                  <div class="post-thumb" style="background:var(--blue-light)">
                    @if($post->image_path)
                      <img src="{{ $post->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                    @else
                      <svg viewBox="0 0 24 24" stroke="var(--blue)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                    @endif
                  </div>
                  <div>
                    <div class="post-title-text">
                      <span class="en">{{ Str::limit($post->title_en, 40) }}</span>
                      <span class="ar">{{ Str::limit($post->title_ar, 40) }}</span>
                    </div>
                    <div class="post-meta">{{ $post->user->name ?? '—' }}</div>
                  </div>
                </div>
              </td>
              <td><span class="badge">{{ $post->category->name_en ?? '—' }}</span></td>
              <td>
                <span class="badge badge-{{ $post->status }}">
                  <span class="en">{{ ucfirst($post->status) }}</span>
                  <span class="ar">@switch($post->status)@case('published')منشور@break@case('draft')مسودة@break@default مؤرشف@endswitch</span>
                </span>
              </td>
              <td style="font-weight:600">{{ number_format($post->views_count) }}</td>
              <td style="color:var(--muted);font-size:12.5px">{{ optional($post->published_at ?? $post->created_at)->format('M j, Y') }}</td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.posts.show', $post) }}" class="row-action" title="View">
                    <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>
                  @if($canManage)
                  <a href="{{ route('admin.posts.edit', $post) }}" class="row-action" title="Edit">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </a>
                  <form id="delete-post-{{ $post->id }}" method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display:none">
                    @csrf
                    @method('DELETE')
                  </form>
                  <button type="button" class="row-action" title="Delete"
                          onclick="confirmDelete('Delete this post?',function(){document.getElementById('delete-post-{{ $post->id }}').submit();})">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="{{ $canManage ? 7 : 6 }}" style="text-align:center;padding:32px;color:var(--muted);">
              <span class="en">No posts found.</span><span class="ar">لا توجد منشورات.</span>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($canManage)
      <div style="padding:12px 20px;border-top:1px solid var(--border);">
        <button type="submit" form="bulk-delete-form" class="btn btn-outline btn-sm"
                onclick="if(!document.querySelector('input[name=&quot;ids[]&quot;]:checked')){alert('Select at least one post first.');return false;} return confirm('Delete the selected posts?');">
          <span class="en">Delete Selected</span><span class="ar">حذف المحدد</span>
        </button>
      </div>
      @endif

      @include('admin.partials.pagination', ['paginator' => $posts])
    </div>

@endsection
