{{--
    BSD Admin — Edit Category
    resources/views/admin/categories/edit.blade.php

    Phase 3: real form wired to CategoryController@update / CategoryRequest,
    pre-populated from the existing $category.
--}}
@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('active_nav', 'categories')
@section('breadcrumb')<span class="en">Edit Category</span><span class="ar">تعديل فئة</span>@endsection

@section('content')

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')

    <div class="page-header">
      <div><h1 class="page-title"><span class="en">Edit Category</span><span class="ar">تعديل فئة</span></h1></div>
      <div class="page-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg><span class="en">Back</span><span class="ar">رجوع</span>
        </a>
      </div>
    </div>

    <div style="max-width:640px">
      <div class="card">
        <div class="card-header"><div class="card-title"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg><span class="en">Category Details</span><span class="ar">تفاصيل الفئة</span></div></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
          <div class="form-group">
            <label class="form-label"><span class="en">Name (English)</span><span class="ar">الاسم بالإنجليزية</span> <span class="required">*</span></label>
            <input class="form-input" type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" placeholder="e.g. Health Projects">
            @error('name_en')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label"><span class="en">Name (Arabic)</span><span class="ar">الاسم بالعربية</span> <span class="required">*</span></label>
            <input class="form-input" type="text" name="name_ar" dir="rtl" value="{{ old('name_ar', $category->name_ar) }}" placeholder="مثال: مشاريع صحية">
            @error('name_ar')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Slug</label>
            <input class="form-input" type="text" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="health-projects">
            <div class="form-hint"><span class="en">Used in URLs.</span><span class="ar">يُستخدم في الروابط.</span></div>
            @error('slug')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="card-footer" style="justify-content:flex-end">
          <a href="{{ route('admin.categories.index') }}" class="btn btn-outline"><span class="en">Cancel</span><span class="ar">إلغاء</span></a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="en">Update</span><span class="ar">تحديث</span>
          </button>
        </div>
      </div>
    </div>
    </form>

@endsection
