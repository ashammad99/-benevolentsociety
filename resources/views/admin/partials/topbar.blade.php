{{--
    BSD Admin — Topbar Partial
    resources/views/admin/partials/topbar.blade.php
--}}
<header class="topbar">

    {{-- Sidebar toggle --}}
    <button class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <svg viewBox="0 0 24 24">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>

    {{-- Breadcrumb --}}
    <div class="topbar-breadcrumb">
        <span><span class="en">Admin</span><span class="ar">الإدارة</span></span>
        <span class="bc-sep">›</span>
        <span class="bc-current">
            @yield('breadcrumb', '<span class="en">Dashboard</span><span class="ar">لوحة التحكم</span>')
        </span>
    </div>

    {{-- Right side --}}
    <div class="topbar-right">

        {{-- Search form (searches posts) --}}
        <form method="GET" action="{{ route('admin.posts.index') }}" class="topbar-search" role="search">
            <svg viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="q"
                   value="{{ request('q') }}"
                   placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث…' : 'Search…' }}"
                   autocomplete="off">
        </form>

        {{-- Notifications (placeholder — wire to a real notification system) --}}
        <button class="icon-btn" onclick="showToast('No new notifications.','info')" aria-label="Notifications">
            <svg viewBox="0 0 24 24">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="notif-dot"></span>
        </button>

        {{-- Language toggle --}}
        <div class="topbar-lang">
            <a class="tl-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"
               href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">EN</a>
            <a class="tl-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
               href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}">عر</a>
        </div>

        {{-- User avatar + dropdown --}}
        <div class="dropdown">
            <div class="topbar-avatar" data-dropdown
                 title="{{ auth()->user()->name }} — {{ ucfirst(auth()->user()->role) }}">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="dropdown-menu">
                {{-- Profile --}}
                <button class="dropdown-item" disabled>
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <div style="padding:4px 14px 8px;font-size:11.5px;color:var(--muted);">
                    {{ auth()->user()->email }}
                </div>

                @if(auth()->user()->isAdmin())
                <button class="dropdown-item" onclick="sidebarNav('settings')">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M12 2v2m0 16v2m-7.07-3.93 1.41-1.41m9.9-9.9 1.41-1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m9.9-9.9 1.41-1.41"/>
                    </svg>
                    <span class="en">Settings</span><span class="ar">الإعدادات</span>
                </button>
                @endif

                <div class="dropdown-divider"></div>

                {{-- Logout --}}
                <button class="dropdown-item danger"
                        onclick="document.getElementById('logoutForm').submit()">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span class="en">Logout</span><span class="ar">تسجيل الخروج</span>
                </button>
            </div>
        </div>

    </div>{{-- /topbar-right --}}
</header>
