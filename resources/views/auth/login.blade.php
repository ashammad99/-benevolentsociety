<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" id="page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Benevolent Society for Disabled</title>
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
            <img src="data:image/jpeg;base64,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gloABx9FP+yHS1qDQ29Gqd1NeXO1Xc3J8zreIw5z2vklEhlcHNAYQMtAGfP0UC7+6TtG2XVoNZa+tTanSeoZBUgFpcwh8fY4H0yHhxwraRj42745rW7ha62jpn1AcW77gCOe744XT1TsfojRm3lPuRtjupUS6gtHZU94rmggjBcWM/E08cZz+qslt11WWCPp4odztf1T5bnTRup6qCCLDqmoa5wYG/wguaATzx83HgKAdf6c6RbrdKa06c1FWUNfdsM7re4yQxudjtDw0gDz6leJ1QbX3LZrbbSOg/vE1Xbp53zSVJbgSShziBgfxAOHqfKiHyRgloVGir662780AywNweJA6FSfpzri3b1Lcqi/WvZ2Cr0jTyEuMLZjVfC88SZ7CQPPyfoo43N3houovqL26t1FSTwWeCuoaV9JIcvZJJLmYO8cjIB8eArndPdhslj2T07RUUEAppKAPmJwRJnOS4+vHCpfsFZbHqXrar3U0LfuFFXV1dShngOjJcwj9cKZ3aAtBPFZOsNwe2lilm3xMQSMYxwOPLVbHURFfLoaIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiLydT6T03rO0y2PVNmpbnQzAh8M7MjkYyD5aefIIK9ZEUHNDxuuGQou0j0xbFaFvtPqXTG31HS3GlJMEz55pvhn3DZHuGfY448hdjf7Ze2756Am0hWVrqKqilFXQ1TR/k52tcB3cZ7SHHOOfB5xgySilLQRhW7qOAxOg3AGniAMKldg2E6xdNabq9vrbru2x2KOOSOGUVALns/stJb3/N4+bA5XqdEWwWudtNWat1FuBYaigqA1tFRSVJa8zhzsve1wPIwxuT6935q4CKmIGhwdlYqn2dpaadk7S4lnDJyEREVZZ5ERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERERf//Z" alt="BSD Logo">
            <div class="panel-logo-name">
                <span class="en">Benevolent Society<br>for Disabled</span>
                <span class="ar">جمعية المعاقين الخيرية</span>
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
        <span class="en">© 2024 Benevolent Society for Disabled. All rights reserved.</span>
        <span class="ar">© 2024 جمعية المعاقين الخيرية. جميع الحقوق محفوظة.</span>
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
