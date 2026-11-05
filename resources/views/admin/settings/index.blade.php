{{--
    BSD Admin — Settings
    resources/views/admin/settings/index.blade.php

    Phase 3: rewired to the shared admin.layouts.app layout. Each tab is its
    own <form> posting to the matching SettingController@save* route, reading
    initial values from $settings (keyed by `key`) passed by
    SettingController@index. Preserves the existing key/value Settings schema.
--}}
@extends('admin.layouts.app')

@section('title', 'Settings')
@section('active_nav', 'settings')
@section('breadcrumb')<span class="en">Settings</span><span class="ar">الإعدادات</span>@endsection

@section('content')

    @php
        $get = fn (string $key, string $col = 'value') => optional($settings->get($key))->{$col};

        $tabFields = [
            'tab-contact' => ['email', 'phone', 'address_en', 'address_ar'],
            'tab-about'   => ['about_en', 'about_ar'],
            'tab-social'  => ['facebook', 'instagram', 'twitter', 'whatsapp', 'youtube', 'linkedin'],
            'tab-seo'     => ['meta_title_en', 'meta_title_ar', 'meta_description_en', 'meta_description_ar'],
        ];
        $activeTab = 'tab-contact';
        foreach ($tabFields as $tab => $fields) {
            if ($errors->hasAny($fields)) { $activeTab = $tab; break; }
        }
    @endphp

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Site Settings</span><span class="ar">إعدادات الموقع</span></h1>
        <p class="page-subtitle"><span class="en">Configure contact info, about content, social links, and SEO.</span><span class="ar">ضبط معلومات التواصل والمحتوى والروابط الاجتماعية وسيو.</span></p>
      </div>
    </div>

    <div class="card">
      <div class="settings-tabs">
        <button type="button" class="settings-tab {{ $activeTab === 'tab-contact' ? 'active' : '' }}" onclick="switchSettingsTab(this,'tab-contact')"><span class="en">Contact</span><span class="ar">التواصل</span></button>
        <button type="button" class="settings-tab {{ $activeTab === 'tab-about' ? 'active' : '' }}" onclick="switchSettingsTab(this,'tab-about')"><span class="en">About / Vision</span><span class="ar">عن الجمعية</span></button>
        <button type="button" class="settings-tab {{ $activeTab === 'tab-social' ? 'active' : '' }}" onclick="switchSettingsTab(this,'tab-social')"><span class="en">Social Media</span><span class="ar">التواصل الاجتماعي</span></button>
        <button type="button" class="settings-tab {{ $activeTab === 'tab-seo' ? 'active' : '' }}" onclick="switchSettingsTab(this,'tab-seo')">SEO</button>
      </div>

      {{-- Contact --}}
      <div class="settings-panel {{ $activeTab === 'tab-contact' ? 'active' : '' }}" id="tab-contact">
        <form method="POST" action="{{ route('admin.settings.contact') }}">
          @csrf
          @method('PUT')
          <div class="form-row">
            <div class="form-group">
              <label class="form-label"><span class="en">Contact Email</span><span class="ar">البريد الإلكتروني</span> <span class="required">*</span></label>
              <input class="form-input" type="email" name="email" value="{{ old('email', $get('email')) }}">
              @error('email')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label"><span class="en">Phone Number</span><span class="ar">رقم الهاتف</span></label>
              <input class="form-input" type="tel" name="phone" value="{{ old('phone', $get('phone')) }}">
              @error('phone')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Address (English)</span><span class="ar">العنوان (إنجليزي)</span></label>
            <textarea class="form-textarea" name="address_en" style="min-height:80px">{{ old('address_en', $get('address', 'value_en')) }}</textarea>
            @error('address_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Address (Arabic)</span><span class="ar">العنوان (عربي)</span></label>
            <textarea class="form-textarea" name="address_ar" dir="rtl" style="min-height:80px">{{ old('address_ar', $get('address', 'value_ar')) }}</textarea>
            @error('address_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span class="en">Save</span><span class="ar">حفظ</span></button>
          </div>
        </form>
      </div>

      {{-- About --}}
      <div class="settings-panel {{ $activeTab === 'tab-about' ? 'active' : '' }}" id="tab-about">
        <form method="POST" action="{{ route('admin.settings.about') }}" id="about-form">
          @csrf
          @method('PUT')
          <div style="margin-bottom:24px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
              <div style="font-weight:700;font-size:13.5px"><span class="en">Vision &amp; Aims</span><span class="ar">الرؤية والأهداف</span></div>
              <div class="lang-tabs"><button type="button" class="lang-tab active" onclick="switchLangTab(this,'about')">EN</button><button type="button" class="lang-tab" onclick="switchLangTab(this,'about')">عر</button></div>
            </div>
            <p style="font-size:12.5px;color:var(--muted);margin-bottom:12px"><span class="en">Shown on the public <a href="{{ route('vision') }}" target="_blank" rel="noopener">Vision &amp; Aims</a> page. Bold, italic and lists are kept; other formatting is removed when saved.</span><span class="ar">يظهر في صفحة <a href="{{ route('vision') }}" target="_blank" rel="noopener">رؤيتنا وأهدافنا</a> على الموقع. يُحتفظ بالخط العريض والمائل والقوائم، ويُزال أي تنسيق آخر عند الحفظ.</span></p>
            <div class="bilingual-pane active" id="about-en">
              <div class="rich-toolbar"><button type="button" class="rich-btn" onclick="execCmd('bold')"><b>B</b></button><button type="button" class="rich-btn" onclick="execCmd('italic')"><i>I</i></button><button type="button" class="rich-btn" onclick="execCmd('insertUnorderedList')">•</button><button type="button" class="rich-btn" onclick="execCmd('insertOrderedList')">1.</button></div>
              <div class="rich-editor" id="about_en_editor" contenteditable="true" data-placeholder="The society's vision and aims in English…">{!! old('about_en', $get('about_message', 'value_en')) !!}</div>
              <textarea name="about_en" id="about_en" style="display:none">{{ old('about_en', $get('about_message', 'value_en')) }}</textarea>
              @error('about_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="bilingual-pane" id="about-ar">
              <div class="rich-toolbar"><button type="button" class="rich-btn" onclick="execCmd('bold')"><b>B</b></button><button type="button" class="rich-btn" onclick="execCmd('italic')"><i>I</i></button><button type="button" class="rich-btn" onclick="execCmd('insertUnorderedList')">•</button><button type="button" class="rich-btn" onclick="execCmd('insertOrderedList')">1.</button></div>
              <div class="rich-editor" id="about_ar_editor" contenteditable="true" dir="rtl" data-placeholder="رؤية الجمعية وأهدافها بالعربية…">{!! old('about_ar', $get('about_message', 'value_ar')) !!}</div>
              <textarea name="about_ar" id="about_ar" style="display:none">{{ old('about_ar', $get('about_message', 'value_ar')) }}</textarea>
              @error('about_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
          <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span class="en">Save</span><span class="ar">حفظ</span></button>
          </div>
        </form>
      </div>

      {{-- Social --}}
      <div class="settings-panel {{ $activeTab === 'tab-social' ? 'active' : '' }}" id="tab-social">
        <form method="POST" action="{{ route('admin.settings.social') }}">
          @csrf
          @method('PUT')
          <div class="form-row">
            <div class="form-group"><label class="form-label">Facebook URL</label><input class="form-input" type="url" name="facebook" value="{{ old('facebook', $get('facebook')) }}" placeholder="https://facebook.com/…">
              @error('facebook')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">Instagram URL</label><input class="form-input" type="url" name="instagram" value="{{ old('instagram', $get('instagram')) }}" placeholder="https://instagram.com/…">
              @error('instagram')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">Twitter / X URL</label><input class="form-input" type="url" name="twitter" value="{{ old('twitter', $get('twitter')) }}" placeholder="https://x.com/…">
              @error('twitter')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">WhatsApp Number</label><input class="form-input" type="tel" name="whatsapp" value="{{ old('whatsapp', $get('whatsapp')) }}" placeholder="+970…">
              @error('whatsapp')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">YouTube Channel</label><input class="form-input" type="url" name="youtube" value="{{ old('youtube', $get('youtube')) }}" placeholder="https://youtube.com/…">
              @error('youtube')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">LinkedIn URL</label><input class="form-input" type="url" name="linkedin" value="{{ old('linkedin', $get('linkedin')) }}" placeholder="https://linkedin.com/…">
              @error('linkedin')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
          <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span class="en">Save</span><span class="ar">حفظ</span></button>
          </div>
        </form>
      </div>

      {{-- SEO --}}
      <div class="settings-panel {{ $activeTab === 'tab-seo' ? 'active' : '' }}" id="tab-seo">
        <form method="POST" action="{{ route('admin.settings.seo') }}">
          @csrf
          @method('PUT')
          <div class="form-group"><label class="form-label">Meta Title (EN)</label><input class="form-input" type="text" name="meta_title_en" value="{{ old('meta_title_en', $get('meta_title', 'value_en')) }}">
            @error('meta_title_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group"><label class="form-label">Meta Title (AR)</label><input class="form-input" type="text" name="meta_title_ar" dir="rtl" value="{{ old('meta_title_ar', $get('meta_title', 'value_ar')) }}">
            @error('meta_title_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group"><label class="form-label">Meta Description (EN)</label><textarea class="form-textarea" name="meta_description_en" style="min-height:80px">{{ old('meta_description_en', $get('meta_description', 'value_en')) }}</textarea>
            @error('meta_description_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group"><label class="form-label">Meta Description (AR)</label><textarea class="form-textarea" name="meta_description_ar" dir="rtl" style="min-height:80px">{{ old('meta_description_ar', $get('meta_description', 'value_ar')) }}</textarea>
            @error('meta_description_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span class="en">Save</span><span class="ar">حفظ</span></button>
          </div>
        </form>
      </div>
    </div>

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
function switchSettingsTab(btn, panelId) {
    btn.closest('.card').querySelectorAll('.settings-tab').forEach(function (t) { t.classList.remove('active'); });
    btn.closest('.card').querySelectorAll('.settings-panel').forEach(function (p) { p.classList.remove('active'); });
    btn.classList.add('active');
    document.getElementById(panelId).classList.add('active');
}
function execCmd(cmd) { document.execCommand(cmd, false, null); }

document.getElementById('about-form').addEventListener('submit', function () {
    document.getElementById('about_en').value = document.getElementById('about_en_editor').innerHTML;
    document.getElementById('about_ar').value = document.getElementById('about_ar_editor').innerHTML;
});
</script>
@endpush
