# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Laravel 10 (PHP 8.1) application for a bilingual (English/Arabic) nonprofit ("Benevolent Society") website. It combines:

- A public marketing site (mostly static bilingual HTML/Blade pages).
- An admin CMS at `/admin` for managing posts, categories, media, ads, users, and site settings.

Frontend build tooling is Vite + Tailwind CSS + Alpine.js (Laravel Breeze scaffolding for auth).

## Commands

**PHP dependencies:** `composer install`
**JS dependencies:** `npm install`

**Run dev server:** `php artisan serve` (backend) + `npm run dev` (Vite, in parallel)
**Build frontend assets:** `npm run build`

**Run all tests:** `php artisan test` or `vendor/bin/phpunit`
**Run a single test file:** `vendor/bin/phpunit tests/Feature/Auth/AuthenticationTest.php`
**Run a single test method:** `vendor/bin/phpunit --filter test_method_name tests/Feature/Auth/AuthenticationTest.php`

Note: `phpunit.xml` has the sqlite in-memory DB config commented out, so tests currently hit whatever `DB_CONNECTION`/`DB_DATABASE` is set in `.env` (default: MySQL). If you need isolated test runs, uncomment the `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` lines in `phpunit.xml` or override via env vars.

**Migrations:** `php artisan migrate` / `php artisan migrate:fresh --seed`
**Storage symlink for public media uploads:** `php artisan storage:link` (required — `MediaController` stores files on the `public` disk and serves them via `asset('storage/...')`)

**Code style:** `vendor/bin/pint` (Laravel Pint, already a dev dependency)

## Architecture

### Bilingual content model

Content-bearing tables (`posts`, `settings`, `media` descriptions) store **parallel English/Arabic columns** (e.g. `title_en`/`title_ar`, `value_en`/`value_ar`) rather than using a translation package. Models expose locale-resolved accessors that check `App::getLocale()`:

- `Post::getTitleAttribute()`, `getExcerptAttribute()`, `getContentAttribute()`
- `Media::getDescriptionAttribute()`
- `Setting::getResolvedValueAttribute()` and the static helper `Setting::get($key, $locale = null)`

**Important:** there is currently no middleware or route mechanism that calls `App::setLocale()` — `config/app.php` locale stays at the default (`en`) for the whole backend request lifecycle. The public-facing bilingual toggle (`.en`/`.ar` spans) in `resources/views/index.blade.php` is done client-side with CSS/JS, not via Laravel's locale system. Keep this in mind before assuming `App::getLocale()` reflects what the visitor sees.

### Admin CMS (`app/Http/Controllers/Admin/*`)

All admin routes live under `Route::prefix('admin')->name('admin.')` in `routes/web.php`, gated by `['auth', 'verified_active']`. Resource areas: Posts, Categories, Media, Ads, Users, Settings. Users and Settings additionally require `role:admin` (see `CheckRole` middleware, registered as the `role` alias).

- **Roles** are a plain string column on `users.role` (no package like spatie/permission). Helpers: `User::isAdmin()`, `isEditor()`, `hasRole()`.
- **Account activation**: `users.is_active` is enforced by `VerifiedActive` middleware (alias `verified_active`), which force-logs-out and redirects to login with an error if a logged-in user's account has been deactivated mid-session.
- **Authorization beyond roles** goes through policies — see `UserPolicy`: admins can't edit/delete other admins or delete themselves, preventing privilege escalation via the admin UI.
- **Media** (`app/Models/Media.php`) is a polymorphic (`morphTo`/`morphMany`) attachment model shared by any `mediable` model (currently `Post`). Uploaded files always go to the `public` disk under `media/`; MIME type is mapped to a coarse `type` (`image`/`video`/`document`/`audio`/`other`) via a hardcoded map in `MediaController::TYPE_MAP`.
- **Settings** (`app/Models/Setting.php`) is a flexible key/value table (`group`, `type`, `value`/`value_en`/`value_ar`) rather than fixed columns per setting. `SettingController` writes to it via a private `upsert($key, $type, $values)` helper per settings tab (contact/about/social/seo). When adding a new setting, follow this key/value + `upsert` pattern rather than adding new columns.

### Public site

Only the `/` route is wired up in `routes/web.php`, rendering `resources/views/index.blade.php`. That view's nav/CTA links (`contact.html`, `vision.html`, `prog-*.html`) point at static HTML files that live in `resources/views/` (not in `public/`) and are **not currently served by any route** — they will 404 as-is. Before treating the public multi-page site as functional, check whether these need real Blade views + routes, or need to be moved into `public/` as static assets.

`resources/views/AdminDashboard/partials/*.html` and `resources/views/admin/pages/dashboard.blade.php` are unused leftover scaffold files — the live admin dashboard is `resources/views/admin/dashboard.blade.php`, rendered by `DashboardController::index()`.

### Request validation

Form requests live under `app/Http/Requests/Admin/*` (e.g. `PostRequest`, `CategoryRequest`, `AdRequest`, `UserRequest`) for the primary CRUD resources, but simpler/tabbed endpoints (Media, Settings) validate inline in the controller via `$request->validate()`. Follow whichever pattern the existing controller for that resource already uses.
