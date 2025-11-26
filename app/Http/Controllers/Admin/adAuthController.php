<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class adAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác hoặc không có quyền truy cập',
        ])->onlyInput('email');
    }

    public function adlogout(Request $request)
    {
        // Logout only the web guard so we don't accidentally clear the
        // entire session when an admin is also logged in (same users table).
        Auth::guard('admin')->logout();

        // If there's no admin session active, it's safe to invalidate the
        // whole session (prevents session fixation). If an admin is also
        // authenticated (same browser), preserve the session to avoid
        // logging them out; still regenerate the CSRF token.
        if (!Auth::guard('web')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            // Preserve session but rotate CSRF token
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin.login');
    }
}