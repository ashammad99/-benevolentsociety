{{--
    BSD Admin — Categories Index
    resources/views/admin/categories/index.blade.php

    Phase 3: rewired to the shared admin.layouts.app layout and real data
    from CategoryController@index ($categories, with posts_count).
--}}
@extends('admin.layouts.app')

@section('title', 'Categories')
@section('active_nav', 'categories')
@section('breadcrumb')<span class="en">Categories</span><span class="ar">الفئات</span>@endsection

@section('content')

    @php $canManage = auth()->user()->isEditor(); @endphp

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Categories</span><span class="ar">الفئات</span></h1>
        <p class="page-subtitle"><span class="en">Manage bilingual post categories.</span><span class="ar">إدارة فئات المنشورات بالعربية والإنجليزية.</span></p>
      </div>
      @if($canManage)
      <div class="page-actions">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span class="en">New Category</span><span class="ar">فئة جديدة</span>
        </a>
      </div>
      @endif
    </div>

    <div class="stats-row">
      <div class="stat-card blue"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></div></div><div class="stat-value">{{ number_format($categories->count()) }}</div><div class="stat-label"><span class="en">Total Categories</span><span class="ar">الفئات</span></div></div>
      <div class="stat-card green"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div></div><div class="stat-value">{{ number_format($categories->sum('posts_count')) }}</div><div class="stat-label"><span class="en">Total Posts</span><span class="ar">المنشورات</span></div></div>
      @php $topCategory = $categories->sortByDesc('posts_count')->first(); @endphp
      <div class="stat-card amber"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div></div><div class="stat-value">{{ $topCategory->posts_count ?? 0 }}</div><div class="stat-label"><span class="en">Most Posts</span><span class="ar">أكثر</span></div><div class="stat-sub"><strong>{{ $topCategory->name_en ?? '—' }}</strong></div></div>
      <div class="stat-card red"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div></div><div class="stat-value">{{ $categories->pluck('user_id')->unique()->count() }}</div><div class="stat-label"><span class="en">Active Authors</span><span class="ar">المؤلفون</span></div></div>
    </div>

    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr>
            <th><span class="en">Name (EN)</span><span class="ar">الاسم (إن)</span></th>
            <th><span class="en">Name (AR)</span><span class="ar">الاسم (ع)</span></th>
            <th>Slug</th>
            <th><span class="en">Posts</span><span class="ar">المنشورات</span></th>
            <th><span class="en">Created by</span><span class="ar">أنشأها</span></th>
            @if($canManage)
            <th><span class="en">Actions</span><span class="ar">إجراءات</span></th>
            @endif
          </tr></thead>
          <tbody>
            @forelse($categories as $category)
            <tr>
              <td style="font-weight:600">{{ $category->name_en }}</td>
              <td dir="rtl" style="font-family:var(--font-ar)">{{ $category->name_ar }}</td>
              <td style="color:var(--muted);font-size:12.5px">{{ $category->slug }}</td>
              <td><span class="badge" style="background:var(--blue-light);color:var(--blue-dark)">{{ $category->posts_count }}</span></td>
              <td style="color:var(--muted);font-size:12.5px">{{ $category->user->name ?? '—' }} · {{ $category->created_at?->format('M Y') }}</td>
              @if($canManage)
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.categories.edit', $category) }}" class="row-action" title="Edit">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </a>
                  <form id="delete-category-{{ $category->id }}" method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:none">
                    @csrf
                    @method('DELETE')
                  </form>
                  <button type="button" class="row-action" title="Delete"
                          onclick="confirmDelete('Delete this category?',function(){document.getElementById('delete-category-{{ $category->id }}').submit();})">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </div>
              </td>
              @endif
            </tr>
            @empty
            <tr><td colspan="{{ $canManage ? 6 : 5 }}" style="text-align:center;padding:32px;color:var(--muted);">
              <span class="en">No categories found.</span><span class="ar">لا توجد فئات.</span>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

@endsection
