<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" id="page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — BSD Admin</title>

    {{-- Favicon (served from public/dashboard/) --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('dashboard/img/logo.jpg') }}">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;500;600;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Admin CSS (all files live in public/dashboard/css/) --}}
    <link rel="stylesheet" href="{{ asset('dashboard/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/pages.css') }}">

    @stack('styles')
</head>
<body data-active="@yield('active_nav', '')">

<div class="admin-shell">

    {{-- ═══ SIDEBAR ══════════════════════════════════════════ --}}
    @include('admin.partials.sidebar')

    {{-- ═══ MAIN WRAP ════════════════════════════════════════ --}}
    <div class="main-wrap" id="mainWrap">

        {{-- Topbar --}}
        @include('admin.partials.topbar')

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="flash-bar flash-success" id="flashBar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="flash-bar flash-error" id="flashBar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') ?? $errors->first() }}
                <button onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        {{-- Page content --}}
        <main class="content">
            @yield('content')
        </main>

    </div>{{-- /main-wrap --}}

</div>{{-- /admin-shell --}}

{{-- Admin JS (public/dashboard/js/) --}}
<script src="{{ asset('dashboard/js/logo.js') }}"></script>
<script src="{{ asset('dashboard/js/app.js') }}"></script>

<script>
// ── Logo injection ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    if (typeof BSD_LOGO !== 'undefined') {
        document.querySelectorAll('.js-logo').forEach(function (el) {
            el.src = BSD_LOGO;
        });
        var fav = document.querySelector('link[rel="icon"]');
        if (fav) fav.href = BSD_LOGO;
    }

    // ── Nav active highlight ──────────────────────────────
    var active = document.body.getAttribute('data-active');
    if (active) {
        document.querySelectorAll('.nav-item[data-page]').forEach(function (el) {
            el.classList.toggle('active', el.getAttribute('data-page') === active);
        });
    }
});

// ── Sidebar navigation (uses Laravel named routes) ───────
var BSD_ROUTES = {
    dashboard:  '{{ route('admin.dashboard') }}',
    posts:      '{{ route('admin.posts.index') }}',
    categories: '{{ route('admin.categories.index') }}',
    media:      '{{ route('admin.media.index') }}',
    ads:        '{{ route('admin.ads.index') }}',
    users:      '{{ route('admin.users.index') }}',
    settings:   '{{ route('admin.settings.index') }}',
};

function sidebarNav(page) {
    if (BSD_ROUTES[page]) window.location.href = BSD_ROUTES[page];
}

// ── Auto-dismiss flash bar ────────────────────────────────
var flash = document.getElementById('flashBar');
if (flash) setTimeout(function () { flash.style.opacity = '0'; setTimeout(function () { flash.remove(); }, 400); }, 4000);
</script>

{{-- Inline styles for flash bar (no extra CSS file needed) --}}
<style>
.flash-bar {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 24px; font-size: 13.5px;
    position: sticky; top: 0; z-index: 50;
    transition: opacity .4s;
}
.flash-bar svg { width: 16px; height: 16px; flex-shrink: 0; }
.flash-bar button { margin-left: auto; background: none; border: none; cursor: pointer; font-size: 18px; line-height: 1; color: inherit; opacity: .6; }
.flash-success { background: var(--green-light); color: var(--green); border-bottom: 1px solid rgba(30,132,73,.2); }
.flash-error   { background: var(--red-light);   color: var(--red);   border-bottom: 1px solid rgba(192,57,43,.2); }
</style>

@stack('scripts')
</body>
</html>
