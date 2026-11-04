<?php
// ════════════════════════════════════════════════════════════════════════════
// app/Http/Middleware/VerifiedActive.php
// Ensures the authenticated user's account is still active.
// Register as 'verified_active' in bootstrap/app.php (Laravel 11) or
// Kernel.php $routeMiddleware (Laravel 10).
// ════════════════════════════════════════════════════════════════════════════
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifiedActive
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();

        if ($user && ! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => __('Your account has been deactivated. Contact an administrator.')]);
        }

        return $next($request);
    }
}
