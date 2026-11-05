@extends('layouts.auth')

@section('title', app()->getLocale() === 'ar' ? 'نسيت كلمة المرور' : 'Forgot password')

@section('card')
    <div class="card-header">
        <h2 class="card-title">
            <span class="en">Forgot your password?</span>
            <span class="ar">نسيت كلمة المرور؟</span>
        </h2>
        <p class="card-subtitle">
            <span class="en">Enter your email address and we will send you a link to choose a new password.</span>
            <span class="ar">أدخل بريدك الإلكتروني وسنرسل لك رابطاً لاختيار كلمة مرور جديدة.</span>
        </p>
    </div>

    @include('auth.partials.alerts')

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @include('auth.partials.email-input', ['value' => old('email'), 'autofocus' => true])

        <button type="submit" class="btn-login">
            <span class="btn-text">
                <span class="en">Email password reset link</span>
                <span class="ar">إرسال رابط إعادة التعيين</span>
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
