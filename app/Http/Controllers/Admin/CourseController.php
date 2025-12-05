<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\Category;
use App\Models\Quiz;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::withCount('students');

        if ($request->keyword) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $courses = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'student_limit' => 'nullable|numeric|min:1',
            'status' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $validatedData['thumbnail_url'] = $path;
        }

        $validatedData['is_active'] = $validatedData['status'];
        unset($validatedData['status']);
        unset($validatedData['thumbnail']);

        Course::create($validatedData);

        return redirect()->route('admin.courses.index')->with('success', 'Tạo khóa học thành công!');
    }

    public function show(Course $course)
    {
        $course->load('sections.lessons');

        $quizzes = Quiz::latest()->get();

        return view('admin.courses.show', compact('course', 'quizzes'));
    }

    public function edit(Course $course)
    {
        $categories = Category::all();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'student_limit' => 'nullable|numeric|min:1',
            'status' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_url) {
                Storage::disk('public')->delete($course->thumbnail_url);
            }
            $path = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $validatedData['thumbnail_url'] = $path;
        }

        $validatedData['is_active'] = $validatedData['status'];
        unset($validatedData['status']);
        unset($validatedData['thumbnail']);

        $course->update($validatedData);

        return redirect()->route('admin.courses.index')->with('success', 'Cập nhật khóa học thành công!');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail_url) {
            Storage::disk('public')->delete($course->thumbnail_url);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Xóa khóa học thành công!');
    }
}