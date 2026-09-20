<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ProgramController;
use App\Http\Controllers\Public\PostController as PublicPostController;

/*
|--------------------------------------------------------------------------
| Public site routes
|--------------------------------------------------------------------------
| Phase 4: real routes replacing the old static/unrouted resources/views/
| {index,contact,vision,prog-*}.html pages. No auth/role middleware — these
| are all public-facing.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vision', [PageController::class, 'vision'])->name('vision');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/programs/{program}', [ProgramController::class, 'show'])
    ->where('program', 'rehab|education|vocational|social|homecare|devices')
    ->name('programs.show');

Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [PublicPostController::class, 'index'])->name('index');
    Route::get('{post}', [PublicPostController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (authenticated + active account)
|--------------------------------------------------------------------------
*/

// ── Admin entry point ───────────────────────────────────────────────────
// Matches RouteServiceProvider::HOME ('/admin'), which the Breeze login
// flow (AuthenticatedSessionController::store) redirects to on success.
// Forwards to the existing named dashboard route without a second
// dashboard implementation.
Route::get('/admin', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified_active'])
    ->name('admin.home');

Route::middleware(['auth', 'verified_active'])->prefix('admin')->name('admin.')->group(function () {

    // ── Dashboard ─────────────────────────────────────────────────────────
    Route::get('/AdminDashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Posts (read: admin/editor/viewer, mutate: admin/editor) ─────────────
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/',              [PostController::class, 'index'])  ->name('index');
        Route::get('create',         [PostController::class, 'create']) ->name('create')->middleware('role:admin,editor');
        Route::post('/',             [PostController::class, 'store'])  ->name('store') ->middleware('role:admin,editor');
        Route::get('{post}',         [PostController::class, 'show'])   ->name('show');
        Route::get('{post}/edit',    [PostController::class, 'edit'])   ->name('edit')  ->middleware('role:admin,editor');
        Route::put('{post}',         [PostController::class, 'update']) ->name('update')->middleware('role:admin,editor');
        Route::delete('{post}',      [PostController::class, 'destroy'])->name('destroy')->middleware('role:admin,editor');

        // Extra actions (mutating)
        Route::patch('{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('toggle-featured')->middleware('role:admin,editor');
        Route::patch('{post}/toggle-status',   [PostController::class, 'toggleStatus'])  ->name('toggle-status')  ->middleware('role:admin,editor');
        Route::post('bulk-delete',             [PostController::class, 'bulkDelete'])    ->name('bulk-delete')    ->middleware('role:admin,editor');
    });

    // ── Categories (read: admin/editor/viewer, mutate: admin/editor) ────────
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',           [CategoryController::class, 'index'])  ->name('index');
        Route::get('create',      [CategoryController::class, 'create']) ->name('create')->middleware('role:admin,editor');
        Route::post('/',          [CategoryController::class, 'store'])  ->name('store') ->middleware('role:admin,editor');
        Route::get('{category}/edit',    [CategoryController::class, 'edit'])   ->name('edit')  ->middleware('role:admin,editor');
        Route::put('{category}',         [CategoryController::class, 'update']) ->name('update')->middleware('role:admin,editor');
        Route::delete('{category}',      [CategoryController::class, 'destroy'])->name('destroy')->middleware('role:admin,editor');
    });

    // ── Media (read: admin/editor/viewer, mutate: admin/editor) ─────────────
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/',           [MediaController::class, 'index'])  ->name('index');
        Route::post('upload',     [MediaController::class, 'upload']) ->name('upload')->middleware('role:admin,editor');   // single or multi file upload
        Route::get('{media}',     [MediaController::class, 'show'])   ->name('show');
        Route::patch('{media}',   [MediaController::class, 'update']) ->name('update')->middleware('role:admin,editor');   // update description/meta
        Route::delete('{media}',  [MediaController::class, 'destroy'])->name('destroy')->middleware('role:admin,editor');
        Route::post('bulk-delete',[MediaController::class, 'bulkDelete'])->name('bulk-delete')->middleware('role:admin,editor');
    });

    // ── Ads (read: admin/editor/viewer, mutate: admin/editor) ───────────────
    Route::prefix('ads')->name('ads.')->group(function () {
        Route::get('/',           [AdController::class, 'index'])  ->name('index');
        Route::get('create',      [AdController::class, 'create']) ->name('create')->middleware('role:admin,editor');
        Route::post('/',          [AdController::class, 'store'])  ->name('store') ->middleware('role:admin,editor');
        Route::get('{ad}/edit',   [AdController::class, 'edit'])   ->name('edit')  ->middleware('role:admin,editor');
        Route::put('{ad}',        [AdController::class, 'update']) ->name('update')->middleware('role:admin,editor');
        Route::delete('{ad}',     [AdController::class, 'destroy'])->name('destroy')->middleware('role:admin,editor');
        Route::patch('{ad}/toggle-status', [AdController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin,editor');
    });

    // ── Users (admin-only) ────────────────────────────────────────────────
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/',           [UserController::class, 'index'])  ->name('index');
        Route::get('create',      [UserController::class, 'create']) ->name('create');
        Route::post('/',          [UserController::class, 'store'])  ->name('store');
        Route::get('{user}/edit', [UserController::class, 'edit'])   ->name('edit');
        Route::put('{user}',      [UserController::class, 'update']) ->name('update');
        Route::delete('{user}',   [UserController::class, 'destroy'])->name('destroy');
        Route::patch('{user}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
    });

    // ── Settings (admin-only) ─────────────────────────────────────────────
    Route::prefix('settings')->name('settings.')->middleware('role:admin')->group(function () {
        Route::get('/',              [SettingController::class, 'index'])      ->name('index');
        Route::put('contact',        [SettingController::class, 'saveContact'])->name('contact');
        Route::put('about',          [SettingController::class, 'saveAbout'])  ->name('about');
        Route::put('social',         [SettingController::class, 'saveSocial']) ->name('social');
        Route::put('seo',            [SettingController::class, 'saveSeo'])    ->name('seo');
    });

    // ── Logout ────────────────────────────────────────────────────────────
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// ── Root redirect ─────────────────────────────────────────────────────────

require __DIR__.'/auth.php';
