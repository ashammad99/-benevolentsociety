<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::published()
            ->featured()
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestPosts = Post::published()
            ->with('category')
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('public.home', compact('featuredPosts', 'latestPosts'));
    }
}
