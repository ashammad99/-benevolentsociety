<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" id="page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ config('society.name.'.app()->getLocale()) ?? config('society.name.en') }}</title>
    <link rel="icon" href="{{ asset(config('society.logo')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;500;600&family=Noto+Naskh+Arabic:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --red:       #C0392B;
            --red-dark:  #7B241C;
            --red-light: #FDECEA;
            --red-mid:   #E74C3C;
            --blue:      #2471A3;
            --blue-dark: #1A5276;
            --text:      #1A1A1A;
            --muted:     #6B6B6B;
            --border:    rgba(0,0,0,0.10);
            --bg:        #FAFAF9;
            --card:      #FFFFFF;
            --font-en:   'Source Sans 3', sans-serif;
            --font-ar:   'Noto Naskh Arabic', serif;
            --font-disp: 'Playfair Display', serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: var(--font-en);
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── LEFT PANEL ───────────────────────────────── */
        .left-panel {
            width: 46%;
            min-height: 100vh;
            background: var(--red-dark);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* Decorative crescent shape echoing the logo */
        .left-panel::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -140px;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            border: 80px solid rgba(255,255,255,0.06);
            pointer-events: none;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -100px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            border: 60px solid rgba(255,255,255,0.04);
            pointer-events: none;
        }

        /* Diagonal accent stripe */
        .panel-stripe {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(
                150deg,
                rgba(192,57,43,0.0) 0%,
                rgba(192,57,43,0.0) 55%,
                rgba(0,0,0,0.18) 100%
            );
            pointer-events: none;
        }

        .panel-top {
            position: relative;
            z-index: 2;
        }

        .panel-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 0;
            animation: fadeSlideUp 0.7s ease both;
        }
        .panel-logo img {
            width: 54px;
            height: 54px;
            object-fit: contain;
            border-radius: 10px;
            background: white;
            padding: 3px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }
        .panel-logo-name {
            font-family: var(--font-disp);
            font-size: 15px;
            font-weight: 600;
            color: rgba(255,255,255,0.92);
            line-height: 1.3;
        }
        .panel-logo-name span {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.04em;
            font-family: var(--font-en);
            margin-top: 2px;
        }
        [dir="rtl"] .panel-logo-name { font-family: var(--font-ar); }

        .panel-middle {
            position: relative;
            z-index: 2;
            animation: fadeSlideUp 0.7s 0.15s ease both;
        }

        .panel-tagline {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            margin-bottom: 1rem;
        }

        .panel-headline {
            font-family: var(--font-disp);
            font-size: 2.6rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1.25rem;
        }
        .panel-headline-ar {
            font-family: var(--font-ar);
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.35;
            margin-bottom: 1.25rem;
            display: none;
        }

        .panel-desc {
            font-size: 15px;
            color: rgba(255,255,255,0.65);
            line-height: 1.75;
            max-width: 340px;
        }
        .panel-desc-ar {
            font-family: var(--font-ar);
            font-size: 15px;
            color: rgba(255,255,255,0.65);
            line-height: 1.8;
            max-width: 340px;
            display: none;
        }

        /* Stats row */
        .panel-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }
        .pstat-num {
            font-family: var(--font-disp);
            font-size: 1.9rem;
            font-weight: 700;
            color: white;
            display: block;
        }
        .pstat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
        }

        .panel-bottom {
            position: relative;
            z-index: 2;
            font-size: 12px;
            color: rgba(255,255,255,0.3);
            animation: fadeSlideUp 0.7s 0.3s ease both;
        }

        /* ── RIGHT PANEL ──────────────────────────────── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        /* Subtle grid texture on the right */
        .right-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 2.75rem 2.5rem;
            box-shadow:
                0 2px 4px rgba(0,0,0,0.03),
                0 8px 32px rgba(0,0,0,0.06),
                0 0 0 1px rgba(255,255,255,0.8) inset;
            position: relative;
            z-index: 1;
            animation: cardAppear 0.65s cubic-bezier(0.22,1,0.36,1) both;
        }

        /* Thin red accent top bar */
        .card-accent {
            position: absolute;
            top: 0; left: 2rem; right: 2rem;
            height: 3px;
            background: linear-gradient(90deg, var(--red), var(--red-mid), var(--blue));
            border-radius: 0 0 4px 4px;
        }

        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .card-title {
            font-family: var(--font-disp);
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.4rem;
        }
        [dir="rtl"] .card-title { font-family: var(--font-ar); font-size: 1.5rem; }

        .card-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        /* ── FORM ─────────────────────────────────────── */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--muted);
            pointer-events: none;
            transition: color 0.2s;
        }
        [dir="rtl"] .input-icon { left: auto; right: 14px; }

        .form-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14.5px;
            font-family: var(--font-en);
            color: var(--text);
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }
        [dir="rtl"] .form-input { padding: 11px 42px 11px 14px; font-family: var(--font-ar); text-align: right; }

        .form-input::placeholder { color: #aaa; font-size: 13.5px; }
        .form-input:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3.5px rgba(192,57,43,0.10);
        }
        .form-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--red); }

        /* Password toggle */
        .pwd-toggle {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        [dir="rtl"] .pwd-toggle { right: auto; left: 14px; }
        .pwd-toggle:hover { color: var(--red); }
        .pwd-toggle svg { width: 17px; height: 17px; }

        /* Remember + forgot row */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 17px;
            height: 17px;
            border: 1.5px solid var(--border);
            border-radius: 5px;
            background: #fff;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: border-color 0.2s, background 0.2s;
        }
        .checkbox-label input[type="checkbox"]:checked {
            background: var(--red);
            border-color: var(--red);
        }
        .checkbox-label input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 2px; left: 5px;
            width: 5px; height: 9px;
            border: 2px solid white;
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        .forgot-link {
            font-size: 13px;
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: var(--red-dark); text-decoration: underline; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--red);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: var(--font-en);
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.02em;
        }
        [dir="rtl"] .btn-login { font-family: var(--font-ar); }
        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
            pointer-events: none;
        }
        .btn-login:hover {
            background: var(--red-dark);
            box-shadow: 0 6px 20px rgba(192,57,43,0.30);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }

        /* Loading spinner inside button */
        .btn-login.loading .btn-text { opacity: 0; }
        .btn-login.loading::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            width: 20px; height: 20px;
            border: 2.5px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
            color: var(--muted);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Lang toggle */
        .lang-row {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 1.5rem;
        }
        .lang-btn {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            background: transparent;
            color: var(--muted);
            font-family: var(--font-en);
            transition: all 0.2s;
        }
        a.lang-btn { text-decoration: none; }
        .lang-btn.active {
            border-color: var(--red);
            color: var(--red);
            background: var(--red-light);
        }

        /* Error / success messages */
        .alert {
            padding: 11px 14px;
            border-radius: 9px;
            font-size: 13.5px;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            animation: fadeSlideUp 0.3s ease both;
        }
        .alert-error {
            background: var(--red-light);
            border: 1px solid rgba(192,57,43,0.2);
            color: var(--red-dark);
        }
        .alert-success {
            background: #EAF7EE;
            border: 1px solid rgba(39,174,96,0.25);
            color: #1E6B3A;
        }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        /* ── LANG HELPERS ────────────────────────────── */
        .en { display: block; } .ar { display: none; }
        span.en { display: inline; } span.ar { display: none; }
        [dir="rtl"] .en { display: none !important; }
        [dir="rtl"] .ar { display: block !important; }
        [dir="rtl"] span.en { display: none !important; }
        [dir="rtl"] span.ar { display: inline !important; }
        [dir="rtl"] .panel-headline { display: none; }
        [dir="rtl"] .panel-headline-ar { display: block; }
        [dir="rtl"] .panel-desc { display: none; }
        [dir="rtl"] .panel-desc-ar { display: block; }
        [dir="rtl"] .panel-logo-name { font-family: var(--font-ar); }
        [dir="rtl"] body { font-family: var(--font-ar); }
        [dir="rtl"] .panel-stats { flex-direction: row-reverse; }
        [dir="rtl"] .form-row { flex-direction: row-reverse; }

        /* ── ANIMATIONS ──────────────────────────────── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(24px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes spin {
            to { transform: translate(-50%,-50%) rotate(360deg); }
        }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 820px) {
            body { flex-direction: column; }
            .left-panel {
                width: 100%;
                min-height: auto;
                padding: 2rem 1.75rem 2.5rem;
            }
            .panel-middle { margin: 1.5rem 0; }
            .panel-headline { font-size: 1.9rem; }
            .panel-headline-ar { font-size: 1.7rem; }
            .panel-stats { display: none; }
            .right-panel { padding: 2rem 1.25rem 3rem; }
            .login-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

<!-- ── LEFT PANEL ──────────────────────────────────── -->
<div class="left-panel">
    <div class="panel-stripe"></div>

    <div class="panel-top">
        <div class="panel-logo">
            <img src="{{ asset(config('society.logo')) }}" alt="{{ config('society.name.en') }}">
            <div class="panel-logo-name">
                <span class="en">{{ config('society.name.en') }}</span>
                <span class="ar">{{ config('society.name.ar') }}</span>
                <span><span class="en">Admin Portal</span><span class="ar">بوابة الإدارة</span></span>
            </div>
        </div>
    </div>

    <div class="panel-middle">
        <p class="panel-tagline"><span class="en">Staff Access</span><span class="ar">وصول الموظفين</span></p>
        <h1 class="panel-headline">Welcome<br>back to the<br>dashboard.</h1>
        <h1 class="panel-headline-ar">مرحباً بعودتك<br>إلى لوحة<br>التحكم.</h1>
        <p class="panel-desc">Manage programs, projects, media, and site content from one place — securely and efficiently.</p>
        <p class="panel-desc-ar">إدارة البرامج والمشاريع والوسائط ومحتوى الموقع من مكان واحد — بأمان وكفاءة.</p>
        <div class="panel-stats">
            <div>
                <span class="pstat-num">8,500+</span>
                <span class="pstat-label"><span class="en">Beneficiaries</span><span class="ar">مستفيد</span></span>
            </div>
            <div>
                <span class="pstat-num">32</span>
                <span class="pstat-label"><span class="en">Active Projects</span><span class="ar">مشروع نشط</span></span>
            </div>
            <div>
                <span class="pstat-num">25+</span>
                <span class="pstat-label"><span class="en">Years</span><span class="ar">عاماً</span></span>
            </div>
        </div>
    </div>

    <div class="panel-bottom">
        <span class="en">© {{ date('Y') }} {{ config('society.name.en') }}. All rights reserved.</span>
        <span class="ar">© {{ date('Y') }} {{ config('society.name.ar') }}. جميع الحقوق محفوظة.</span>
    </div>
</div>

<!-- ── RIGHT PANEL / LOGIN CARD ────────────────────── -->
<div class="right-panel">
    <div class="login-card">
        <div class="card-accent"></div>

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

        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" id="loginError" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form id="loginForm" method="POST" action="{{route('login')}}" novalidate>
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="email">
                    <span class="en">Email address</span>
                    <span class="ar">البريد الإلكتروني</span>
                </label>
                <div class="input-wrap">
                    <input
                        class="form-input"
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="you@example.com"
                    >
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password">
                    <span class="en">Password</span>
                    <span class="ar">كلمة المرور</span>
                </label>
                <div class="input-wrap">
                    <input
                        class="form-input"
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        placeholder="••••••••"
                    >
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <button type="button" class="pwd-toggle" id="pwdToggle" aria-label="Show password">
                        <svg id="eyeShow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="eyeHide" style="display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember me + Forgot password -->
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

        <div class="divider">
            <span class="en">or switch language</span>
            <span class="ar">أو تغيير اللغة</span>
        </div>

        <div class="lang-row">
            <a href="?lang=en" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" id="btn-en">English</a>
            <a href="?lang=ar" class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}" id="btn-ar">العربية</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('pwdToggle').addEventListener('click', function () {
        var input = document.getElementById('password');
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        document.getElementById('eyeShow').style.display = show ? 'none' : '';
        document.getElementById('eyeHide').style.display = show ? '' : 'none';
    });
</script>

</body>
</html>
