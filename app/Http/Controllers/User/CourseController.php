<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('is_active', true);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->paginate(9);
        return view('user.courses', compact('courses'));
    }

    public function show($id)
    {
        $course = Course::with('sections.lessons')->findOrFail($id);
        return view('user.course_detail', compact('course'));
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
}
