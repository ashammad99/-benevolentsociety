{{--
    BSD Admin — Sidebar Partial
    resources/views/admin/partials/sidebar.blade.php

    Active nav item is driven by the @yield('active_nav') value set in each page,
    which is echoed onto <body data-active="...">.
    JS in the layout then adds the .active class to the matching [data-page] button.
--}}
<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <img class="brand-logo js-logo" src="{{ asset('dashboard/img/logo.jpg') }}" alt="BSD">
        <div class="brand-text">
            <span class="brand-name en">Benevolent Society</span>
            <span class="brand-name ar">جمعية المعاقين</span>
            <span class="brand-sub en">Admin Panel</span>
            <span class="brand-sub ar">لوحة الإدارة</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav" aria-label="Admin navigation">

        <div class="nav-section-label">
            <span class="en">Main</span><span class="ar">الرئيسي</span>
        </div>

        <button class="nav-item" data-page="dashboard"
                data-label="Dashboard"
                onclick="sidebarNav('dashboard')">
            <svg viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span class="nav-label">
                <span class="en">Dashboard</span><span class="ar">لوحة التحكم</span>
            </span>
        </button>

        <div class="nav-divider"></div>
        <div class="nav-section-label">
            <span class="en">Content</span><span class="ar">المحتوى</span>
        </div>

        <button class="nav-item" data-page="posts"
                data-label="Posts"
                onclick="sidebarNav('posts')">
            <svg viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            <span class="nav-label">
                <span class="en">Posts</span><span class="ar">المنشورات</span>
            </span>
            @php $postCount = \App\Models\Post::where('status','published')->count(); @endphp
            @if($postCount)
                <span class="nav-badge">{{ $postCount }}</span>
            @endif
        </button>

        <button class="nav-item" data-page="categories"
                data-label="Categories"
                onclick="sidebarNav('categories')">
            <svg viewBox="0 0 24 24">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
            <span class="nav-label">
                <span class="en">Categories</span><span class="ar">الفئات</span>
            </span>
        </button>

        <button class="nav-item" data-page="media"
                data-label="Media"
                onclick="sidebarNav('media')">
            <svg viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span class="nav-label">
                <span class="en">Media</span><span class="ar">الوسائط</span>
            </span>
        </button>

        <button class="nav-item" data-page="ads"
                data-label="Ads"
                onclick="sidebarNav('ads')">
            <svg viewBox="0 0 24 24">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            <span class="nav-label">
                <span class="en">Ads</span><span class="ar">الإعلانات</span>
            </span>
        </button>

        <div class="nav-divider"></div>
        <div class="nav-section-label">
            <span class="en">Management</span><span class="ar">الإدارة</span>
        </div>

        @if(auth()->user()->isAdmin())
        <button class="nav-item" data-page="users"
                data-label="Users"
                onclick="sidebarNav('users')">
            <svg viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span class="nav-label">
                <span class="en">Users</span><span class="ar">المستخدمون</span>
            </span>
        </button>

        <button class="nav-item" data-page="settings"
                data-label="Settings"
                onclick="sidebarNav('settings')">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span class="nav-label">
                <span class="en">Settings</span><span class="ar">الإعدادات</span>
            </span>
        </button>
        @endif

        <div class="nav-divider"></div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
            @csrf
            <button type="submit" class="nav-item" style="color:rgba(255,255,255,0.5);" data-label="Logout">
                <svg viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span class="nav-label">
                    <span class="en">Logout</span><span class="ar">تسجيل الخروج</span>
                </span>
            </button>
        </form>

    </nav>

    {{-- Logged-in user --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">
                    <span class="en">{{ ucfirst(auth()->user()->role) }}</span>
                    <span class="ar">
                        @switch(auth()->user()->role)
                            @case('admin')  مسؤول  @break
                            @case('editor') محرر   @break
                            @default        مشاهد
                        @endswitch
                    </span>
                </div>
            </div>
            <svg class="user-chevron" viewBox="0 0 24 24" width="14" height="14"
                 fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </div>
    </div>

</aside>
