{{--
    BSD Admin — Edit User
    resources/views/admin/users/edit.blade.php

    Phase 3: real form wired to UserController@update / UserRequest,
    pre-populated from the existing $user. Access itself is gated by
    UserPolicy::update in the controller before this view ever renders.
--}}
@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('active_nav', 'users')
@section('breadcrumb')<span class="en">Edit User</span><span class="ar">تعديل مستخدم</span>@endsection

@section('content')

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')

    <div class="page-header">
      <div><h1 class="page-title"><span class="en">Edit User</span><span class="ar">تعديل مستخدم</span></h1></div>
      <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg><span class="en">Back</span><span class="ar">رجوع</span>
        </a>
      </div>
    </div>

    <div style="max-width:640px">
      <div class="card">
        <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span class="en">User Details</span><span class="ar">بيانات المستخدم</span></div></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
          <div class="form-group">
            <label class="form-label"><span class="en">Full Name</span><span class="ar">الاسم الكامل</span> <span class="required">*</span></label>
            <input class="form-input" type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="e.g. Dana">
            @error('name')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Email Address</span><span class="ar">البريد الإلكتروني</span> <span class="required">*</span></label>
            <input class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="user@interpal.org">
            @error('email')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Role</span><span class="ar">الدور</span> <span class="required">*</span></label>
            <select class="form-select" name="role">
              <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
              <option value="editor" @selected(old('role', $user->role) === 'editor')>Editor</option>
              <option value="viewer" @selected(old('role', $user->role) === 'viewer')>Viewer</option>
            </select>
            @error('role')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            @if($user->id === auth()->id())
            <div class="form-hint"><span class="en">You cannot change your own role.</span><span class="ar">لا يمكنك تغيير دورك الخاص.</span></div>
            @endif
          </div>
          <div class="form-group">
            <label class="form-label">
              <span class="en">New Password</span>
              <span class="ar">كلمة مرور جديدة</span>
              <span style="font-size:11.5px;color:var(--muted);font-weight:400"> (leave blank to keep)</span>
            </label>
            <input class="form-input" type="password" name="password" placeholder="••••••••">
            @error('password')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Confirm New Password</span><span class="ar">تأكيد كلمة المرور</span></label>
            <input class="form-input" type="password" name="password_confirmation" placeholder="••••••••">
          </div>
          <div class="toggle-wrap">
            <input type="hidden" name="is_active" value="{{ old('is_active', $user->is_active ? '1' : '0') }}">
            <div class="toggle {{ old('is_active', $user->is_active) ? 'on' : '' }}"></div>
            <span style="font-size:13px"><span class="en">Active Account</span><span class="ar">حساب نشط</span></span>
          </div>
        </div>
        <div class="card-footer" style="justify-content:flex-end">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><span class="en">Cancel</span><span class="ar">إلغاء</span></a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="en">Update User</span><span class="ar">تحديث</span>
          </button>
        </div>
      </div>
    </div>
    </form>

@endsection
