@extends('layouts.auth')

@section('title', app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login')

@section('card')
    <div class="card-header">
        <h2 class="card-title">
            <span class="en">Sign in</span>
            <span class="ar">تسجيل الدخول</span>
        </h2>
        <p class="card-subtitle">
            <span class="en">Enter your credentials to access the admin panel</span>
            <span class="ar">أدخل بياناتك للوصول إلى لوحة التحكم</span>
        </p>
    </div>

    @include('auth.partials.alerts')

    <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        @include('auth.partials.email-input', ['value' => old('email')])

        @include('auth.partials.password-input', [
            'id' => 'password', 'name' => 'password', 'autocomplete' => 'current-password',
            'labelEn' => 'Password', 'labelAr' => 'كلمة المرور',
        ])

        <div class="form-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember">
                <span class="en">Remember me</span>
                <span class="ar">تذكرني</span>
            </label>
            <a href="{{ route('password.request') }}" class="forgot-link">
                <span class="en">Forgot password?</span>
                <span class="ar">نسيت كلمة المرور؟</span>
            </a>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <span class="btn-text">
                <span class="en">Sign in to Dashboard</span>
                <span class="ar">دخول إلى لوحة التحكم</span>
            </span>
        </button>
    </form>
@endsection
