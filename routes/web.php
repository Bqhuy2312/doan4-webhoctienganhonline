<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

// ==== USER CONTROLLERS ==== //
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\EnrollmentController;
use App\Http\Controllers\User\LearnController;
use App\Http\Controllers\User\MyCoursesController;
use App\Http\Controllers\User\QuizAttemptController;
use App\Http\Controllers\User\LessonCompletionController;

// ==== ADMIN CONTROLLERS ==== //
use App\Http\Controllers\Admin\adAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\QuizAttemptController as AdminQuizAttemptController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;

//
// ========================= AUTH (USER) =========================
//

// Đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Đăng nhập với Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

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

Route::prefix('user')->middleware('auth:web')->group(function () {
    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Trang chính
    Route::get('/home', [UserController::class, 'home'])->name('user.home');

    // Khóa học
    Route::get('/courses', [CourseController::class, 'courses'])->name('user.courses');
    Route::get('/course/{course}', [CourseController::class, 'courseDetail'])->name('user.course.detail');

    // Quiz + Chat
    Route::get('/quiz', [UserController::class, 'quiz'])->name('user.quiz');
    Route::get('/chat', [UserController::class, 'chat'])->name('user.chat');

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('user.profile.password');

    // Đăng kí khóa học
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('user.enroll');
    // Vào học
    Route::get('/learn/{course}/{lesson}', [LearnController::class, 'show'])->name('user.learn');
    // Tiếp tục học
    Route::get('/courses/{course}/resume', [LearnController::class, 'resume'])->name('user.resume');
    // Khóa học của tôi
    Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('user.my_courses');
    //Chấm bài quiz
    // Route để xử lý nộp bài quiz
    Route::post('/quiz/{quiz}/submit', [QuizAttemptController::class, 'store'])->name('user.quiz.submit');
    // Route để JS gọi đến khi hoàn thành bài học (video/pdf)
    Route::post('/lessons/{lesson}/complete', [LessonCompletionController::class, 'store'])->name('user.lessons.complete');
});

//
// ========================= ADMIN =========================
//
Route::prefix('admin')->group(function () {

    // --- Auth ---
    Route::get('/', [adAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [adAuthController::class, 'login'])->name('admin.auth.login.submit');


    Route::middleware('auth:admin')->group(function () {
        // Logout
        Route::post('/logout', [adAuthController::class, 'logout'])->name('admin.auth.logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Categories
        Route::resource('categories', CategoryController::class)->names('admin.categories')->except(['show', 'create', 'edit']);

        // Courses
        Route::resource('courses', AdminCourseController::class)->names('admin.courses');

        // Sections
        Route::post('/courses/{course}/sections', [AdminSectionController::class, 'store'])->name('admin.courses.sections.store');
        Route::put('/sections/{section}', [AdminSectionController::class, 'update'])->name('admin.sections.update');
        Route::delete('/sections/{section}', [AdminSectionController::class, 'destroy'])->name('admin.sections.destroy');

        // Lessons
        Route::post('/courses/{course}/lessons', [AdminLessonController::class, 'store'])->name('admin.lessons.store');
        Route::put('/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('admin.lessons.update');
        Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('admin.lessons.destroy');
        Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('admin.lessons.destroy');

        // Students

        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/{user}', [StudentController::class, 'show'])->name('admin.students.show');

        // Quizzes
        Route::resource('quizzes', AdminQuizController::class)->names('admin.quizzes');
        Route::resource('quizzes.questions', QuestionController::class)->names('admin.quizzes.questions')->shallow();
        Route::get('/quizzes/{quiz}/results', [AdminQuizAttemptController::class, 'index'])->name('admin.quizzes.results');
        Route::post('/quizzes/import', [AdminQuizController::class, 'import'])->name('admin.quizzes.import');

        // Chat
        Route::get('/chat', [AdminChatController::class, 'index'])->name('admin.chat.index');
        // Các route API để JS gọi
        Route::get('/chat/{user}/messages', [AdminChatController::class, 'fetchMessages'])->name('admin.chat.fetch');
        Route::post('/chat/{user}/messages', [AdminChatController::class, 'sendMessage'])->name('admin.chat.send');
    });
});
