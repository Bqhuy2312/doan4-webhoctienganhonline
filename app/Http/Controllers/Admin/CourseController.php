<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->withCount('students')->paginate(10); 
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
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

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
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