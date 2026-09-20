{{--
    BSD Admin — Users Index
    resources/views/admin/users/index.blade.php

    Phase 3: rewired to the shared admin.layouts.app layout and real data
    from UserController@index ($users, $stats). Edit/delete actions respect
    UserPolicy (admins can't edit/delete other admins or delete themselves).
--}}
@extends('admin.layouts.app')

@section('title', 'Users')
@section('active_nav', 'users')
@section('breadcrumb')<span class="en">Users</span><span class="ar">المستخدمون</span>@endsection

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Users</span><span class="ar">المستخدمون</span></h1>
        <p class="page-subtitle"><span class="en">Manage admin panel users and their roles.</span><span class="ar">إدارة مستخدمي لوحة الإدارة وأدوارهم.</span></p>
      </div>
      <div class="page-actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span class="en">Add User</span><span class="ar">إضافة مستخدم</span>
        </a>
      </div>
    </div>

    <div class="stats-row">
      <div class="stat-card blue"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div></div><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label"><span class="en">Total Users</span><span class="ar">المستخدمون</span></div></div>
      <div class="stat-card red"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div></div><div class="stat-value">{{ number_format($stats['admins']) }}</div><div class="stat-label"><span class="en">Admins</span><span class="ar">مسؤولون</span></div></div>
      <div class="stat-card green"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/></svg></div></div><div class="stat-value">{{ number_format($stats['editors']) }}</div><div class="stat-label"><span class="en">Editors</span><span class="ar">محررون</span></div></div>
      <div class="stat-card amber"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div><div class="stat-value">{{ number_format($stats['active']) }}</div><div class="stat-label"><span class="en">Active</span><span class="ar">نشطون</span></div></div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
      <div class="search-inline">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search users…">
      </div>
      <select class="filter-select" name="role" onchange="this.form.submit()">
        <option value="">All Roles</option>
        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        <option value="editor" @selected(request('role') === 'editor')>Editor</option>
        <option value="viewer" @selected(request('role') === 'viewer')>Viewer</option>
      </select>
      <button type="submit" class="btn btn-outline btn-sm"><span class="en">Filter</span><span class="ar">تصفية</span></button>
      @if(request()->anyFilled(['q','role']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm"><span class="en">Clear</span><span class="ar">مسح</span></a>
      @endif
    </form>

    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr>
            <th><span class="en">User</span><span class="ar">المستخدم</span></th>
            <th><span class="en">Email</span><span class="ar">البريد</span></th>
            <th><span class="en">Role</span><span class="ar">الدور</span></th>
            <th><span class="en">Status</span><span class="ar">الحالة</span></th>
            <th><span class="en">Posts</span><span class="ar">المنشورات</span></th>
            <th><span class="en">Actions</span><span class="ar">إجراءات</span></th>
          </tr></thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <div style="width:34px;height:34px;border-radius:50%;background:var(--red);color:white;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;font-family:var(--font-disp);flex-shrink:0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                  <div>
                    <div style="font-weight:600">{{ $user->name }}</div>
                    <div style="font-size:11.5px;color:var(--muted)">{{ $user->email }}</div>
                  </div>
                </div>
              </td>
              <td style="font-size:13px;color:var(--muted)">{{ $user->email }}</td>
              <td><span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
              <td>
                <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                  <span class="en">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                  <span class="ar">{{ $user->is_active ? 'نشط' : 'موقوف' }}</span>
                </span>
              </td>
              <td style="font-weight:600">{{ $user->posts_count }}</td>
              <td>
                <div class="row-actions">
                  @can('update', $user)
                  <a href="{{ route('admin.users.edit', $user) }}" class="row-action" title="Edit">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </a>
                  @endcan
                  @if($user->id !== auth()->id())
                  <form id="toggle-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="display:none">
                    @csrf
                    @method('PATCH')
                  </form>
                  <button type="button" class="row-action" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}"
                          onclick="document.getElementById('toggle-user-{{ $user->id }}').submit();">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
                  </button>
                  @endif
                  @can('delete', $user)
                  <form id="delete-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:none">
                    @csrf
                    @method('DELETE')
                  </form>
                  <button type="button" class="row-action" title="Delete"
                          onclick="confirmDelete('Delete this user?',function(){document.getElementById('delete-user-{{ $user->id }}').submit();})">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                  @endcan
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--muted);">
              <span class="en">No users found.</span><span class="ar">لا يوجد مستخدمون.</span>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @include('admin.partials.pagination', ['paginator' => $users])
    </div>

@endsection
