@extends('layouts.auth')

@section('title', app()->getLocale() === 'ar' ? 'إعادة تعيين كلمة المرور' : 'Reset password')

@section('card')
    <div class="card-header">
        <h2 class="card-title">
            <span class="en">Choose a new password</span>
            <span class="ar">اختر كلمة مرور جديدة</span>
        </h2>
        <p class="card-subtitle">
            <span class="en">Use at least 8 characters.</span>
            <span class="ar">استخدم 8 أحرف على الأقل.</span>
        </p>
    </div>

    @include('auth.partials.alerts')

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        @include('auth.partials.email-input', ['value' => old('email', $request->email)])

        @include('auth.partials.password-input', [
            'id' => 'password', 'name' => 'password', 'autocomplete' => 'new-password', 'autofocus' => true,
            'labelEn' => 'New password', 'labelAr' => 'كلمة المرور الجديدة',
        ])

        @include('auth.partials.password-input', [
            'id' => 'password_confirmation', 'name' => 'password_confirmation', 'autocomplete' => 'new-password',
            'labelEn' => 'Confirm new password', 'labelAr' => 'تأكيد كلمة المرور الجديدة',
        ])

        <button type="submit" class="btn-login">
            <span class="btn-text">
                <span class="en">Reset password</span>
                <span class="ar">إعادة تعيين كلمة المرور</span>
            </span>
        </button>
    </form>

    <div class="card-footer-link">
        <a href="{{ route('login') }}" class="forgot-link">
            <span class="en">Back to sign in</span>
            <span class="ar">العودة إلى تسجيل الدخول</span>
        </a>
    </div>
@endsection
