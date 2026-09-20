<?php
// ════════════════════════════════════════════════════════════════════════════
// app/Http/Middleware/CheckRole.php
// Usage: Route::middleware('role:admin')
// ════════════════════════════════════════════════════════════════════════════
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->   role, $roles)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
