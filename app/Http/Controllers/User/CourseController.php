<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Category;

class CourseController extends Controller
{
    // DANH SÁCH KHÓA HỌC
    public function courses(Request $request)
    {
        $query = Course::where('is_active', true);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $categories = Category::all();

        $courses = $query->paginate(9);

        return view('user.courses', compact('courses', 'categories'));
    }

    // CHI TIẾT KHÓA HỌC
    public function courseDetail(Course $course)
    {
        if (!$course->is_active) {
            abort(404);
        }

        $course->load('sections.lessons', 'category');

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Auth::user()->enrollments()
                                      ->where('course_id', $course->id)
                                      ->exists();
        }

        return view('user.course_detail', compact('course', 'isEnrolled'));
    }
}
