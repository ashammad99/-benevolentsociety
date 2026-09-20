<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // ── Index — list with filters & pagination ─────────────────────────────
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user'])->latest('published_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by title (either language)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title_en', 'like', "%{$q}%")
                    ->orWhere('title_ar', 'like', "%{$q}%");
            });
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', (bool) $request->featured);
        }

        $posts      = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name_en')->get();

        $stats = [
            'total'     => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft'     => Post::where('status', 'draft')->count(),
            'archived'  => Post::where('status', 'archived')->count(),
        ];

        return view('admin.posts.index', compact('posts', 'categories', 'stats'));
    }

    // ── Create form ───────────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::orderBy('name_en')->get();
        return view('admin.posts.create', compact('categories'));
    }

    // ── Store ─────────────────────────────────────────────────────────────
    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Handle cover image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }

        // Auto-set published_at when status changes to published
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        return redirect()
            ->route('admin.posts.show', $post)
            ->with('success', __('Post created successfully.'));
    }

    // ── Show ──────────────────────────────────────────────────────────────
    public function show(Post $post)
    {
        $post->load(['category', 'user', 'media']);
        return view('admin.posts.show', compact('post'));
    }

    // ── Edit form ─────────────────────────────────────────────────────────
    public function edit(Post $post)
    {
        $categories = Category::orderBy('name_en')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    // ── Update ────────────────────────────────────────────────────────────
    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();

        // Handle cover image replacement
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }

        // Remove old image if explicitly cleared
        if ($request->boolean('remove_image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $data['image_path'] = null;
        }

        // Auto-set published_at on first publish
        if ($data['status'] === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()
            ->route('admin.posts.show', $post)
            ->with('success', __('Post updated successfully.'));
    }

    // ── Destroy ───────────────────────────────────────────────────────────
    public function destroy(Post $post)
    {
        // Delete cover image from storage
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();  // soft delete (trashed, not permanently removed)

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('Post deleted successfully.'));
    }

    // ── Toggle featured flag ───────────────────────────────────────────────
    public function toggleFeatured(Post $post)
    {
        $post->update(['is_featured' => ! $post->is_featured]);

        return back()->with('success', __('Featured status updated.'));
    }

    // ── Toggle status (draft ↔ published) ──────────────────────────────────
    public function toggleStatus(Post $post)
    {
        $newStatus = $post->status === 'published' ? 'draft' : 'published';

        $updates = ['status' => $newStatus];
        if ($newStatus === 'published' && ! $post->published_at) {
            $updates['published_at'] = now();
        }

        $post->update($updates);

        return back()->with('success', __("Post status changed to {$newStatus}."));
    }

    // ── Bulk delete ────────────────────────────────────────────────────────
    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);

        $posts = Post::whereIn('id', $request->ids)->get();

        foreach ($posts as $post) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->delete();
        }

        return back()->with('success', __('Selected posts deleted.'));
    }
}
