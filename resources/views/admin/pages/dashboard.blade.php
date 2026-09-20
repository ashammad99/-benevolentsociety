<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard — BSD Admin</title>
<link rel="icon" type="image/jpeg" id="bsd-favicon">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;500;600;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{asset('dashboard/css/variables.css')}}">
<link rel="stylesheet" href="{{asset('dashboard/css/layput.css'}}css/layout.css">
<link rel="stylesheet" href="{{asset('dashboard/css/components.css')}}">
<link rel="stylesheet" href="{{asset('dashboard')}}css/pages.css">
</head>
<body data-root=".." data-active="dashboard">
<div class="admin-shell">

<!-- ═══ SIDEBAR ══════════════════════════════════════════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <img class="brand-logo js-logo" src="" alt="BSD">
    <div class="brand-text">
      <span class="brand-name en">Benevolent Society</span>
      <span class="brand-name ar">جمعية المعاقين</span>
      <span class="brand-sub en">Admin Panel</span>
      <span class="brand-sub ar">لوحة الإدارة</span>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-label"><span class="en">Main</span><span class="ar">الرئيسي</span></div>
    <button class="nav-item active" data-page="dashboard" onclick="sidebarNav('dashboard')"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg><span class="nav-label"><span class="en">Dashboard</span><span class="ar">لوحة التحكم</span></span></button>
    <div class="nav-divider"></div>
    <div class="nav-section-label"><span class="en">Content</span><span class="ar">المحتوى</span></div>
    <button class="nav-item" data-page="posts" onclick="sidebarNav('posts')"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg><span class="nav-label"><span class="en">Posts</span><span class="ar">المنشورات</span></span><span class="nav-badge">74</span></button>
    <button class="nav-item" data-page="categories" onclick="sidebarNav('categories')"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg><span class="nav-label"><span class="en">Categories</span><span class="ar">الفئات</span></span></button>
    <button class="nav-item" data-page="media" onclick="sidebarNav('media')"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span class="nav-label"><span class="en">Media</span><span class="ar">الوسائط</span></span></button>
    <button class="nav-item" data-page="ads" onclick="sidebarNav('ads')"><svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg><span class="nav-label"><span class="en">Ads</span><span class="ar">الإعلانات</span></span></button>
    <div class="nav-divider"></div>
    <div class="nav-section-label"><span class="en">Management</span><span class="ar">الإدارة</span></div>
    <button class="nav-item" data-page="users" onclick="sidebarNav('users')"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg><span class="nav-label"><span class="en">Users</span><span class="ar">المستخدمون</span></span></button>
    <button class="nav-item" data-page="settings" onclick="sidebarNav('settings')"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg><span class="nav-label"><span class="en">Settings</span><span class="ar">الإعدادات</span></span></button>
    <div class="nav-divider"></div>
    <button class="nav-item" style="color:rgba(255,255,255,0.5);"
      onclick="window.location.href='../login.html'">
      <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      <span class="nav-label"><span class="en">Logout</span><span class="ar">تسجيل الخروج</span></span>
    </button>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar">D</div>
      <div class="user-info">
        <div class="user-name">Dana</div>
        <div class="user-role"><span class="en">Administrator</span><span class="ar">مسؤول</span></div>
      </div>
      <svg class="user-chevron" viewBox="0 0 24 24" width="14" height="14" fill="none"
        stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
    </div>
  </div>
</aside>

<!-- ═══ MAIN ══════════════════════════════════════════════════ -->
<div class="main-wrap" id="mainWrap">

  <!-- Topbar -->
  <header class="topbar">
    <button class="topbar-toggle" id="sidebarToggle">
      <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <div class="topbar-breadcrumb">
      <span><span class="en">Admin</span><span class="ar">الإدارة</span></span>
      <span class="bc-sep">›</span>
      <span class="bc-current">
        <span class="en">Dashboard</span><span class="ar">لوحة التحكم</span>
      </span>
    </div>
    <div class="topbar-right">
      <div class="topbar-search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search… / ابحث…">
      </div>
      <button class="icon-btn" onclick="showToast('No new notifications.','info')">
        <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="notif-dot"></span>
      </button>
      <div class="topbar-lang">
        <button class="tl-btn active" id="tl-en" onclick="setLang('en')">EN</button>
        <button class="tl-btn"        id="tl-ar" onclick="setLang('ar')">عر</button>
      </div>
      <div class="dropdown">
        <div class="topbar-avatar" data-dropdown>D</div>
        <div class="dropdown-menu">
          <button class="dropdown-item">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="en">Profile</span><span class="ar">ملفي</span>
          </button>
          <div class="dropdown-divider"></div>
          <button class="dropdown-item danger" onclick="window.location.href='../login.html'">
            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span class="en">Logout</span><span class="ar">خروج</span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Page content -->
  <main class="content">
<div class="page-header">
  <div>
    <h1 class="page-title"><span class="en">Dashboard</span><span class="ar">لوحة التحكم</span></h1>
    <p class="page-subtitle">
      <span class="en">Welcome back, Dana — here's what's happening today.</span>
      <span class="ar">مرحباً بعودتك، دانا — إليك ما يحدث اليوم.</span>
    </p>
  </div>
  <div class="page-actions">
    <button class="btn btn-outline"><svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg><span class="en">Export</span><span class="ar">تصدير</span></button>
    <button class="btn btn-primary" onclick="window.location.href='../posts/create.html'"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg><span class="en">New Post</span><span class="ar">منشور جديد</span></button>
  </div>
</div>

<div class="stats-row">
  <div class="stat-card red">
    <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
    <span class="stat-trend up"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>12%</span></div>
    <div class="stat-value">8,547</div>
    <div class="stat-label"><span class="en">Total Beneficiaries</span><span class="ar">إجمالي المستفيدين</span></div>
    <div class="stat-sub"><strong>+92</strong> <span class="en">added this month</span><span class="ar">هذا الشهر</span></div>
  </div>
  <div class="stat-card blue">
    <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
    <span class="stat-trend up"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>5%</span></div>
    <div class="stat-value">74</div>
    <div class="stat-label"><span class="en">Total Posts</span><span class="ar">إجمالي المنشورات</span></div>
    <div class="stat-sub"><strong>3</strong> <span class="en">drafts pending</span><span class="ar">مسودات معلّقة</span></div>
  </div>
  <div class="stat-card green">
    <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
    <span class="stat-trend up"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>8%</span></div>
    <div class="stat-value">32</div>
    <div class="stat-label"><span class="en">Active Projects</span><span class="ar">المشاريع النشطة</span></div>
    <div class="stat-sub"><strong>4</strong> <span class="en">near completion</span><span class="ar">قرب الاكتمال</span></div>
  </div>
  <div class="stat-card amber">
    <div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
    <span class="stat-trend down"><svg viewBox="0 0 24 24"><polyline points="18 9 12 15 6 9"/></svg>3%</span></div>
    <div class="stat-value">856</div>
    <div class="stat-label"><span class="en">Media Files</span><span class="ar">ملفات الوسائط</span></div>
    <div class="stat-sub"><strong>24 MB</strong> <span class="en">used this week</span><span class="ar">هذا الأسبوع</span></div>
  </div>
</div>

<div class="grid-2">
  <!-- Recent Posts -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span class="en">Recent Posts</span><span class="ar">المنشورات الأخيرة</span></div>
      <button class="btn btn-ghost btn-sm" onclick="window.location.href='../posts/index.html'"><span class="en">View all →</span><span class="ar">← الكل</span></button>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr>
          <th><span class="en">Post</span><span class="ar">المنشور</span></th>
          <th><span class="en">Status</span><span class="ar">الحالة</span></th>
          <th><span class="en">Date</span><span class="ar">التاريخ</span></th>
          <th></th>
        </tr></thead>
        <tbody>
          <tr>
            <td><div class="post-title-cell"><div class="post-thumb" style="background:var(--red-light)"><svg viewBox="0 0 24 24" stroke="var(--red)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div><div class="post-title-text"><span class="en">Fuel for Gaza Hospital</span><span class="ar">وقود لمستشفيات غزة</span></div><div class="post-meta">Dana · Health</div></div></div></td>
            <td><span class="badge badge-published"><span class="en">Published</span><span class="ar">منشور</span></span></td>
            <td style="color:var(--muted);font-size:12.5px;">Sep 6, 2024</td>
            <td><div class="row-actions"><button class="row-action" onclick="window.location.href='../posts/edit.html'"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></div></td>
          </tr>
          <tr>
            <td><div class="post-title-cell"><div class="post-thumb" style="background:var(--blue-light)"><svg viewBox="0 0 24 24" stroke="var(--blue)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div><div class="post-title-text"><span class="en">Outreach Programme</span><span class="ar">برنامج التواصل</span></div><div class="post-meta">Ibrahim · Disabilities</div></div></div></td>
            <td><span class="badge badge-draft"><span class="en">Draft</span><span class="ar">مسودة</span></span></td>
            <td style="color:var(--muted);font-size:12.5px;">Jan 14, 2024</td>
            <td><div class="row-actions"><button class="row-action" onclick="window.location.href='../posts/edit.html'"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></div></td>
          </tr>
          <tr>
            <td><div class="post-title-cell"><div class="post-thumb" style="background:var(--amber-light)"><svg viewBox="0 0 24 24" stroke="var(--amber)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div><div class="post-title-text"><span class="en">COVID-19 & Disabilities</span><span class="ar">كوفيد-19 وذوو الإعاقة</span></div><div class="post-meta">Dana · Health</div></div></div></td>
            <td><span class="badge badge-archived"><span class="en">Archived</span><span class="ar">مؤرشف</span></span></td>
            <td style="color:var(--muted);font-size:12.5px;">Jul 19, 2023</td>
            <td><div class="row-actions"><button class="row-action" onclick="window.location.href='../posts/edit.html'"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Activity -->
  <div class="card">
    <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span class="en">Recent Activity</span><span class="ar">النشاط الأخير</span></div></div>
    <div class="activity-item"><div class="activity-dot ad-blue"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div><div class="activity-msg"><strong>Dana</strong> <span class="en">published a new post</span><span class="ar">نشرت منشوراً</span></div><div class="activity-time">2h ago</div></div></div>
    <div class="activity-item"><div class="activity-dot ad-green"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><div><div class="activity-msg"><strong>Staff 3</strong> <span class="en">updated settings</span><span class="ar">حدّث الإعدادات</span></div><div class="activity-time">5h ago</div></div></div>
    <div class="activity-item"><div class="activity-dot ad-amber"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div><div><div class="activity-msg"><strong>Ibrahim</strong> <span class="en">uploaded 3 media files</span><span class="ar">رفع 3 ملفات</span></div><div class="activity-time"><span class="en">Yesterday</span><span class="ar">أمس</span></div></div></div>
    <div class="activity-item"><div class="activity-dot ad-red"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div><div><div class="activity-msg"><span class="en">System flagged 1 post for review</span><span class="ar">النظام وضع علامة على منشور</span></div><div class="activity-time"><span class="en">Yesterday</span><span class="ar">أمس</span></div></div></div>
    <div class="activity-item"><div class="activity-dot ad-purple"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></div><div><div class="activity-msg"><strong>Dana</strong> <span class="en">added category "Elders"</span><span class="ar">أضافت فئة "كبار السن"</span></div><div class="activity-time">3d ago</div></div></div>
  </div>
</div>

<div class="grid-2">
  <!-- Progress -->
  <div class="card">
    <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg><span class="en">Program Progress</span><span class="ar">تقدم البرامج</span></div><span style="font-size:11px;color:var(--muted)">2024</span></div>
    <div style="padding:20px;">
      <div class="progress-item"><div class="progress-label-row"><span><span class="en">Medical Rehabilitation</span><span class="ar">التأهيل الطبي</span></span><span>68%</span></div><div class="progress-track"><div class="progress-fill" style="width:68%;background:var(--red)"></div></div></div>
      <div class="progress-item"><div class="progress-label-row"><span><span class="en">Special Education</span><span class="ar">التعليم الخاص</span></span><span>81%</span></div><div class="progress-track"><div class="progress-fill" style="width:81%;background:var(--blue)"></div></div></div>
      <div class="progress-item"><div class="progress-label-row"><span><span class="en">Vocational Training</span><span class="ar">التدريب المهني</span></span><span>55%</span></div><div class="progress-track"><div class="progress-fill" style="width:55%;background:var(--green)"></div></div></div>
      <div class="progress-item"><div class="progress-label-row"><span><span class="en">Social Inclusion</span><span class="ar">الإدماج الاجتماعي</span></span><span>72%</span></div><div class="progress-track"><div class="progress-fill" style="width:72%;background:var(--purple)"></div></div></div>
      <div class="progress-item"><div class="progress-label-row"><span><span class="en">Home-Based Care</span><span class="ar">الرعاية المنزلية</span></span><span>40%</span></div><div class="progress-track"><div class="progress-fill" style="width:40%;background:var(--amber)"></div></div></div>
    </div>
  </div>
  <!-- Quick actions -->
  <div class="card">
    <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg><span class="en">Quick Actions</span><span class="ar">إجراءات سريعة</span></div></div>
    <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
      <button class="btn btn-primary" style="justify-content:flex-start" onclick="window.location.href='../posts/create.html'"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg><span class="en">New Post</span><span class="ar">منشور جديد</span></button>
      <button class="btn btn-outline" style="justify-content:flex-start" onclick="window.location.href='../media/index.html'"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/></svg><span class="en">Upload Media</span><span class="ar">رفع وسائط</span></button>
      <button class="btn btn-outline" style="justify-content:flex-start" onclick="window.location.href='../categories/create.html'"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg><span class="en">Add Category</span><span class="ar">فئة جديدة</span></button>
      <button class="btn btn-outline" style="justify-content:flex-start" onclick="window.location.href='../users/create.html'"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg><span class="en">Add User</span><span class="ar">مستخدم جديد</span></button>
      <button class="btn btn-outline" style="justify-content:flex-start" onclick="window.location.href='../settings/index.html'"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg><span class="en">Site Settings</span><span class="ar">إعدادات الموقع</span></button>
    </div>
  </div>
</div>

  </main>

</div><!-- /main-wrap -->
</div><!-- /admin-shell -->

<script src="../../../../public/dashboard/js/logo.js"></script>
<script src="../../../../public/dashboard/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.js-logo').forEach(function(el) {
    if (typeof BSD_LOGO !== 'undefined') el.src = BSD_LOGO;
  });
  var fav = document.getElementById('bsd-favicon');
  if (fav && typeof BSD_LOGO !== 'undefined') fav.href = BSD_LOGO;

  // Highlight active nav item
  var active = document.body.getAttribute('data-active');
  document.querySelectorAll('.nav-item[data-page]').forEach(function(el) {
    if (el.getAttribute('data-page') === active) el.classList.add('active');
  });
});

// Sidebar nav (depth-aware)
function sidebarNav(page) {
  var root = '..';
  var map = {
    dashboard:  root+'/pages/AdminDashboard.html',
    posts:      root+'/pages/posts/index.html',
    categories: root+'/pages/categories/index.html',
    media:      root+'/pages/media/index.html',
    ads:        root+'/pages/ads/index.html',
    users:      root+'/pages/users/index.html',
    settings:   root+'/pages/settings/index.html',
  };
  if (map[page]) window.location.href = map[page];
}


</script>
</body></html>
