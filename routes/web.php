<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

// ==== USER CONTROLLERS ==== //
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// ==== ADMIN CONTROLLERS ==== //
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

//
// ========================= AUTH (USER) =========================
//

// Đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

//
// ========================= USER =========================
//

// Trang chủ (cả "/" và "/user/home" đều về home)
Route::get('/', [UserController::class, 'home'])->name('user.home');

Route::prefix('user')->middleware('auth')->group(function () {
    // Trang chính
    Route::get('/home', [UserController::class, 'home'])->name('user.home');

    // Khóa học
    Route::get('/courses', [UserController::class, 'courses'])->name('user.courses');
    Route::get('/course/{id}', [UserController::class, 'courseDetail'])->name('user.course.detail');

    // Quiz + Chat
    Route::get('/quiz', [UserController::class, 'quiz'])->name('user.quiz');
    Route::get('/chat', [UserController::class, 'chat'])->name('user.chat');

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('user.profile.password');
});

//
// ========================= ADMIN =========================
//
Route::prefix('admin')->name('admin.')->group(function () {

    // --- Auth ---
    Route::get('/', [adAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [adAuthController::class, 'login'])->name('auth.login.submit');

    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [adAuthController::class, 'logout'])->name('auth.logout');

        // --- Dashboard ---
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- Quản lý danh mục ---
        Route::resource('categories', CategoryController::class)
            ->names('categories')
            ->except(['show', 'create', 'edit']);

        // --- Quản lý khóa học ---
        Route::resource('courses', CourseController::class)->names('courses');

        // --- Section ---
        Route::post('/courses/{course}/sections', [SectionController::class, 'store'])->name('courses.sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

        // --- Lesson ---
        Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
        Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

        // --- Student ---
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{user}', [StudentController::class, 'show'])->name('students.show');

        // --- Quiz ---
        Route::resource('quizzes', QuizController::class)->names('quizzes');
        Route::resource('quizzes.questions', QuestionController::class)->names('quizzes.questions')->shallow();
        Route::get('/quizzes/{quiz}/results', [QuizAttemptController::class, 'index'])->name('quizzes.results');

        // --- Chat ---
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/{user}/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
        Route::post('/chat/{user}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
    });
});
