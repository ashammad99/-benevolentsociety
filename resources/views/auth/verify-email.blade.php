@extends('layouts.auth')

@section('title', app()->getLocale() === 'ar' ? 'تأكيد البريد الإلكتروني' : 'Verify email')

@section('card')
    <div class="card-header">
        <h2 class="card-title">
            <span class="en">Verify your email address</span>
            <span class="ar">أكّد بريدك الإلكتروني</span>
        </h2>
        <p class="card-subtitle">
            <span class="en">Please click the link we emailed you to verify your address. If you didn't receive it, we can send another.</span>
            <span class="ar">يرجى الضغط على الرابط الذي أرسلناه إلى بريدك لتأكيده. إن لم يصلك، يمكننا إرسال رابط آخر.</span>
        </p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success" role="status">
            <span class="en">A new verification link has been sent to your email address.</span>
            <span class="ar">تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.</span>
        </div>
    @endif

    @include('auth.partials.alerts')

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-login">
            <span class="btn-text">
                <span class="en">Resend verification email</span>
                <span class="ar">إعادة إرسال رسالة التأكيد</span>
            </span>
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="card-footer-link">
        @csrf
        <button type="submit" class="btn-link">
            <span class="en">Log out</span>
            <span class="ar">تسجيل الخروج</span>
        </button>
    </form>
@endsection
