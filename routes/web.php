<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

// Form quên mật khẩu
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Gửi email reset mật khẩu
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [UserController::class, 'home'])->name('user.home');
// Nhóm route cho User
Route::prefix('user')->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('user.home');
    Route::get('/courses', [UserController::class, 'courses'])->name('user.courses');
    Route::get('/course/{id}', [UserController::class, 'courseDetail'])->name('user.course.detail');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/quiz', [UserController::class, 'quiz'])->name('user.quiz');
    Route::get('/chat', [UserController::class, 'chat'])->name('user.chat');
});
