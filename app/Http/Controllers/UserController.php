<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Trang chủ User
    public function home()
    {
        $courses = [
            (object) [
                'id' => 1,
                'title' => 'Tiếng Anh Giao Tiếp',
                'description' => 'Khóa học cho người mới bắt đầu',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Giao+Tiep'
            ],
            (object) [
                'id' => 2,
                'title' => 'Luyện Nghe Tiếng Anh',
                'description' => 'Cải thiện kỹ năng nghe hiệu quả',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Nghe+Tieng+Anh'
            ],
            (object) [
                'id' => 3,
                'title' => 'Ngữ pháp nâng cao',
                'description' => 'Khóa học dành cho Intermediate',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Ngu+Phap'
            ],
        ];
        return view('user.homepage', compact('courses'));
    }

    // Danh sách khóa học
    public function courses()
    {
        $courses = [
            (object) [
                'id' => 1,
                'title' => 'Tiếng Anh Giao Tiếp',
                'description' => 'Khóa học cho người mới bắt đầu',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Giao+Tiep'
            ],
            (object) [
                'id' => 2,
                'title' => 'Luyện Nghe Tiếng Anh',
                'description' => 'Cải thiện kỹ năng nghe hiệu quả',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Nghe+Tieng+Anh'
            ],
            (object) [
                'id' => 3,
                'title' => 'Ngữ pháp nâng cao',
                'description' => 'Khóa học dành cho Intermediate',
                'thumbnail' => 'https://via.placeholder.com/300x200?text=Ngu+Phap'
            ],
        ];
        return view('user.courses', compact('courses'));
    }

    // Chi tiết khóa học
    public function courseDetail($id)
    {
        $course = (object) [
            'id' => $id,
            'title' => 'Tiếng Anh Giao Tiếp',
            'description' => 'Khóa học này giúp bạn giao tiếp tiếng Anh cơ bản.',
            'thumbnail' => 'https://via.placeholder.com/300x200?text=Chi+Tiet+Khoa+Hoc',
            'lessons' => [
                (object) ['id' => 1, 'title' => 'Bài 1: Giới thiệu'],
                (object) ['id' => 2, 'title' => 'Bài 2: Chào hỏi'],
                (object) ['id' => 3, 'title' => 'Bài 3: Hỏi đường'],
            ]
        ];
        return view('user.course_detail', compact('course'));
    }

    // Hồ sơ cá nhân
    public function profile()
{
    $user = Auth::user(); // ✅ lấy thông tin user đang login

    // Demo khóa học gắn cho user
    $user->courses = [
        (object)['title'=>'Tiếng Anh Giao Tiếp','pivot'=>(object)['progress'=>70]],
        (object)['title'=>'Ngữ pháp nâng cao','pivot'=>(object)['progress'=>40]],
    ];

    return view('user.profile', compact('user'));
}
// Trang quiz
    public function quiz()
{
    $quizzes = [
        (object) ['id'=>1, 'title'=>'Bài kiểm tra ngữ pháp cơ bản'],
        (object) ['id'=>2, 'title'=>'Bài kiểm tra kỹ năng nghe'],
        (object) ['id'=>3, 'title'=>'Bài kiểm tra từ vựng'],
    ];
    return view('user.quiz', compact('quizzes'));
}
// Trang chat
public function chat()
{
    $messages = [
        (object)['user' => 'Nguyễn Văn A', 'message' => 'Xin chào, tôi muốn hỏi về khóa học.'],
        (object)['user' => 'Giảng viên', 'message' => 'Chào bạn, mình có thể giúp gì?'],
        (object)['user' => 'Nguyễn Văn A', 'message' => 'Khóa học này kéo dài bao lâu vậy?'],
    ];
    return view('user.chat', compact('messages'));
}

}
