{{--
    BSD Admin — New Post
    resources/views/admin/posts/create.blade.php

    Phase 3 (Posts CRUD pilot): real form wired to PostController@store /
    PostRequest. Field names match PostRequest::rules() exactly.
--}}
@extends('admin.layouts.app')

@section('title', 'New Post')
@section('active_nav', 'posts')
@section('breadcrumb')<span class="en">New Post</span><span class="ar">منشور جديد</span>@endsection

@section('content')

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" id="post-form">
    @csrf

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">New Post</span><span class="ar">منشور جديد</span></h1>
        <p class="page-subtitle"><span class="en">Fill in the details below.</span><span class="ar">أدخل التفاصيل أدناه.</span></p>
      </div>
      <div class="page-actions">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg><span class="en">Back</span><span class="ar">رجوع</span>
        </a>
      </div>
    </div>

    <div class="grid-2" style="align-items:start">
      <!-- Left column: content -->
      <div style="display:flex;flex-direction:column;gap:18px">

        <!-- Title -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg><span class="en">Title</span><span class="ar">العنوان</span></div>
            <div class="lang-tabs"><button type="button" class="lang-tab active" onclick="switchLangTab(this,'title')">EN</button><button type="button" class="lang-tab" onclick="switchLangTab(this,'title')">عر</button></div>
          </div>
          <div class="card-body">
            <div class="bilingual-pane active" id="title-en">
              <input class="form-input" type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Post title in English…">
              @error('title_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="bilingual-pane" id="title-ar">
              <input class="form-input" type="text" name="title_ar" dir="rtl" value="{{ old('title_ar') }}" placeholder="عنوان المنشور بالعربية…">
              @error('title_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <!-- Excerpt -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><svg viewBox="0 0 24 24"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/></svg><span class="en">Excerpt</span><span class="ar">المقتطف</span></div>
            <div class="lang-tabs"><button type="button" class="lang-tab active" onclick="switchLangTab(this,'excerpt')">EN</button><button type="button" class="lang-tab" onclick="switchLangTab(this,'excerpt')">عر</button></div>
          </div>
          <div class="card-body">
            <div class="bilingual-pane active" id="excerpt-en">
              <textarea class="form-textarea" name="excerpt_en" placeholder="Short summary for listing pages…" style="min-height:80px">{{ old('excerpt_en') }}</textarea>
              @error('excerpt_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="bilingual-pane" id="excerpt-ar">
              <textarea class="form-textarea" name="excerpt_ar" dir="rtl" placeholder="ملخص قصير لصفحات القوائم…" style="min-height:80px">{{ old('excerpt_ar') }}</textarea>
              @error('excerpt_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><svg viewBox="0 0 24 24"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg><span class="en">Content</span><span class="ar">المحتوى</span></div>
            <div class="lang-tabs"><button type="button" class="lang-tab active" onclick="switchLangTab(this,'content')">EN</button><button type="button" class="lang-tab" onclick="switchLangTab(this,'content')">عر</button></div>
          </div>
          <div class="rich-toolbar">
            <button type="button" class="rich-btn" onclick="execCmd('bold')"><b>B</b></button>
            <button type="button" class="rich-btn" onclick="execCmd('italic')"><i>I</i></button>
            <button type="button" class="rich-btn" onclick="execCmd('underline')"><u>U</u></button>
            <button type="button" class="rich-btn" onclick="execCmd('insertUnorderedList')">•</button>
            <button type="button" class="rich-btn" onclick="execCmd('insertOrderedList')">1.</button>
          </div>
          <div class="bilingual-pane active" id="content-en">
            <div class="rich-editor" id="content_en_editor" contenteditable="true" data-placeholder="Write content in English…">{!! old('content_en') !!}</div>
            <textarea name="content_en" id="content_en" style="display:none">{{ old('content_en') }}</textarea>
            @error('content_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="bilingual-pane" id="content-ar">
            <div class="rich-editor" id="content_ar_editor" contenteditable="true" dir="rtl" data-placeholder="اكتب المحتوى بالعربية…">{!! old('content_ar') !!}</div>
            <textarea name="content_ar" id="content_ar" style="display:none">{{ old('content_ar') }}</textarea>
            @error('content_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

      <!-- Right column: settings -->
      <div class="form-sidebar">
        <!-- Publish -->
        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span class="en">Publish</span><span class="ar">النشر</span></div></div>
          <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Status</span><span class="ar">الحالة</span></label>
              <select class="form-select" name="status">
                <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                <option value="published" @selected(old('status') === 'published')>Published</option>
                <option value="archived" @selected(old('status') === 'archived')>Archived</option>
              </select>
              @error('status')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Publish Date</span><span class="ar">تاريخ النشر</span></label>
              <input class="form-input" type="datetime-local" name="published_at" value="{{ old('published_at') }}">
              @error('published_at')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="toggle-wrap">
              <input type="hidden" name="is_featured" value="{{ old('is_featured') ? '1' : '0' }}">
              <div class="toggle {{ old('is_featured') ? 'on' : '' }}"></div>
              <span style="font-size:13px"><span class="en">Featured Post</span><span class="ar">منشور مميز</span></span>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm"><span class="en">Cancel</span><span class="ar">إلغاء</span></a>
            <button type="submit" class="btn btn-primary btn-sm">
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              <span class="en">Publish</span><span class="ar">نشر</span>
            </button>
          </div>
        </div>

        <!-- Category -->
        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg><span class="en">Category</span><span class="ar">الفئة</span></div></div>
          <div class="card-body">
            <select class="form-select" name="category_id">
              <option value="">— Select —</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name_en }}</option>
              @endforeach
            </select>
            @error('category_id')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
        </div>

        <!-- Cover Image -->
        <div class="card">
          <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span class="en">Cover Image</span><span class="ar">صورة الغلاف</span></div></div>
          <div class="card-body">
            <input class="form-input" type="file" name="image" accept="image/*">
            @error('image')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
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
function execCmd(cmd) { document.execCommand(cmd, false, null); }

// Sync the contenteditable rich editors into their hidden textareas before submit.
document.getElementById('post-form').addEventListener('submit', function () {
    document.getElementById('content_en').value = document.getElementById('content_en_editor').innerHTML;
    document.getElementById('content_ar').value = document.getElementById('content_ar_editor').innerHTML;
});
</script>
@endpush
