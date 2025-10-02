<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,quiz',
        ]);

        if ($request->type === 'video') {
            $validated += $request->validate(['video_url' => 'required|url']);
        } elseif ($request->type === 'pdf') {
            $validated += $request->validate(['pdf_file' => 'required|file|mimes:pdf|max:10240']);
        } elseif ($request->type === 'quiz') {
            $validated += $request->validate(['quiz_id' => 'required|exists:quizzes,id']);
        }
        
        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
            $validated['pdf_path'] = $path;
        }

        $validated['course_id'] = $course->id;
        Lesson::create($validated);

        return back()->with('success', 'Thêm bài học thành công!');
    }

    public function destroy(Lesson $lesson)
    {
        // (Tùy chọn) Xóa file PDF khỏi storage nếu có
        // if ($lesson->pdf_path) {
        //     Storage::disk('public')->delete($lesson->pdf_path);
        // }

        $lesson->delete();

        return back()->with('success', 'Xóa bài học thành công!');
    }
}