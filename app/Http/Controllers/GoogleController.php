<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    // Chuyển hướng người dùng tới Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Nhận callback từ Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'first_name' => $googleUser->user['given_name'] ?? '',
                    'last_name'  => $googleUser->user['family_name'] ?? '',
                    'avatar'     => $googleUser->getAvatar(),
                    'password'   => Hash::make(uniqid()),
                    'role'       => 'user',
                ]
            );

            Auth::login($user);

            return redirect()->route('user.home');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Không thể đăng nhập bằng Google.');
        }
    }
}
