{{--
    BSD Admin — Media Library
    resources/views/admin/media/index.blade.php

    Phase 3: rewired to the shared admin.layouts.app layout and real data
    from MediaController@index ($media, $stats). Upload, per-item edit and
    delete, and bulk delete all post to the existing MediaController routes.
--}}
@extends('admin.layouts.app')

@section('title', 'Media Library')
@section('active_nav', 'media')
@section('breadcrumb')<span class="en">Media Library</span><span class="ar">مكتبة الوسائط</span>@endsection

@section('content')

    @php $canManage = auth()->user()->isEditor(); @endphp

    <div class="page-header">
      <div>
        <h1 class="page-title"><span class="en">Media Library</span><span class="ar">مكتبة الوسائط</span></h1>
        <p class="page-subtitle"><span class="en">Upload and manage images, documents, and other files.</span><span class="ar">رفع وإدارة الصور والمستندات.</span></p>
      </div>
      <div class="page-actions">
        <form method="GET" action="{{ route('admin.media.index') }}" style="display:contents">
          <select class="filter-select" name="type" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="image" @selected(request('type') === 'image')>Images</option>
            <option value="document" @selected(request('type') === 'document')>Documents</option>
            <option value="video" @selected(request('type') === 'video')>Videos</option>
            <option value="audio" @selected(request('type') === 'audio')>Audio</option>
            <option value="other" @selected(request('type') === 'other')>Other</option>
          </select>
          @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
        </form>
        @if($canManage)
        <button type="button" class="btn btn-primary" onclick="openModal('upload-modal')">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span class="en">Upload Files</span><span class="ar">رفع ملفات</span>
        </button>
        @endif
      </div>
    </div>

    <div class="stats-row">
      <div class="stat-card amber"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div></div><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label"><span class="en">Total Files</span><span class="ar">الملفات</span></div></div>
      <div class="stat-card blue"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div></div><div class="stat-value">{{ number_format($stats['images']) }}</div><div class="stat-label"><span class="en">Images</span><span class="ar">صور</span></div></div>
      <div class="stat-card green"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div></div><div class="stat-value">{{ number_format($stats['documents']) }}</div><div class="stat-label"><span class="en">Documents</span><span class="ar">مستندات</span></div></div>
      @php
        $bytes = $stats['size_bytes'] ?? 0;
        $sizeLabel = $bytes < 1048576 ? round($bytes / 1024, 1) . ' KB' : ($bytes < 1073741824 ? round($bytes / 1048576, 1) . ' MB' : round($bytes / 1073741824, 2) . ' GB');
      @endphp
      <div class="stat-card red"><div class="stat-header"><div class="stat-icon"><svg viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg></div></div><div class="stat-value">{{ $sizeLabel }}</div><div class="stat-label"><span class="en">Storage Used</span><span class="ar">التخزين</span></div></div>
    </div>

    @if($canManage)
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.media.bulk-delete') }}">
      @csrf
    </form>
    @endif

    <div class="card">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-top:1px solid var(--border)">
        <form method="GET" action="{{ route('admin.media.index') }}" class="search-inline" style="min-width:200px">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search files…">
          @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
        </form>
        @if($canManage)
        <button type="submit" form="bulk-delete-form" class="btn btn-outline btn-sm"
                onclick="if(!document.querySelector('input[name=&quot;ids[]&quot;]:checked')){alert('Select at least one file first.');return false;} return confirm('Delete the selected files?');">
          <span class="en">Delete Selected</span><span class="ar">حذف المحدد</span>
        </button>
        @endif
      </div>

      <div class="media-grid">
        @forelse($media as $item)
        <div class="media-card">
          <div class="media-thumb" style="background:var(--blue-light);position:relative">
            @if($canManage)
            <input type="checkbox" name="ids[]" value="{{ $item->id }}" form="bulk-delete-form"
                   style="position:absolute;top:6px;inset-inline-start:6px;z-index:2;accent-color:var(--red);width:16px;height:16px;">
            @endif
            @if($item->type === 'image')
              <img src="{{ $item->url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
            @else
              <svg viewBox="0 0 24 24" stroke="var(--blue)">
                @if($item->type === 'document')<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                @elseif($item->type === 'video')<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                @elseif($item->type === 'audio')<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                @else<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                @endif
              </svg>
            @endif
          </div>
          <div class="media-info">
            <div class="media-name">{{ Str::limit($item->name, 24) }}</div>
            <div class="media-size">{{ $item->size_human }} · {{ strtoupper($item->type) }}</div>
          </div>
          @if($canManage)
          <div class="row-actions" style="padding:0 10px 10px;justify-content:flex-end">
            <button type="button" class="row-action" title="Edit" onclick="openModal('edit-media-{{ $item->id }}')">
              <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <form id="delete-media-{{ $item->id }}" method="POST" action="{{ route('admin.media.destroy', $item) }}" style="display:none">
              @csrf
              @method('DELETE')
            </form>
            <button type="button" class="row-action" title="Delete"
                    onclick="confirmDelete('Delete this file?',function(){document.getElementById('delete-media-{{ $item->id }}').submit();})">
              <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            </button>
          </div>

          {{-- Edit modal --}}
          <div class="modal-overlay" id="edit-media-{{ $item->id }}">
            <div class="modal">
              <form method="POST" action="{{ route('admin.media.update', $item) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                  <div class="modal-title"><span class="en">Edit File</span><span class="ar">تعديل الملف</span></div>
                  <button type="button" class="modal-close" onclick="closeModal('edit-media-{{ $item->id }}')">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
                <div class="modal-body" style="display:flex;flex-direction:column;gap:14px">
                  <div class="form-group" style="margin-bottom:0">
                    <label class="form-label"><span class="en">Description (English)</span></label>
                    <textarea class="form-textarea" name="description_en">{{ $item->description_en }}</textarea>
                  </div>
                  <div class="form-group" style="margin-bottom:0">
                    <label class="form-label"><span class="en">Description (Arabic)</span></label>
                    <textarea class="form-textarea" name="description_ar" dir="rtl">{{ $item->description_ar }}</textarea>
                  </div>
                  <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active)>
                    <span class="en">Active</span><span class="ar">نشط</span>
                  </label>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" onclick="closeModal('edit-media-{{ $item->id }}')"><span class="en">Cancel</span><span class="ar">إلغاء</span></button>
                  <button type="submit" class="btn btn-primary"><span class="en">Save</span><span class="ar">حفظ</span></button>
                </div>
              </form>
            </div>
          </div>
          @endif
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:32px;color:var(--muted);">
          <span class="en">No files found.</span><span class="ar">لا توجد ملفات.</span>
        </div>
        @endforelse
      </div>

      @include('admin.partials.pagination', ['paginator' => $media])
    </div>

    {{-- Upload modal --}}
    @if($canManage)
    <div class="modal-overlay @error('files') open @enderror @error('files.*') open @enderror" id="upload-modal">
      <div class="modal">
        <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <div class="modal-title"><span class="en">Upload Files</span><span class="ar">رفع ملفات</span></div>
            <button type="button" class="modal-close" onclick="closeModal('upload-modal')">
              <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
          <div class="modal-body" style="display:flex;flex-direction:column;gap:14px">
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Files</span><span class="ar">الملفات</span> <span class="required">*</span></label>
              <input class="form-input" type="file" name="files[]" multiple required
                     accept=".jpg,.jpeg,.png,.gif,.webp,.mp4,.mov,.pdf,.doc,.docx,.xls,.xlsx,.mp3,.wav">
              <div class="form-hint"><span class="en">Up to 20 files, 50 MB each.</span><span class="ar">حتى 20 ملف، 50 ميجا لكل ملف.</span></div>
              @error('files')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
              @error('files.*')<div style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Description (English)</span></label>
              <input class="form-input" type="text" name="description_en" placeholder="Optional">
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label"><span class="en">Description (Arabic)</span></label>
              <input class="form-input" type="text" name="description_ar" dir="rtl" placeholder="اختياري">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('upload-modal')"><span class="en">Cancel</span><span class="ar">إلغاء</span></button>
            <button type="submit" class="btn btn-primary">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <span class="en">Upload</span><span class="ar">رفع</span>
            </button>
          </div>
        </form>
      </div>
    </div>
    @endif

@endsection
