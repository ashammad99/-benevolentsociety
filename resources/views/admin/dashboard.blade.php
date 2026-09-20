{{--
    BSD Admin — Dashboard
    resources/views/admin/dashboard.blade.php

    Phase 1 (admin shared layout): this page now extends the shared
    admin.layouts.app layout instead of duplicating the sidebar/topbar/head/
    scripts inline. Only the dashboard's own data-driven content lives here.
--}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('active_nav', 'dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Dashboard</span><span class="ar">لوحة التحكم</span></h1>
        <p class="page-subtitle">
          <span class="en">Welcome back, {{ auth()->user()->name }} — here's what's happening today.</span>
          <span class="ar">مرحباً بعودتك، {{ auth()->user()->name }} — إليك ما يحدث اليوم.</span>
        </p>
      </div>
      <div class="page-actions">
        <button class="btn btn-outline" onclick="showToast('Export coming soon','info')">
          <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          <span class="en">Export</span><span class="ar">تصدير</span>
        </button>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span class="en">New Post</span><span class="ar">منشور جديد</span>
        </a>
      </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stats-row">
      <div class="stat-card red">
        <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><span class="stat-trend up"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>12%</span></div>
        <div class="stat-value">8,547</div>
        <div class="stat-label"><span class="en">Total Beneficiaries</span><span class="ar">إجمالي المستفيدين</span></div>
        <div class="stat-sub"><strong>+92</strong> <span class="en">added this month</span><span class="ar">هذا الشهر</span></div>
      </div>
      <div class="stat-card blue">
        <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div><span class="stat-trend up"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>5%</span></div>
        <div class="stat-value">{{ number_format($stats['posts_total']) }}</div>
        <div class="stat-label"><span class="en">Total Posts</span><span class="ar">إجمالي المنشورات</span></div>
        <div class="stat-sub"><strong>{{ $stats['posts_draft'] }}</strong> <span class="en">drafts pending</span><span class="ar">مسودات معلّقة</span></div>
      </div>
      <div class="stat-card green">
        <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><span class="stat-trend up"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>8%</span></div>
        <div class="stat-value">{{ number_format($stats['posts_published']) }}</div>
        <div class="stat-label"><span class="en">Published Posts</span><span class="ar">المنشورات النشطة</span></div>
        <div class="stat-sub"><strong>{{ $stats['categories'] }}</strong> <span class="en">categories</span><span class="ar">فئات</span></div>
      </div>
      <div class="stat-card amber">
        <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div><span class="stat-trend down"><svg viewBox="0 0 24 24"><polyline points="18 9 12 15 6 9"/></svg>3%</span></div>
        <div class="stat-value">{{ number_format($stats['media_total']) }}</div>
        <div class="stat-label"><span class="en">Media Files</span><span class="ar">ملفات الوسائط</span></div>
        <div class="stat-sub"><strong>{{ $stats['ads_active'] }}</strong> <span class="en">active ads</span><span class="ar">إعلانات نشطة</span></div>
      </div>
    </div>

    {{-- Recent Posts + Activity --}}
    <div class="grid-2" style="margin-bottom:20px;">

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span class="en">Recent Posts</span><span class="ar">المنشورات الأخيرة</span>
          </div>
          <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost btn-sm">
            <span class="en">View all →</span><span class="ar">← الكل</span>
          </a>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr>
              <th><span class="en">Post</span><span class="ar">المنشور</span></th>
              <th><span class="en">Status</span><span class="ar">الحالة</span></th>
              <th><span class="en">Date</span><span class="ar">التاريخ</span></th>
              <th></th>
            </tr></thead>
            <tbody>
              @forelse($recent_posts as $post)
              <tr>
                <td>
                  <div class="post-title-cell">
                    <div class="post-thumb" style="background:var(--blue-light)">
                      @if($post->image_path)
                        <img src="{{ asset('storage/'.$post->image_path) }}" alt=""
                             style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                      @else
                        <svg viewBox="0 0 24 24" stroke="var(--blue)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                      @endif
                    </div>
                    <div>
                      <div class="post-title-text">
                        <span class="en">{{ Str::limit($post->title_en, 40) }}</span>
                        <span class="ar">{{ Str::limit($post->title_ar, 40) }}</span>
                      </div>
                      <div class="post-meta">
                        {{ $post->user->name ?? '—' }} ·
                        <span class="en">{{ $post->category->name_en ?? '—' }}</span>
                        <span class="ar">{{ $post->category->name_ar ?? '—' }}</span>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge badge-{{ $post->status }}">
                    <span class="en">{{ ucfirst($post->status) }}</span>
                    <span class="ar">@switch($post->status)@case('published')منشور@break@case('draft')مسودة@break@default مؤرشف@endswitch</span>
                  </span>
                </td>
                <td style="color:var(--muted);font-size:12.5px;">
                  {{ optional($post->published_at ?? $post->created_at)->format('M j, Y') }}
                </td>
                <td>
                  <div class="row-actions">
                    <a href="{{ route('admin.posts.show', $post) }}" class="row-action" title="View">
                      <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="row-action" title="Edit">
                      <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--muted);">
                <span class="en">No posts yet.</span><span class="ar">لا توجد منشورات بعد.</span>
              </td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span class="en">Recent Activity</span><span class="ar">النشاط الأخير</span>
          </div>
        </div>
        @forelse($recent_posts->take(5) as $post)
        <div class="activity-item">
          <div class="activity-dot ad-{{ ['blue','green','amber','red','purple'][$loop->index%5] }}">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
          </div>
          <div>
            <div class="activity-msg">
              <strong>{{ $post->user->name ?? '—' }}</strong>
              <span class="en">{{ $post->status==='published'?'published':'saved draft' }}</span>
              <span class="ar">{{ $post->status==='published'?'نشر':'حفظ مسودة' }}</span>
            </div>
            <div class="activity-time">{{ optional($post->updated_at)->diffForHumans() }}</div>
          </div>
        </div>
        @empty
        <div style="padding:24px;text-align:center;color:var(--muted);font-size:13.5px;">
          <span class="en">No activity yet.</span><span class="ar">لا يوجد نشاط بعد.</span>
        </div>
        @endforelse
      </div>

    </div>

    {{-- Progress + Quick Actions --}}
    <div class="grid-2">

      <div class="card">
        <div class="card-header">
          <div class="card-title"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg><span class="en">Posts by Category</span><span class="ar">المنشورات حسب الفئة</span></div>
          <span style="font-size:11px;color:var(--muted)">{{ now()->year }}</span>
        </div>
        <div style="padding:20px;">
          @php
            $cats   = \App\Models\Category::withCount(['posts'=>fn($q)=>$q->where('status','published')])->orderByDesc('posts_count')->take(5)->get();
            $maxCnt = $cats->max('posts_count') ?: 1;
            $clrs   = ['red','blue','green','purple','amber'];
          @endphp
          @forelse($cats as $cat)
          <div class="progress-item">
            <div class="progress-label-row">
              <span><span class="en">{{ $cat->name_en }}</span><span class="ar">{{ $cat->name_ar }}</span></span>
              <span>{{ $cat->posts_count }}</span>
            </div>
            <div class="progress-track">
              <div class="progress-fill" style="width:{{ round(($cat->posts_count/$maxCnt)*100) }}%;background:var(--{{ $clrs[$loop->index%5] }})"></div>
            </div>
          </div>
          @empty
          <p style="color:var(--muted);font-size:13.5px;"><span class="en">No categories yet.</span><span class="ar">لا توجد فئات.</span></p>
          @endforelse
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title"><svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg><span class="en">Quick Actions</span><span class="ar">إجراءات سريعة</span></div>
        </div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
          <a href="{{ route('admin.posts.create') }}" class="btn btn-primary" style="justify-content:flex-start"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg><span class="en">New Post</span><span class="ar">منشور جديد</span></a>
          <a href="{{ route('admin.media.index') }}" class="btn btn-outline" style="justify-content:flex-start"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/></svg><span class="en">Media Library</span><span class="ar">مكتبة الوسائط</span></a>
          <a href="{{ route('admin.categories.create') }}" class="btn btn-outline" style="justify-content:flex-start"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg><span class="en">Add Category</span><span class="ar">فئة جديدة</span></a>
          @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.users.create') }}" class="btn btn-outline" style="justify-content:flex-start"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg><span class="en">Add User</span><span class="ar">مستخدم جديد</span></a>
          <a href="{{ route('admin.settings.index') }}" class="btn btn-outline" style="justify-content:flex-start"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg><span class="en">Site Settings</span><span class="ar">إعدادات الموقع</span></a>
          @endif
        </div>
      </div>

    </div>

@endsection
