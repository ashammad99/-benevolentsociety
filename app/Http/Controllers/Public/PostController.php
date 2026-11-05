<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // ── Public post listing — published only ─────────────────────────────
    public function index(Request $request)
    {
        $query = Post::published()->with('category')->latest('published_at');

        $category = null;
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $posts = $query->paginate(9)->withQueryString();

        $categories = Category::withCount(['posts' => fn ($q) => $q->published()])
            ->orderBy('name_en')
            ->get();

        return view('public.posts.index', compact('posts', 'categories', 'category'));
    }

    // ── Public post detail — published only ───────────────────────────────
    public function show(Post $post)
    {
        if ($post->status !== 'published' || ($post->published_at && $post->published_at->isFuture())) {
            abort(404);
        }

        $post->load('category');
        $post->incrementViews();

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.posts.show', compact('post', 'relatedPosts'));
    }
}
