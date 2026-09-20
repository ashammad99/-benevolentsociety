{{--
    BSD Admin — Ads Index
    resources/views/admin/ads/index.blade.php

    Phase 3: rewired to the shared admin.layouts.app layout and real data
    from AdController@index ($ads, $stats).
--}}
@extends('admin.layouts.app')

@section('title', 'Ads')
@section('active_nav', 'ads')
@section('breadcrumb')<span class="en">Ads</span><span class="ar">الإعلانات</span>@endsection

@section('content')

    @php $canManage = auth()->user()->isEditor(); @endphp

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Ads & Announcements</span><span class="ar">الإعلانات والإشعارات</span></h1>
        <p class="page-subtitle"><span class="en">Manage banners, popups, and announcements shown on the site.</span><span class="ar">إدارة البانرات والنوافذ المنبثقة والإعلانات.</span></p>
      </div>
      @if($canManage)
      <div class="page-actions">
        <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span class="en">New Ad</span><span class="ar">إعلان جديد</span>
        </a>
      </div>
      @endif
    </div>

    <div class="stats-row">
      <div class="stat-card green"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div></div><div class="stat-value">{{ number_format($stats['active']) }}</div><div class="stat-label"><span class="en">Active Ads</span><span class="ar">نشطة</span></div></div>
      <div class="stat-card amber"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg></div></div><div class="stat-value">{{ number_format($stats['paused']) }}</div><div class="stat-label"><span class="en">Paused</span><span class="ar">موقوف</span></div></div>
      <div class="stat-card blue"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div></div><div class="stat-value">{{ number_format($stats['banners']) }}</div><div class="stat-label"><span class="en">Banners</span><span class="ar">بانرات</span></div></div>
      <div class="stat-card red"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div></div><div class="stat-value">{{ number_format($stats['popups']) }}</div><div class="stat-label"><span class="en">Popups</span><span class="ar">نوافذ</span></div></div>
    </div>

    <form method="GET" action="{{ route('admin.ads.index') }}" class="filter-bar">
      <select class="filter-select" name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="paused" @selected(request('status') === 'paused')>Paused</option>
        <option value="expired" @selected(request('status') === 'expired')>Expired</option>
      </select>
      <select class="filter-select" name="type" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="banner" @selected(request('type') === 'banner')>Banner</option>
        <option value="popup" @selected(request('type') === 'popup')>Popup</option>
        <option value="sidebar" @selected(request('type') === 'sidebar')>Sidebar</option>
        <option value="inline" @selected(request('type') === 'inline')>Inline</option>
      </select>
      @if(request()->anyFilled(['status','type']))
        <a href="{{ route('admin.ads.index') }}" class="btn btn-ghost btn-sm"><span class="en">Clear</span><span class="ar">مسح</span></a>
      @endif
    </form>

    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr>
            <th><span class="en">Title</span><span class="ar">العنوان</span></th>
            <th><span class="en">Type</span><span class="ar">النوع</span></th>
            <th><span class="en">Status</span><span class="ar">الحالة</span></th>
            <th><span class="en">Start</span><span class="ar">البداية</span></th>
            <th><span class="en">End</span><span class="ar">النهاية</span></th>
            @if($canManage)
            <th><span class="en">Actions</span><span class="ar">إجراءات</span></th>
            @endif
          </tr></thead>
          <tbody>
            @forelse($ads as $ad)
            <tr>
              <td>
                <div>
                  <div style="font-weight:600"><span class="en">{{ $ad->title_en }}</span><span class="ar">{{ $ad->title_ar }}</span></div>
                  @if($ad->body_en || $ad->body_ar)
                  <div style="font-size:11.5px;color:var(--muted);margin-top:1px"><span class="en">{{ Str::limit($ad->body_en, 50) }}</span><span class="ar">{{ Str::limit($ad->body_ar, 50) }}</span></div>
                  @endif
                </div>
              </td>
              <td><span class="badge" style="background:var(--blue-light);color:var(--blue-dark)">{{ ucfirst($ad->type) }}</span></td>
              <td>
                <span class="badge {{ $ad->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                  <span class="en">{{ ucfirst($ad->status) }}</span>
                  <span class="ar">@switch($ad->status)@case('active')نشط@break@case('paused')موقوف@break@default منتهي@endswitch</span>
                </span>
              </td>
              <td style="font-size:12.5px;color:var(--muted)">{{ optional($ad->starts_at)->format('M j, Y') ?? '—' }}</td>
              <td style="font-size:12.5px;color:var(--muted)">{{ optional($ad->ends_at)->format('M j, Y') ?? '—' }}</td>
              @if($canManage)
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.ads.edit', $ad) }}" class="row-action" title="Edit">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </a>
                  <form id="toggle-ad-{{ $ad->id }}" method="POST" action="{{ route('admin.ads.toggle-status', $ad) }}" style="display:none">
                    @csrf
                    @method('PATCH')
                  </form>
                  <button type="button" class="row-action" title="{{ $ad->status === 'active' ? 'Pause' : 'Activate' }}"
                          onclick="document.getElementById('toggle-ad-{{ $ad->id }}').submit();">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
                  </button>
                  <form id="delete-ad-{{ $ad->id }}" method="POST" action="{{ route('admin.ads.destroy', $ad) }}" style="display:none">
                    @csrf
                    @method('DELETE')
                  </form>
                  <button type="button" class="row-action" title="Delete"
                          onclick="confirmDelete('Delete this ad?',function(){document.getElementById('delete-ad-{{ $ad->id }}').submit();})">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </div>
              </td>
              @endif
            </tr>
            @empty
            <tr><td colspan="{{ $canManage ? 6 : 5 }}" style="text-align:center;padding:32px;color:var(--muted);">
              <span class="en">No ads found.</span><span class="ar">لا توجد إعلانات.</span>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @include('admin.partials.pagination', ['paginator' => $ads])
    </div>

@endsection
