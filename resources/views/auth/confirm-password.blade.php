@extends('layouts.auth')

@section('title', app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm password')

@section('card')
    <div class="card-header">
        <h2 class="card-title">
            <span class="en">Confirm your password</span>
            <span class="ar">أكّد كلمة المرور</span>
        </h2>
        <p class="card-subtitle">
            <span class="en">This is a secure area. Please enter your password to continue.</span>
            <span class="ar">هذه منطقة محمية. يرجى إدخال كلمة المرور للمتابعة.</span>
        </p>
    </div>

    @include('auth.partials.alerts')

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        @include('auth.partials.password-input', [
            'id' => 'password', 'name' => 'password', 'autocomplete' => 'current-password', 'autofocus' => true,
            'labelEn' => 'Password', 'labelAr' => 'كلمة المرور',
        ])

        <button type="submit" class="btn-login">
            <span class="btn-text">
                <span class="en">Confirm</span>
                <span class="ar">تأكيد</span>
            </span>
        </button>
    </form>
@endsection
