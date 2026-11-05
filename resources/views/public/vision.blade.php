{{--
    Public Site — Vision & Aims
    resources/views/public/vision.blade.php

    Shows the organisation's own vision and aims: the about_message setting,
    imported from the old site's About Us page (see PageController::vision()).
--}}
@extends('layouts.public')

@section('title', 'Vision & Aims')
@section('active_nav', 'vision')

@section('content')

<div style="background:linear-gradient(135deg,#fdeded,#eaf2fb);">
  <div style="max-width:820px;margin:0 auto;text-align:center;padding:4rem 2.5rem 3rem;">
    <div class="breadcrumb" style="justify-content:center;">
      <a href="{{ route('home') }}"><span class="en">Home</span><span class="ar">الرئيسية</span></a>
      <span>›</span>
      <span><span class="en">Vision &amp; Aims</span><span class="ar">رؤيتنا وأهدافنا</span></span>
    </div>
    <div class="vision-icon">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
    </div>
    <div class="page-title"><span class="en">Our Vision &amp; Aims</span><span class="ar">رؤيتنا وأهدافنا</span></div>
  </div>
</div>

@if ($about['en'] || $about['ar'])
<div class="section-wrap">
  <div class="about-text">
    <div class="en">{!! $about['en'] !!}</div>
    <div class="ar">{!! $about['ar'] !!}</div>
  </div>
</div>
@endif

@endsection
