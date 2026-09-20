{{--
    BSD Admin — New User
    resources/views/admin/users/create.blade.php

    Phase 3: real form wired to UserController@store / UserRequest.
--}}
@extends('admin.layouts.app')

@section('title', 'New User')
@section('active_nav', 'users')
@section('breadcrumb')<span class="en">New User</span><span class="ar">مستخدم جديد</span>@endsection

@section('content')

    <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <div class="page-header">
      <div><h1 class="page-title"><span class="en">New User</span><span class="ar">مستخدم جديد</span></h1></div>
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
            <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Dana">
            @error('name')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Email Address</span><span class="ar">البريد الإلكتروني</span> <span class="required">*</span></label>
            <input class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="user@interpal.org">
            @error('email')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Role</span><span class="ar">الدور</span> <span class="required">*</span></label>
            <select class="form-select" name="role">
              <option value="admin" @selected(old('role') === 'admin')>Admin</option>
              <option value="editor" @selected(old('role', 'editor') === 'editor')>Editor</option>
              <option value="viewer" @selected(old('role') === 'viewer')>Viewer</option>
            </select>
            @error('role')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">
              <span class="en">Password</span>
              <span class="ar">كلمة المرور</span>
              <span class="required">*</span>
            </label>
            <input class="form-input" type="password" name="password" placeholder="••••••••">
            @error('password')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Confirm Password</span><span class="ar">تأكيد كلمة المرور</span> <span class="required">*</span></label>
            <input class="form-input" type="password" name="password_confirmation" placeholder="••••••••">
          </div>
          <div class="toggle-wrap">
            <input type="hidden" name="is_active" value="{{ old('is_active', '1') }}">
            <div class="toggle {{ old('is_active', true) ? 'on' : '' }}"></div>
            <span style="font-size:13px"><span class="en">Active Account</span><span class="ar">حساب نشط</span></span>
          </div>
        </div>
        <div class="card-footer" style="justify-content:flex-end">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><span class="en">Cancel</span><span class="ar">إلغاء</span></a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="en">Create User</span><span class="ar">إنشاء</span>
          </button>
        </div>
      </div>
    </div>
    </form>

@endsection
