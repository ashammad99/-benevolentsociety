# Benevolent Society for Disabled — جمعية دار المعاقين الخيرية

**Live site:** [http://benevolentsociety.ps/](http://benevolentsociety.ps/)

The bilingual (English / Arabic) website of the Benevolent Society for Disabled in Gaza City, with a
content management system for its staff.

## Features

- **Public site** in English and Arabic (full right-to-left layout): home, vision & aims, contact,
  six program pages, and a news section with categories.
- **Contact form** that emails the organisation (spam-protected, rate-limited).
- **Ads** in four placements: banner, popup, sidebar (news pages) and inline (home and program pages).
- **Admin panel** at `/admin` for news posts, categories, media, ads, users and site settings, with
  admin / editor / viewer roles. There is no public registration or self-service password reset:
  admins create staff accounts and set their passwords.

## Tech stack

Laravel 13 · PHP 8.3 · MySQL · plain CSS/JS (no frontend build step).

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# set DB_* in .env, then:
php artisan migrate
php artisan db:seed              # empty contact / "Vision & Aims" settings rows
php artisan society:create-admin # first admin account (prompts for the password)
php artisan storage:link
php artisan serve
```

To see contact-form emails locally without a mail server, run `MAIL_MAILER=log php artisan serve`;
messages are written to `storage/logs/laravel.log`.

**Tests:** `php artisan test` (runs against an in-memory SQLite database, never your `.env` database).
**Code style:** `vendor/bin/pint`.

## Configuration

- **Branding** — name, logo, location, footer lines, headline statistics, contact-form reasons and
  fallback contact details: `config/society.php`.
- **Contact details, social links, SEO and the Vision & Aims text** — Admin → Settings.
- **Site photos** — `public/images/site/` (replace a file with one of the same name to change it).

## Deployment (cPanel)

1. Build: `composer install --no-dev --optimize-autoloader`, then upload the project **outside**
   `public_html` and point the domain's document root at its `public/` folder.
2. Server `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` = the exact site URL (other host
   names are rejected), `SESSION_SECURE_COOKIE=true`, database and `MAIL_*` settings.
3. On the server:
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan society:create-admin
   php artisan storage:link
   php artisan optimize
   ```
4. Turn on AutoSSL and Force HTTPS Redirect.

`public/.htaccess` blocks dotfiles such as `.env`, and `storage/app/public/.htaccess` stops uploaded
files from ever running as PHP.

## Credits

Developed by [Ahmed Hammad](https://www.linkedin.com/in/ahmed-hammad-461337177/).

Built with [Laravel](https://laravel.com) (MIT licence).
