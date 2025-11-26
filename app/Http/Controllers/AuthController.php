<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Hiển thị form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

         if (Auth::guard('web')->attempt(array_merge($credentials, ['role' => 'user']))) {
            $request->session()->regenerate();
            return redirect()->route('user.home');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        // Logout only the web guard so we don't accidentally clear the
        // entire session when an admin is also logged in (same users table).
        Auth::guard('web')->logout();

        // If there's no admin session active, it's safe to invalidate the
        // whole session (prevents session fixation). If an admin is also
        // authenticated (same browser), preserve the session to avoid
        // logging them out; still regenerate the CSRF token.
        if (!Auth::guard('admin')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            // Preserve session but rotate CSRF token
            $request->session()->regenerateToken();
        }

        return redirect()->route('login');
    }

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|string|email|unique:users,email',
        'password'   => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'user',
    ]);

    Auth::guard('web')->login($user);

    return redirect()->route('user.home');
}

}
