<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\adAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizAttemptController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ChatController;

// =================== AUTH =================== //

// Quên mật khẩu
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

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

// =================== USER =================== //

// Trang chủ mặc định → user.home
Route::get('/', [UserController::class, 'home'])->name('user.home');

Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('user.home');
    Route::get('/courses', [UserController::class, 'courses'])->name('user.courses');
    Route::get('/course/{id}', [UserController::class, 'courseDetail'])->name('user.course.detail');
    Route::get('/quiz', [UserController::class, 'quiz'])->name('user.quiz');
    Route::get('/chat', [UserController::class, 'chat'])->name('user.chat');

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('user.profile.password');
});

// =================== ADMIN =================== //
Route::prefix('admin')->group(function () {

    // Login Admin
    Route::get('/', [adAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [adAuthController::class, 'login'])->name('admin.auth.login.submit');

    Route::middleware(['auth'])->group(function () {

        // Logout
        Route::post('/logout', [adAuthController::class, 'logout'])->name('admin.auth.logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Categories
        Route::resource('categories', CategoryController::class)->names('admin.categories')->except(['show', 'create', 'edit']);

        // Courses
        Route::resource('courses', CourseController::class)->names('admin.courses');

        // Sections
        Route::post('/courses/{course}/sections', [SectionController::class, 'store'])->name('admin.courses.sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('admin.sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('admin.sections.destroy');

        // Lessons
        Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
        Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('admin.lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');

        // Students

        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/{user}', [StudentController::class, 'show'])->name('admin.students.show');

        // Quizzes
        Route::resource('quizzes', QuizController::class)->names('admin.quizzes');
        Route::resource('quizzes.questions', QuestionController::class)->names('admin.quizzes.questions')->shallow();
        Route::get('/quizzes/{quiz}/results', [QuizAttemptController::class, 'index'])->name('admin.quizzes.results');

        // Chat
        Route::get('/chat', [ChatController::class, 'index'])->name('admin.chat.index');
        // Các route API để JS gọi
        Route::get('/chat/{user}/messages', [ChatController::class, 'fetchMessages'])->name('admin.chat.fetch');
        Route::post('/chat/{user}/messages', [ChatController::class, 'sendMessage'])->name('admin.chat.send');

    });
});
