# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Laravel 13 (PHP 8.3+) application for a bilingual (English/Arabic) nonprofit ("Benevolent Society") website. It combines:

- A public bilingual site (Blade, under `resources/views/public/`): home, vision, contact, six program pages (static copy), and a database-driven news listing/detail (`/news`).
- An admin CMS at `/admin` for managing posts, categories, media, ads, users, and site settings.

Frontend build tooling is Vite + Tailwind CSS + Alpine.js (Laravel Breeze scaffolding for auth).

The app was upgraded from Laravel 10 but deliberately **kept on the Laravel 10 directory structure** (`app/Http/Kernel.php`, `app/Console/Kernel.php`, `RouteServiceProvider`, full `config/` files) rather than the slim Laravel 11+ skeleton — register middleware and aliases in `app/Http/Kernel.php`, not `bootstrap/app.php`. `App\Http\Middleware\VerifyCsrfToken` keeps its old name but extends Laravel 13's `PreventRequestForgery`; CSRF is skipped entirely while running tests, so tests don't prove forms carry `@csrf`. `config/cache.php` sets `serializable_classes => false` — add a class there before caching objects of it.

## Commands

**PHP dependencies:** `composer install`
**JS dependencies:** `npm install`

**Run dev server:** `php artisan serve` (backend) + `npm run dev` (Vite, in parallel)
**Build frontend assets:** `npm run build`

**Run all tests:** `php artisan test` or `vendor/bin/phpunit`
**Run a single test file:** `vendor/bin/phpunit tests/Feature/Auth/AuthenticationTest.php`
**Run a single test method:** `vendor/bin/phpunit --filter test_method_name tests/Feature/Auth/AuthenticationTest.php`

Note: `phpunit.xml` runs tests against sqlite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), so `RefreshDatabase` never touches the `.env` database. `tests/TestCase.php` calls `withoutVite()`, so tests do not prove the Breeze guest pages (forgot/reset password, verify email, confirm password) render — those use `@vite` and need `npm run build` (or `npm run dev`) to exist at runtime.

**Migrations:** `php artisan migrate`

**First admin / seeding:** the seeder creates no users. On a new database run `php artisan migrate`, then `php artisan society:create-admin` (prompts for the password), then `php artisan db:seed` — `CategoriesSeeder` assigns categories to the first admin and skips them if none exists. Both seeders use `insertOrIgnore`, so re-running `db:seed` only adds missing rows.

Sanctum's `personal_access_tokens` migration is published into `database/migrations/` under its original `2019_12_14_000001_...` name (Sanctum 4 no longer auto-loads it); keep that filename so existing databases continue to see it as already run.

**Storage symlink for public media uploads:** `php artisan storage:link` (required — `MediaController` stores files on the `public` disk and serves them via `asset('storage/...')`)

**Code style:** `vendor/bin/pint` (Laravel Pint, already a dev dependency)

**Legacy post import (one-time):** `php artisan society:import-legacy-posts --legacy-db=old_benevole --images-zip=path/to/photos.zip` — reads posts from the old Laravel 5.5 site's dump (loaded into a *separate* database on the default connection's server) and stores cover images on the `public` disk. It is a **dry run unless `--commit`** is passed, refuses to use the app database as the source, only verifies (never imports) users/categories, and skips posts whose serialized en/ar title/content is invalid or contains script/iframe/event-handler markup.

## Architecture

### Bilingual content model

Content-bearing tables (`posts`, `settings`, `media` descriptions) store **parallel English/Arabic columns** (e.g. `title_en`/`title_ar`, `value_en`/`value_ar`) rather than using a translation package. Models expose locale-resolved accessors that check `App::getLocale()`:

- `Post::getTitleAttribute()`, `getExcerptAttribute()`, `getContentAttribute()`
- `Media::getDescriptionAttribute()`
- `Setting::getResolvedValueAttribute()` and the static helper `Setting::get($key, $locale = null)`

Locale is set per request by the `SetLocale` middleware (`app/Http/Middleware/SetLocale.php`, in the `web` group): `?lang=en|ar` switches and is remembered in the session; invalid/missing values fall back to `config("app.locale")`. Layouts emit `<html lang dir>` from `app()->getLocale()`, and the `.en`/`.ar` span pairs are toggled by CSS on `[dir=rtl]`. `resources/views/auth/login.blade.php` is a standalone page (not `layouts.guest`) but follows the same `?lang=` mechanism. `resources/lang/ar.json` holds Arabic strings for flash messages; `resources/lang/ar/{auth,passwords}.php` translate the framework's login/password-reset messages (English falls back to the framework's built-in files).

### Admin CMS (`app/Http/Controllers/Admin/*`)

All admin routes live under `Route::prefix('admin')->name('admin.')` in `routes/web.php`, gated by `['auth', 'verified_active']`. Resource areas: Posts, Categories, Media, Ads, Users, Settings. Read routes are open to admin/editor/viewer; every mutating route on Posts, Categories, Media and Ads requires `role:admin,editor`; Users and Settings are entirely `role:admin` (see `CheckRole` middleware, registered as the `role` alias). `/admin` redirects to the dashboard, which lives at `/admin/AdminDashboard` (`admin.dashboard`). There is **no public registration** — `users.role` defaults to `editor`, so accounts must only be created via the admin Users screen (or `society:create-admin` for the first admin).

- **Roles** are a plain string column on `users.role` (no package like spatie/permission). Helpers: `User::isAdmin()`, `isEditor()`, `hasRole()`.
- **Account activation**: `users.is_active` is enforced by `VerifiedActive` middleware (alias `verified_active`), which force-logs-out and redirects to login with an error if a logged-in user's account has been deactivated mid-session.
- **Authorization beyond roles** goes through policies — see `UserPolicy`: admins can't edit/delete other admins or delete themselves, preventing privilege escalation via the admin UI.
- **Media** (`app/Models/Media.php`) is a polymorphic (`morphTo`/`morphMany`) attachment model shared by any `mediable` model (currently `Post`). Uploaded files always go to the `public` disk under `media/`; MIME type is mapped to a coarse `type` (`image`/`video`/`document`/`audio`/`other`) via a hardcoded map in `MediaController::TYPE_MAP`. `MediaController::show` returns JSON only (there is no media detail page).
- **Auth flows** (login, password reset, verify email, confirm password) are the Breeze controllers in `App\Http\Controllers\Auth` (`routes/auth.php`); `Admin\AuthController` only handles logout from the admin sidebar.
- **Settings** (`app/Models/Setting.php`) is a flexible key/value table (`group`, `type`, `value`/`value_en`/`value_ar`) rather than fixed columns per setting. `SettingController` writes to it via a private `upsert($key, $type, $values)` helper per settings tab (contact/about/social/seo). When adding a new setting, follow this key/value + `upsert` pattern rather than adding new columns.

### Public site

Public routes (no auth): `/` (`home`), `/vision`, `/contact`, `/programs/{rehab|education|vocational|social|homecare|devices}`, `/news`, `/news/{post}`. All views extend `layouts.public`. Only published posts with `published_at <= now()` are exposed (`Post::published()` scope; the detail route 404s otherwise); the only settings read publicly are contact info, social links and SEO meta. The admin "About" settings (`vision`, `aims`) are stored but the public `/vision` page is static copy and does not render them yet.

**Branding** lives in `config/society.php` (bilingual name, short name, logo path under `public/`, footer tagline and registration line, office hours, and fallback contact/meta values shown while the admin Settings are empty). The public layout, login page and admin layout/sidebar read from it — never hard-code the organisation's name in a view. Page views set only `@section('title', 'Page')`; `layouts.public` appends the name in the current locale. Body copy on the home, vision and program pages (and the home/login statistics) is still static text in those views.

The live admin dashboard is `resources/views/admin/dashboard.blade.php`, rendered by `DashboardController::index()`. Admin views share `admin/layouts/app.blade.php` and the CSS/JS in `public/dashboard/`.

### Request validation

Form requests live under `app/Http/Requests/Admin/*` (e.g. `PostRequest`, `CategoryRequest`, `AdRequest`, `UserRequest`) for the primary CRUD resources, but simpler/tabbed endpoints (Media, Settings) validate inline in the controller via `$request->validate()`. Follow whichever pattern the existing controller for that resource already uses.
