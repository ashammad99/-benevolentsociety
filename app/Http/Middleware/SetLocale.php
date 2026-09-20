<?php
// ════════════════════════════════════════════════════════════════════════════
// app/Http/Middleware/SetLocale.php
//
// The single request-level locale mechanism for the whole app (admin +
// public): ?lang=en|ar explicitly switches locale and is remembered in the
// session; otherwise the session value is reused; anything invalid or
// missing falls back to config('app.locale'). Registered once in the `web`
// middleware group — no locale logic anywhere else.
// ════════════════════════════════════════════════════════════════════════════
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    private const SUPPORTED = ['en', 'ar'];

    public function handle(Request $request, Closure $next): mixed
    {
        $requested = $request->query('lang');

        if (is_string($requested) && in_array($requested, self::SUPPORTED, true)) {
            $locale = $requested;
            $request->session()->put('locale', $locale);
        } else {
            $locale = $request->session()->get('locale');
            if (! in_array($locale, self::SUPPORTED, true)) {
                $locale = config('app.locale');
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
