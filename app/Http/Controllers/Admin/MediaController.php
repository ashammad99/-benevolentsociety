<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class MediaController extends Controller
{
    // Allowed MIME types and their BSD type grouping
    private const TYPE_MAP = [
        'image/jpeg'                                                            => 'image',
        'image/png'                                                             => 'image',
        'image/gif'                                                             => 'image',
        'image/webp'                                                            => 'image',
        'video/mp4'                                                             => 'video',
        'video/mpeg'                                                            => 'video',
        'video/quicktime'                                                       => 'video',
        'application/pdf'                                                       => 'document',
        'application/msword'                                                    => 'document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
        'application/vnd.ms-excel'                                              => 'document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'    => 'document',
        'audio/mpeg'                                                            => 'audio',
        'audio/wav'                                                             => 'audio',
    ];

    // ── Index ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Media::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description_en', 'like', "%{$q}%")
                    ->orWhere('description_ar', 'like', "%{$q}%");
            });
        }

        $media = $query->paginate(30)->withQueryString();

        $stats = [
            'total'     => Media::count(),
            'images'    => Media::where('type', 'image')->count(),
            'documents' => Media::where('type', 'document')->count(),
            'videos'    => Media::where('type', 'video')->count(),
            'size_bytes'=> Media::sum('size_bytes'),
        ];

        return view('admin.media.index', compact('media', 'stats'));
    }

    // ── Upload (single or multi) ───────────────────────────────────────────
    public function upload(Request $request)
    {
        $request->validate([
            'files'              => ['required', 'array', 'min:1', 'max:20'],
            'files.*'            => [
                // No SVG: it can carry scripts and is served from our own domain.
                File::types(['jpg','jpeg','png','gif','webp','mp4','mov','pdf','doc','docx','xls','xlsx','mp3','wav'])
                    ->max(50 * 1024),  // 50 MB
            ],
            'description_en'     => ['nullable', 'string', 'max:500'],
            'description_ar'     => ['nullable', 'string', 'max:500'],
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $mime      = $file->getMimeType();
            $type      = self::TYPE_MAP[$mime] ?? 'other';
            $path      = $file->store('media', 'public');

            $media = Media::create([
                'name'           => $file->getClientOriginalName(),
                'disk'           => 'public',
                'path'           => $path,
                'mime_type'      => $mime,
                'size_bytes'     => $file->getSize(),
                'type'           => $type,
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'user_id'        => Auth::id(),
                'is_active'      => true,
            ]);

            $uploaded[] = $media;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'media'   => $uploaded,
                'message' => __(':count file(s) uploaded successfully.', ['count' => count($uploaded)]),
            ]);
        }

        return back()->with('success', __(':count file(s) uploaded successfully.', ['count' => count($uploaded)]));
    }

    // ── Show / detail (JSON only — there is no standalone detail page) ─────
    public function show(Media $media)
    {
        $media->load('user');

        return response()->json($media->append('url'));
    }

    // ── Update description / metadata ──────────────────────────────────────
    public function update(Request $request, Media $media)
    {
        $data = $request->validate([
            'description_en' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:500'],
            'is_active'      => ['boolean'],
        ]);

        $media->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'media' => $media]);
        }

        return back()->with('success', __('Media updated.'));
    }

    // ── Destroy ────────────────────────────────────────────────────────────
    public function destroy(Media $media)
    {
        // Remove file from disk
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('File deleted.'));
    }

    // ── Bulk delete ────────────────────────────────────────────────────────
    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);

        $files = Media::whereIn('id', $request->ids)->get();

        foreach ($files as $file) {
            Storage::disk($file->disk)->delete($file->path);
            $file->delete();
        }

        return back()->with('success', __(':count file(s) deleted.', ['count' => $files->count()]));
    }
}
