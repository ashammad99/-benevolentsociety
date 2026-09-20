<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Media;
use App\Models\Ad;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts_total'     => Post::count(),
            'posts_published' => Post::where('status', 'published')->count(),
            'posts_draft'     => Post::where('status', 'draft')->count(),
            'posts_archived'  => Post::where('status', 'archived')->count(),
            'categories'      => Category::count(),
            'media_total'     => Media::count(),
            'ads_active'      => Ad::where('status', 'active')->count(),
            'users_total'     => User::count(),
        ];

        $recent_posts = Post::with(['category', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Latest 6 activity entries (basic — replace with a proper activity log package like spatie/laravel-activitylog)
        $recent_activity = collect([
            // Pulled from auditable models in production; placeholder here
        ]);

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_activity'));
    }
}
