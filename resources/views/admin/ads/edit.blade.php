{{--
    BSD Admin — Edit Ad
    resources/views/admin/ads/edit.blade.php

    Phase 3: real form wired to AdController@update / AdRequest,
    pre-populated from the existing $ad.
--}}
@extends('admin.layouts.app')

@section('title', 'Edit Ad')
@section('active_nav', 'ads')
@section('breadcrumb')<span class="en">Edit Ad</span><span class="ar">تعديل إعلان</span>@endsection

@section('content')

    <form method="POST" action="{{ route('admin.ads.update', $ad) }}" enctype="multipart/form-data" id="ad-form">
    @csrf
    @method('PUT')

    <div class="page-header">
      <div><h1 class="page-title"><span class="en">Edit Ad</span><span class="ar">تعديل إعلان</span></h1></div>
      <div class="page-actions">
        <a href="{{ route('admin.ads.index') }}" class="btn btn-outline">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg><span class="en">Back</span><span class="ar">رجوع</span>
        </a>
      </div>
    </div>

    <div class="grid-2" style="align-items:start">
      <div class="card">
        <div class="card-header">
          <div class="card-title"><svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/></svg><span class="en">Ad Content</span><span class="ar">محتوى الإعلان</span></div>
          <div class="lang-tabs"><button type="button" class="lang-tab active" onclick="switchLangTab(this,'ad')">EN</button><button type="button" class="lang-tab" onclick="switchLangTab(this,'ad')">عر</button></div>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
          <div class="bilingual-pane active" id="ad-en">
            <div class="form-group"><label class="form-label">Title (EN) <span class="required">*</span></label><input class="form-input" type="text" name="title_en" value="{{ old('title_en', $ad->title_en) }}" placeholder="Ad title in English">
              @error('title_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">Body (EN)</label><textarea class="form-textarea" name="body_en">{{ old('body_en', $ad->body_en) }}</textarea>
              @error('body_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="bilingual-pane" id="ad-ar">
            <div class="form-group"><label class="form-label">Title (AR)</label><input class="form-input" type="text" name="title_ar" dir="rtl" value="{{ old('title_ar', $ad->title_ar) }}" placeholder="عنوان الإعلان بالعربية">
              @error('title_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">Body (AR)</label><textarea class="form-textarea" name="body_ar" dir="rtl">{{ old('body_ar', $ad->body_ar) }}</textarea>
              @error('body_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="form-group"><label class="form-label">Click URL <span style="font-size:11.5px;color:var(--muted)">(optional)</span></label><input class="form-input" type="url" name="url" value="{{ old('url', $ad->url) }}" placeholder="https://">
            @error('url')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
      <div class="form-sidebar">
        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg><span class="en">Ad Settings</span><span class="ar">إعدادات</span></div></div>
          <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Type</span><span class="ar">النوع</span></label>
              <select class="form-select" name="type">
                <option value="banner" @selected(old('type', $ad->type) === 'banner')>Banner</option>
                <option value="popup" @selected(old('type', $ad->type) === 'popup')>Popup</option>
                <option value="sidebar" @selected(old('type', $ad->type) === 'sidebar')>Sidebar</option>
                <option value="inline" @selected(old('type', $ad->type) === 'inline')>Inline</option>
              </select>
              @error('type')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Status</span><span class="ar">الحالة</span></label>
              <select class="form-select" name="status">
                <option value="active" @selected(old('status', $ad->status) === 'active')>Active</option>
                <option value="paused" @selected(old('status', $ad->status) === 'paused')>Paused</option>
                <option value="expired" @selected(old('status', $ad->status) === 'expired')>Expired</option>
              </select>
              @error('status')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-row" style="margin-bottom:0">
              <div class="form-group" style="margin-bottom:0"><label class="form-label"><span class="en">Start</span><span class="ar">بداية</span></label>
                <input class="form-input" type="date" name="starts_at" value="{{ old('starts_at', optional($ad->starts_at)->format('Y-m-d')) }}"></div>
              <div class="form-group" style="margin-bottom:0"><label class="form-label"><span class="en">End</span><span class="ar">نهاية</span></label>
                <input class="form-input" type="date" name="ends_at" value="{{ old('ends_at', optional($ad->ends_at)->format('Y-m-d')) }}">
                @error('ends_at')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span class="en">Image</span><span class="ar">الصورة</span></div></div>
          <div class="card-body">
            @if($ad->image_path)
              <div style="height:120px;border-radius:8px;overflow:hidden;margin-bottom:12px">
                <img src="{{ $ad->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:10px;cursor:pointer;">
                <input type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))>
                <span class="en">Remove current image</span><span class="ar">إزالة الصورة الحالية</span>
              </label>
            @endif
            <input class="form-input" type="file" name="image" accept="image/*">
            @error('image')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="card-footer" style="justify-content:flex-end">
            <a href="{{ route('admin.ads.index') }}" class="btn btn-outline"><span class="en">Cancel</span><span class="ar">إلغاء</span></a>
            <button type="submit" class="btn btn-primary">
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              <span class="en">Update</span><span class="ar">تحديث</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    </form>

@endsection

@push('scripts')
<script>
function switchLangTab(btn, pfx) {
    var tabs = btn.closest('.lang-tabs').querySelectorAll('.lang-tab');
    tabs.forEach(function (t, i) {
        t.classList.toggle('active', t === btn);
        var p = document.getElementById(pfx + (i === 0 ? '-en' : '-ar'));
        if (p) p.classList.toggle('active', t === btn);
    });
}
</script>
@endpush
