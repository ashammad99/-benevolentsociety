{{--
    BSD Public Site — Shared Layout
    resources/views/layouts/public.blade.php

    Phase 4: extracted from the near-identical <style>/<nav>/<footer>/<script>
    blocks that were duplicated byte-for-byte across index.blade.php,
    contact.html, vision.html, and the six prog-*.html pages. Visual design
    is preserved exactly (same CSS, same markup) — only the duplication and
    the broken `{'active' if ... else ''}` (non-Blade/Jinja-style) nav-active
    logic are fixed, replaced with a real Blade $activeNav check.

    Pages extend this with @section('active_nav', '...') and @section('content').
--}}
@php
    $__navPrograms = ['rehab', 'education', 'vocational', 'social', 'homecare', 'devices'];
    $__active = trim($__env->yieldContent('active_nav'));
    $__settingEmail   = \App\Models\Setting::get('email')   ?: 'info@bsd-nablus.ps';
    $__settingPhone   = \App\Models\Setting::get('phone')   ?: '+970 9 XXX XXXX';
    $__settingAddrEn  = \App\Models\Setting::get('address', 'en') ?: 'Nablus, West Bank, Palestine';
    $__settingAddrAr  = \App\Models\Setting::get('address', 'ar') ?: 'نابلس، الضفة الغربية، فلسطين';
    $__socialLinks = [
        'facebook'  => \App\Models\Setting::get('facebook'),
        'instagram' => \App\Models\Setting::get('instagram'),
        'twitter'   => \App\Models\Setting::get('twitter'),
        'youtube'   => \App\Models\Setting::get('youtube'),
        'linkedin'  => \App\Models\Setting::get('linkedin'),
    ];
    $__socialLinks = array_filter($__socialLinks);
    $__metaTitle = trim($__env->yieldContent('meta_title')) ?: (\App\Models\Setting::get('meta_title', 'en') ?: 'Benevolent Society for Disabled');
    $__metaDesc  = trim($__env->yieldContent('meta_description')) ?: (\App\Models\Setting::get('meta_description', 'en') ?: 'A registered local NGO serving persons with disabilities and their families in Nablus and the West Bank.');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>@yield('title', 'Benevolent Society for Disabled | جمعية المعاقين الخيرية')</title>
<meta name="description" content="{{ $__metaDesc }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $__metaTitle }}">
<meta property="og:description" content="{{ $__metaDesc }}">
<meta property="og:url" content="{{ url()->current() }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Source+Sans+3:wght@300;400;500;600;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root{
  --red:#C0392B;--red-light:#FDECEA;--red-dark:#7B241C;
  --blue:#2471A3;--blue-light:#EAF2FB;--blue-dark:#1A5276;
  --green:#27AE60;--green-light:#EAFAF1;--green-dark:#1E8449;
  --purple:#8E44AD;--purple-light:#F4ECF7;--purple-dark:#6C3483;
  --gold:#D4AC0D;--gold-light:#FEFDF0;--gold-dark:#9A7D0A;
  --teal:#1ABC9C;--teal-light:#E8F8F5;--teal-dark:#148F77;
  --gray-50:#F7F7F7;--gray-100:#EEEEEE;--gray-200:#BDBDBD;
  --muted:#5E5E5E;--border:rgba(0,0,0,.09);--bg:#fff;--text:#1A1A1A;
  --font-en:'Source Sans 3',sans-serif;
  --font-ar:'Noto Naskh Arabic',serif;
  --font-display:'Playfair Display',serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--font-en);background:var(--bg);color:var(--text);font-size:16px;line-height:1.7}
a{text-decoration:none;color:inherit}

/* NAV */
nav{background:#fff;border-bottom:1px solid var(--border);padding:0 2.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:200;height:68px;box-shadow:0 1px 12px rgba(0,0,0,.06)}
.nav-logo{display:flex;align-items:center;gap:12px}
.logo-img{width:46px;height:46px;object-fit:contain;border-radius:6px}
.logo-name{font-family:var(--font-display);font-size:13px;font-weight:700;color:var(--red-dark);line-height:1.3;max-width:150px}
.logo-name-ar{font-family:var(--font-ar);font-size:14px;font-weight:700;color:var(--red-dark);line-height:1.4;max-width:170px;display:none}
.nav-menu{display:flex;gap:0;list-style:none;position:relative}
.nav-menu>li{position:relative}
.nav-menu>li>a{display:flex;align-items:center;gap:4px;padding:0 14px;height:68px;color:var(--muted);font-size:14px;font-weight:500;transition:color .2s;white-space:nowrap;cursor:pointer}
.nav-menu>li>a:hover,.nav-menu>li.active>a{color:var(--red)}
.nav-menu>li.active>a{border-bottom:2.5px solid var(--red)}
.dropdown{display:none;position:absolute;top:68px;left:0;background:#fff;border:1px solid var(--border);border-top:none;border-radius:0 0 12px 12px;box-shadow:0 8px 24px rgba(0,0,0,.1);min-width:220px;z-index:300;overflow:hidden}
.nav-menu>li:hover .dropdown{display:block}
.dropdown a{display:block;padding:10px 18px;font-size:13.5px;color:var(--muted);transition:background .15s,color .15s;border-bottom:1px solid var(--gray-50)}
.dropdown a:last-child{border-bottom:none}
.dropdown a:hover{background:var(--red-light);color:var(--red)}
.nav-right{display:flex;gap:10px;align-items:center}
.lang-btn{padding:6px 14px;border-radius:20px;border:1.5px solid var(--red);font-size:13px;cursor:pointer;background:transparent;color:var(--red);font-weight:600;transition:all .2s}
.lang-btn.active{background:var(--red);color:white}
.nav-cta{padding:9px 18px;background:var(--red);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;transition:background .2s;white-space:nowrap}
.nav-cta:hover{background:var(--red-dark)}

/* PAGE HERO */
.page-hero{background:linear-gradient(135deg,#fdeded 0%,#eaf2fb 100%);padding:4rem 2.5rem 3rem;text-align:center;position:relative;overflow:hidden}
.page-hero::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 15% 85%,rgba(192,57,43,.07),transparent 50%),radial-gradient(circle at 85% 15%,rgba(36,113,163,.07),transparent 50%);pointer-events:none}
.breadcrumb{font-size:12px;color:var(--muted);margin-bottom:1rem;display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap}
.breadcrumb a{color:var(--red);font-weight:600}
.breadcrumb span{color:var(--gray-200)}
.page-title{font-family:var(--font-display);font-size:2.6rem;font-weight:700;color:var(--red-dark);line-height:1.2;margin-bottom:.8rem}
.page-subtitle{font-size:1.05rem;color:var(--muted);max-width:580px;margin:0 auto}

/* SLIDER (home only) */
.hero-slider{position:relative;width:100%;height:560px;overflow:hidden;background:#111}
.slides-track{display:flex;height:100%;transition:transform .65s cubic-bezier(.77,0,.175,1);will-change:transform}
.slide{min-width:100%;height:100%;position:relative;flex-shrink:0;overflow:hidden}
.slide-bg{position:absolute;inset:0;background-size:cover;background-position:center;transform:scale(1.07);transition:transform 6.5s ease}
.slide.active .slide-bg{transform:scale(1)}
.slide-overlay{position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,.75) 0%,rgba(0,0,0,.3) 55%,rgba(0,0,0,.06) 100%)}
.slide-content{position:absolute;bottom:0;left:0;right:0;padding:2.5rem 3.5rem;display:flex;flex-direction:column;gap:.5rem;transform:translateY(18px);opacity:0;transition:transform .5s .28s ease,opacity .5s .28s ease}
.slide.active .slide-content{transform:translateY(0);opacity:1}
.slide-badge{display:inline-block;background:var(--red);color:white;font-size:11px;font-weight:700;padding:4px 13px;border-radius:20px;letter-spacing:.06em;text-transform:uppercase;width:fit-content}
.slide-title{font-family:var(--font-display);font-size:2.4rem;font-weight:700;color:white;line-height:1.2;max-width:600px}
.slide-title-ar{font-family:var(--font-ar);font-size:2rem;font-weight:700;color:white;line-height:1.35;max-width:600px;display:none}
.slide-desc{font-size:15px;color:rgba(255,255,255,.82);max-width:520px;line-height:1.65}
.slide-desc-ar{font-family:var(--font-ar);font-size:16px;color:rgba(255,255,255,.82);max-width:520px;line-height:1.7;display:none}
.slide-btn{display:inline-flex;align-items:center;gap:8px;margin-top:.5rem;padding:10px 22px;background:white;color:var(--red-dark);border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;width:fit-content;transition:background .2s;font-family:inherit}
.slide-btn:hover{background:var(--red-light)}
.slider-arrow{position:absolute;top:50%;transform:translateY(-50%);width:46px;height:46px;border-radius:50%;background:rgba(255,255,255,.14);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.28);color:white;font-size:22px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;z-index:10;user-select:none;line-height:1}
.slider-arrow:hover{background:rgba(255,255,255,.28)}
.slider-arrow.prev{left:1.5rem}
.slider-arrow.next{right:1.5rem}
.slider-dots{position:absolute;bottom:1.4rem;right:2.5rem;display:flex;gap:8px;z-index:10}
.dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.38);cursor:pointer;transition:background .25s,transform .25s}
.dot.active{background:white;transform:scale(1.35)}
.slide-counter{position:absolute;top:1.5rem;right:2.5rem;background:rgba(0,0,0,.4);color:white;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;z-index:10;letter-spacing:.05em}
.thumb-strip{display:flex;gap:5px;padding:8px 2.5rem;background:#0e0e0e;overflow-x:auto;scrollbar-width:none}
.thumb-strip::-webkit-scrollbar{display:none}
.thumb{min-width:82px;height:54px;border-radius:6px;background-size:cover;background-position:center;cursor:pointer;opacity:.42;border:2.5px solid transparent;transition:opacity .2s,border-color .2s,transform .2s;flex-shrink:0}
.thumb.active{opacity:1;border-color:var(--red);transform:scale(1.05)}

/* STATS BAR */
.stats-bar{background:var(--red-dark);padding:1.4rem 2rem;display:flex;justify-content:center;gap:4rem;flex-wrap:wrap}
.stat-num{font-family:var(--font-display);font-size:2rem;font-weight:700;color:white}
.stat-label{font-size:12px;color:rgba(255,255,255,.62);font-weight:500;margin-top:1px;text-align:center}

/* LAYOUT */
.section-wrap{padding:4.5rem 2.5rem;max-width:1120px;margin:0 auto}
.section-label{font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.12em;text-transform:uppercase;margin-bottom:.5rem}
.section-title{font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--red-dark);margin-bottom:1rem;line-height:1.25}
.section-sub{font-size:15px;color:var(--muted);max-width:560px;line-height:1.65}
.tag{padding:3px 11px;border-radius:20px;font-size:12px;font-weight:600;display:inline-block}

/* PROGRAM CARDS */
.programs-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem;margin-top:2.5rem}
.program-card{border:1px solid var(--border);border-radius:16px;overflow:hidden;background:var(--bg);transition:transform .2s,box-shadow .2s;cursor:pointer}
.program-card:hover{transform:translateY(-4px);box-shadow:0 10px 32px rgba(0,0,0,.08)}
.card-bar{height:5px}
.card-body{padding:1.4rem}
.card-icon{width:46px;height:46px;border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:.85rem}
.card-icon svg{width:24px;height:24px;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.card-title{font-weight:700;font-size:15px;margin-bottom:.4rem;color:var(--text)}
.card-desc{font-size:13.5px;color:var(--muted);line-height:1.6;margin-bottom:1rem}
.card-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:var(--red)}

/* PROJECTS / LATEST POSTS */
.projects-bg{background:var(--gray-50);padding:4.5rem 2.5rem}
.projects-inner{max-width:1120px;margin:0 auto}
.projects-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(310px,1fr));gap:1.25rem;margin-top:2.5rem}
.project-card{background:var(--bg);border-radius:16px;border:1px solid var(--border);padding:1.6rem;transition:box-shadow .2s;display:block}
.project-card:hover{box-shadow:0 8px 28px rgba(0,0,0,.07)}
.project-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.85rem}
.project-year{font-size:12px;font-weight:600;color:var(--muted);background:var(--gray-50);border:1px solid var(--border);padding:3px 11px;border-radius:20px}
.project-title{font-family:var(--font-display);font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:.5rem}
.project-desc{font-size:13.5px;color:var(--muted);line-height:1.65;margin-bottom:1rem}
.progress-label{display:flex;justify-content:space-between;font-size:12px;color:var(--muted);margin-bottom:5px;font-weight:600}
.progress-bg{background:var(--gray-100);border-radius:99px;height:6px}
.progress-fill{height:100%;border-radius:99px}
.project-footer{display:flex;justify-content:space-between;align-items:center;margin-top:1.1rem;padding-top:1.1rem;border-top:1px solid var(--border)}
.beneficiaries{font-size:13px;color:var(--muted)}
.beneficiaries strong{color:var(--text);font-weight:700}

/* IMPACT */
.impact-bg{background:var(--red-dark);padding:4.5rem 2.5rem}
.impact-inner{max-width:950px;margin:0 auto;text-align:center}
.impact-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:1.4rem;margin-top:2.5rem}
.impact-card{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:1.6rem 1rem}
.impact-num{font-family:var(--font-display);font-size:2.3rem;font-weight:700;color:white}
.impact-label{font-size:13px;color:rgba(255,255,255,.62);margin-top:5px;line-height:1.4}

/* TEAM */
.team-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-top:2.5rem}
.team-card{text-align:center;padding:1.75rem 1rem;border:1px solid var(--border);border-radius:16px;background:var(--bg);transition:transform .2s,box-shadow .2s}
.team-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.07)}
.avatar{width:74px;height:74px;border-radius:50%;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700}
.team-name{font-weight:700;font-size:15px;margin-bottom:3px}
.team-role{font-size:13px;color:var(--muted)}

/* CONTACT */
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:3rem;margin-top:2.5rem}
.contact-info-card{background:var(--gray-50);border-radius:16px;padding:2rem}
.contact-info-item{display:flex;align-items:flex-start;gap:14px;padding:1rem 0;border-bottom:1px solid var(--border)}
.contact-info-item:last-child{border-bottom:none}
.contact-icon{width:42px;height:42px;border-radius:10px;background:var(--red-light);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.contact-icon svg{width:20px;height:20px;fill:none;stroke:var(--red);stroke-width:1.8;stroke-linecap:round}
.contact-label{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px}
.contact-value{font-size:15px;color:var(--text);font-weight:500}
.contact-form{background:#fff;border:1px solid var(--border);border-radius:16px;padding:2rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem}
.form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:1rem}
.form-group label{font-size:13px;font-weight:600;color:var(--text)}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:14px;font-family:inherit;color:var(--text);background:#fff;transition:border-color .2s;outline:none}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--red)}
.form-group textarea{min-height:130px;resize:vertical}
.form-submit{width:100%;padding:12px;background:var(--red);color:white;border:none;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;transition:background .2s}
.form-submit:hover{background:var(--red-dark)}

/* PROGRAM PAGE */
.program-hero{padding:4rem 2.5rem 0;max-width:1120px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center}
.program-hero-text{}
.program-badge{display:inline-block;padding:5px 16px;border-radius:20px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem}
.program-big-title{font-family:var(--font-display);font-size:2.5rem;font-weight:700;line-height:1.2;margin-bottom:1rem}
.program-lead{font-size:16px;color:var(--muted);line-height:1.7;margin-bottom:1.5rem}
.program-stats-row{display:flex;gap:2rem;margin-top:1.5rem}
.pstat{text-align:center}
.pstat-num{font-family:var(--font-display);font-size:2rem;font-weight:700}
.pstat-label{font-size:12px;color:var(--muted);font-weight:500}
.program-image{border-radius:20px;overflow:hidden;background:var(--gray-100);height:340px;background-size:cover;background-position:center}
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;margin-top:2.5rem}
.info-card{background:var(--gray-50);border-radius:14px;padding:1.5rem;border:1px solid var(--border)}
.info-card h4{font-weight:700;font-size:14px;margin-bottom:.6rem;color:var(--text)}
.info-card p{font-size:13.5px;color:var(--muted);line-height:1.6}
.services-list{margin-top:2.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1rem}
.service-item{display:flex;align-items:flex-start;gap:12px;padding:1.2rem;background:var(--gray-50);border-radius:12px;border:1px solid var(--border)}
.service-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:6px}
.service-text strong{display:block;font-size:14px;margin-bottom:3px}
.service-text span{font-size:13px;color:var(--muted)}

/* VISION PAGE */
.vision-hero-inner{max-width:800px;margin:0 auto;text-align:center;padding:4rem 2.5rem}
.vision-icon{width:80px;height:80px;border-radius:20px;background:var(--red-light);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem}
.vision-icon svg{width:40px;height:40px;fill:none;stroke:var(--red);stroke-width:1.5;stroke-linecap:round}
.aims-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.4rem;margin-top:2.5rem}
.aim-card{border-radius:16px;padding:1.75rem;border:1px solid var(--border);background:var(--bg);transition:transform .2s,box-shadow .2s}
.aim-card:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,0,0,.07)}
.aim-num{font-family:var(--font-display);font-size:2.5rem;font-weight:700;opacity:.15;margin-bottom:.5rem}
.aim-title{font-weight:700;font-size:16px;margin-bottom:.5rem;color:var(--text)}
.aim-desc{font-size:13.5px;color:var(--muted);line-height:1.65}
.values-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-top:2.5rem}
.value-card{text-align:center;padding:2rem 1rem;border-radius:16px;background:var(--bg);border:1px solid var(--border)}
.value-icon{font-size:2rem;margin-bottom:.75rem}
.value-title{font-weight:700;font-size:15px;margin-bottom:.35rem}
.value-desc{font-size:13px;color:var(--muted);line-height:1.55}

/* CONTACT STRIP */
.contact-strip{background:var(--blue-dark);padding:2.5rem 2.5rem}
.contact-strip-inner{max-width:1120px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1.5rem}
.cs-text h3{font-family:var(--font-display);font-size:1.5rem;color:white;margin-bottom:.35rem}
.cs-text p{color:rgba(255,255,255,.7);font-size:14px}
.cs-actions{display:flex;gap:10px;flex-wrap:wrap}
.btn-white{padding:11px 22px;background:white;color:var(--blue-dark);border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit}
.btn-outline-white{padding:11px 22px;background:transparent;color:white;border:2px solid rgba(255,255,255,.5);border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;transition:border-color .2s}
.btn-outline-white:hover{border-color:white}

/* AD BANNER */
.ad-banner{background:var(--gray-50);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:1.1rem 2.5rem;display:flex;align-items:center;justify-content:center;gap:1rem;text-align:center;flex-wrap:wrap}
.ad-banner img{max-height:64px;border-radius:8px}
.ad-banner .ad-text strong{color:var(--red-dark)}

/* PAGINATION */
.pg-nav{display:flex;gap:6px;align-items:center}
.pg-btn{min-width:36px;height:36px;padding:0 10px;border-radius:8px;border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;color:var(--muted);transition:all .2s}
.pg-btn:hover{border-color:var(--red);color:var(--red)}
.pg-btn.active{background:var(--red);border-color:var(--red);color:white}
.pg-btn.disabled{opacity:.4;pointer-events:none}

/* FOOTER */
footer{background:#111;color:white;padding:3rem 2.5rem 1.5rem}
.footer-inner{max-width:1120px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:3rem;padding-bottom:2rem;border-bottom:1px solid rgba(255,255,255,.1)}
.footer-brand p{color:rgba(255,255,255,.55);font-size:14px;margin-top:.85rem;max-width:280px;line-height:1.65}
.footer-col h4{font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:1rem}
.footer-col ul{list-style:none}
.footer-col ul li{margin-bottom:.55rem}
.footer-col ul li a{color:rgba(255,255,255,.65);font-size:14px;transition:color .2s}
.footer-col ul li a:hover{color:white}
.footer-logo-wrap{display:flex;align-items:center;gap:10px;margin-bottom:.85rem}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;padding-top:1.25rem;font-size:12px;color:rgba(255,255,255,.35);flex-wrap:wrap;gap:.5rem}

/* LANG */
.en{display:block}.ar{display:none}
span.en{display:inline}span.ar{display:none}
[dir="rtl"] .en{display:none!important}[dir="rtl"] .ar{display:block!important}
[dir="rtl"] span.en{display:none!important}[dir="rtl"] span.ar{display:inline!important}
[dir="rtl"] .logo-name{display:none}[dir="rtl"] .logo-name-ar{display:block}
[dir="rtl"] .slide-title{display:none}[dir="rtl"] .slide-title-ar{display:block}
[dir="rtl"] .slide-desc{display:none}[dir="rtl"] .slide-desc-ar{display:block}
[dir="rtl"] body{font-family:var(--font-ar)}
[dir="rtl"] .nav-menu{flex-direction:row-reverse}
[dir="rtl"] .slide-content{text-align:right}
[dir="rtl"] .slide-overlay{background:linear-gradient(to left,rgba(0,0,0,.75) 0%,rgba(0,0,0,.3) 55%,rgba(0,0,0,.06) 100%)}
[dir="rtl"] .slider-dots{right:auto;left:2.5rem}
[dir="rtl"] .slide-counter{right:auto;left:2.5rem}
[dir="rtl"] .slider-arrow.prev{left:auto;right:1.5rem}
[dir="rtl"] .slider-arrow.next{right:auto;left:1.5rem}
[dir="rtl"] .stats-bar,[dir="rtl"] .footer-grid,[dir="rtl"] .footer-bottom,[dir="rtl"] .contact-grid,[dir="rtl"] .contact-strip-inner{direction:rtl}
[dir="rtl"] .progress-label,[dir="rtl"] .project-header{flex-direction:row-reverse}
[dir="rtl"] .section-title,[dir="rtl"] .project-title,[dir="rtl"] .program-big-title,[dir="rtl"] .page-title{font-family:var(--font-ar)}
[dir="rtl"] .dropdown{left:auto;right:0}
[dir="rtl"] .program-hero{direction:rtl}
[dir="rtl"] .form-group label,[dir="rtl"] .form-group input,[dir="rtl"] .form-group textarea,[dir="rtl"] .form-group select{text-align:right;direction:rtl}
[dir="rtl"] .breadcrumb{flex-direction:row-reverse}
[dir="rtl"] .cs-text{text-align:right}
[dir="rtl"] .cs-text h3{font-family:var(--font-ar)}
[dir="rtl"] .contact-info-item{flex-direction:row}

@media(max-width:768px){
  nav{padding:0 1rem}
  .nav-menu{display:none}
  .hero-slider{height:400px}
  .slide-title{font-size:1.6rem}
  .slide-content{padding:1.5rem}
  .section-wrap,.projects-bg,.impact-bg{padding:3rem 1.25rem}
  .footer-grid{grid-template-columns:1fr;gap:1.5rem}
  .contact-grid,.program-hero,.form-row{grid-template-columns:1fr}
  .program-image{height:240px}
}
@stack('styles')
</style>

</head>
<body>

<nav>
  <a class="nav-logo" href="{{ route('home') }}">
    <img class="logo-img js-logo" src="" alt="BSD">
    <div>
      <div class="logo-name">Benevolent Society<br>for Disabled</div>
      <div class="logo-name-ar">جمعية المعاقين الخيرية</div>
    </div>
  </a>
  <ul class="nav-menu">
    <li class="{{ $__active === 'home' ? 'active' : '' }}"><a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a></li>
    <li class="{{ in_array($__active, $__navPrograms) ? 'active' : '' }}">
      <a><span class="en">Programs ▾</span><span class="ar">البرامج ▾</span></a>
      <div class="dropdown">
        <a href="{{ route('programs.show', 'rehab') }}"><span class="en">Medical Rehabilitation</span><span class="ar">التأهيل الطبي</span></a>
        <a href="{{ route('programs.show', 'education') }}"><span class="en">Special Education</span><span class="ar">التعليم الخاص</span></a>
        <a href="{{ route('programs.show', 'vocational') }}"><span class="en">Vocational Training</span><span class="ar">التدريب المهني</span></a>
        <a href="{{ route('programs.show', 'social') }}"><span class="en">Social Inclusion</span><span class="ar">الإدماج الاجتماعي</span></a>
        <a href="{{ route('programs.show', 'homecare') }}"><span class="en">Home-Based Care</span><span class="ar">الرعاية المنزلية</span></a>
        <a href="{{ route('programs.show', 'devices') }}"><span class="en">Assistive Devices</span><span class="ar">الأجهزة المساعدة</span></a>
      </div>
    </li>
    <li class="{{ $__active === 'news' ? 'active' : '' }}"><a href="{{ route('news.index') }}"><span class="en">News</span><span class="ar">الأخبار</span></a></li>
    <li class="{{ $__active === 'vision' ? 'active' : '' }}"><a href="{{ route('vision') }}"><span class="en">Vision &amp; Mission</span><span class="ar">رؤيتنا</span></a></li>
    <li class="{{ $__active === 'contact' ? 'active' : '' }}"><a href="{{ route('contact') }}"><span class="en">Contact</span><span class="ar">تواصل</span></a></li>
  </ul>
  <div class="nav-right">
    <a class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">EN</a>
    <a class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}">عر</a>
    <button class="nav-cta" onclick="location.href='{{ route('contact') }}'"><span class="en">Support Us</span><span class="ar">ادعمنا</span></button>
  </div>
</nav>

@yield('content')

<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo-wrap">
          <img class="logo-img js-logo" src="" alt="BSD" style="width:40px;height:40px;">
          <div style="color:white;font-weight:700;font-size:14px;line-height:1.35;">
            <span class="en">Benevolent Society for Disabled</span>
            <span class="ar">جمعية المعاقين الخيرية</span>
          </div>
        </div>
        <p><span class="en">Serving persons with disabilities and their families in Nablus and the West Bank with dignity, care, and professionalism.</span><span class="ar">نخدم ذوي الإعاقة وأسرهم في نابلس والضفة الغربية بكرامة وعناية واحترافية.</span></p>
      </div>
      <div class="footer-col">
        <h4><span class="en">Navigate</span><span class="ar">تنقل</span></h4>
        <ul>
          <li><a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a></li>
          <li><a href="{{ route('vision') }}"><span class="en">Vision &amp; Mission</span><span class="ar">رؤيتنا ومهمتنا</span></a></li>
          <li><a href="{{ route('news.index') }}"><span class="en">News</span><span class="ar">الأخبار</span></a></li>
          <li><a href="{{ route('programs.show', 'rehab') }}"><span class="en">Programs</span><span class="ar">البرامج</span></a></li>
          <li><a href="{{ route('contact') }}"><span class="en">Contact</span><span class="ar">تواصل</span></a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4><span class="en">Contact</span><span class="ar">تواصل</span></h4>
        <ul>
          <li><a href="mailto:{{ $__settingEmail }}">{{ $__settingEmail }}</a></li>
          <li><a href="tel:{{ preg_replace('/\s+/', '', $__settingPhone) }}">{{ $__settingPhone }}</a></li>
          <li><a href="{{ route('contact') }}"><span class="en">{{ $__settingAddrEn }}</span><span class="ar">{{ $__settingAddrAr }}</span></a></li>
          @if($__socialLinks)
          <li>
            @foreach($__socialLinks as $network => $url)
              <a href="{{ $url }}" target="_blank" rel="noopener">{{ ucfirst($network) }}</a>@if(!$loop->last) &middot; @endif
            @endforeach
          </li>
          @else
          <li><a href="{{ route('contact') }}"><span class="en">Facebook · Instagram</span><span class="ar">فيسبوك · إنستغرام</span></a></li>
          @endif
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© {{ date('Y') }} <span class="en">Benevolent Society for Disabled. All rights reserved.</span><span class="ar">جمعية المعاقين الخيرية. جميع الحقوق محفوظة.</span></span>
      <span><span class="en">Registered under Palestinian Authority NGO Law</span><span class="ar">مرخصة بموجب القانون الفلسطيني</span></span>
    </div>
  </div>
</footer>

<script src="{{ asset('dashboard/js/logo.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof BSD_LOGO !== 'undefined') {
        document.querySelectorAll('.js-logo').forEach(function (el) { el.src = BSD_LOGO; });
    }
});
</script>
@stack('scripts')
</body>
</html>
