<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────
    public function index()
    {
        // Include soft-deleted to show trash count in UI
        $categories = Category::withCount('posts')
            ->orderBy('name_en')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    // ── Create form ────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.categories.create');
    }

    // ── Store ──────────────────────────────────────────────────────────────
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Auto-generate slug from English name if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name_en']);
        }

        // Ensure slug is unique
        $data['slug'] = $this->uniqueSlug($data['slug']);

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('Category created successfully.'));
    }

    // ── Edit form ──────────────────────────────────────────────────────────
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // ── Update ─────────────────────────────────────────────────────────────
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        // Regenerate slug if English name changed
        if ($data['name_en'] !== $category->name_en && empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug(Str::slug($data['name_en']), $category->id);
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('Category updated successfully.'));
    }

    // ── Destroy ─────────────────────────────────────────────────────────────
    public function destroy(Category $category)
    {
        // Prevent deletion if posts are still attached
        if ($category->posts()->exists()) {
            return back()->withErrors([
                'category' => __('Cannot delete this category — it still has posts assigned to it. Reassign or delete those posts first.'),
            ]);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('Category deleted successfully.'));
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $original = $slug;
        $count    = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
