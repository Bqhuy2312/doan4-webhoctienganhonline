<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class UserController extends Controller
{
    // =================== TRANG CHỦ =================== //
    public function home()
    {
        // Lấy danh sách 3 khóa học nổi bật
        $courses = Course::where('is_active', true)
            ->take(3)
            ->get();

        return view('user.homepage', compact('courses'));
    }

    // =================== DANH SÁCH KHÓA HỌC =================== //
    public function courses(Request $request)
    {
        $query = Course::where('is_active', true);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->paginate(9);

        return view('user.courses', compact('courses'));
    }

    // =================== CHI TIẾT KHÓA HỌC =================== //
    public function courseDetail($id)
    {
        $course = Course::with('sections.lessons')->findOrFail($id);

        return view('user.course_detail', compact('course'));
    }

    // =================== HỒ SƠ CÁ NHÂN =================== //
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            // Load quan hệ: user -> courses -> level (nếu có bảng levels)
            $user->load('courses.level');
        }

        return view('user.profile', compact('user'));
    }

    // =================== QUIZ =================== //
    public function quiz()
    {
        // Demo quiz – sau có thể đổi thành lấy từ DB
        $quizzes = [
            (object) ['id' => 1, 'title' => 'Bài kiểm tra ngữ pháp cơ bản'],
            (object) ['id' => 2, 'title' => 'Bài kiểm tra kỹ năng nghe'],
            (object) ['id' => 3, 'title' => 'Bài kiểm tra từ vựng'],
        ];

        return view('user.quiz', compact('quizzes'));
    }

    // =================== CHAT =================== //
    public function chat()
    {
        // Demo chat – sau có thể kết nối DB / Realtime
        $messages = [
            (object) ['user' => 'Nguyễn Văn A', 'message' => 'Xin chào, tôi muốn hỏi về khóa học.'],
            (object) ['user' => 'Giảng viên', 'message' => 'Chào bạn, mình có thể giúp gì?'],
            (object) ['user' => 'Nguyễn Văn A', 'message' => 'Khóa học này kéo dài bao lâu vậy?'],
        ];

        return view('user.chat', compact('messages'));
    }
}
