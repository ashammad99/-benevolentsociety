<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Login and password-reset flows are handled by the Breeze controllers in
// App\Http\Controllers\Auth (routes/auth.php). This controller only serves
// the admin sidebar's logout form.
class AuthController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
