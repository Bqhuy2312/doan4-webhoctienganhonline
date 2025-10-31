<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class UserController extends Controller
{
    /**
     * =================== TRANG CHỦ ===================
     * Hiển thị 3 khóa học nổi bật
     */
    public function home()
    {
        $courses = Course::where('is_active', true)
            ->take(3)
            ->get();

        return view('user.homepage', compact('courses'));
    }

    /**
     * =================== HỒ SƠ CÁ NHÂN ===================
     * Hiển thị thông tin người dùng và các khóa học đã đăng ký
     */
    public function profile()
    {
        $user = Auth::user();

        if ($user) {
            // Load quan hệ: user -> enrolledCourses -> category (hoặc level nếu có)
            $user->load(['enrolledCourses.category']);
        }

        return view('user.profile', compact('user'));
    }

    /**
     * =================== QUIZ ===================
     * Hiển thị danh sách bài kiểm tra mẫu (demo)
     */
    public function quiz()
    {
        $quizzes = [
            (object) ['id' => 1, 'title' => 'Bài kiểm tra ngữ pháp cơ bản'],
            (object) ['id' => 2, 'title' => 'Bài kiểm tra kỹ năng nghe'],
            (object) ['id' => 3, 'title' => 'Bài kiểm tra từ vựng'],
        ];

        return view('user.quiz', compact('quizzes'));
    }

    /**
     * =================== CHAT ===================
     * Hiển thị khung trò chuyện demo
     */
    public function chat()
    {
        $messages = [
            (object) ['user' => 'Nguyễn Văn A', 'message' => 'Xin chào, tôi muốn hỏi về khóa học.'],
            (object) ['user' => 'Giảng viên', 'message' => 'Chào bạn, mình có thể giúp gì?'],
            (object) ['user' => 'Nguyễn Văn A', 'message' => 'Khóa học này kéo dài bao lâu vậy?'],
        ];

        return view('user.chat', compact('messages'));
    }
}
