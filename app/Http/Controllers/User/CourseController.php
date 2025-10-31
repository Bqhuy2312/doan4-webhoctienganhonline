<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Category;

class CourseController extends Controller
{
    /**
     * Hiển thị danh sách khóa học
     */
    public function courses(Request $request)
    {
        $query = Course::query()->where('is_active', true);

        // Tìm kiếm theo tên khóa học
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lấy danh mục và danh sách khóa học (phân trang)
        $categories = Category::all();
        $courses = $query->paginate(9);

        return view('user.courses', compact('courses', 'categories'));
    }

    /**
     * Hiển thị chi tiết khóa học
     */
    public function courseDetail(Course $course)
    {
        // Kiểm tra trạng thái hoạt động của khóa học
        if (!$course->is_active) {
            abort(404);
        }

        // Nạp thêm quan hệ để hiển thị đầy đủ thông tin
        $course->load(['sections.lessons', 'category']);

        // Kiểm tra người dùng đã đăng ký khóa học hay chưa
        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Auth::user()
                ->enrollments()
                ->where('course_id', $course->id)
                ->exists();
        }

        return view('user.course_detail', compact('course', 'isEnrolled'));
    }
}
