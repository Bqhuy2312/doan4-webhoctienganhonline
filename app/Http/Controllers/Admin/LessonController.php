<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,quiz',
            'section_id' => 'required|exists:sections,id',
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

        Lesson::create($validated);

        return back()->with('success', 'Thêm bài học thành công!');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        if ($lesson->type === 'video') {
            $validated += $request->validate(['video_url' => 'required|url']);
        } elseif ($lesson->type === 'pdf') {
            if ($request->hasFile('pdf_file')) {
                $validated += $request->validate(['pdf_file' => 'required|file|mimes:pdf|max:61440']); // 60MB

                if ($lesson->pdf_path) {
                    Storage::disk('public')->delete($lesson->pdf_path);
                }

                $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                $validated['pdf_path'] = $path;
            }
        } elseif ($lesson->type === 'quiz') {
            $validated += $request->validate(['quiz_id' => 'required|exists:quizzes,id']);
        }

        $lesson->update($validated);

        return back()->with('success', 'Cập nhật bài học thành công!');
    }

    public function destroy(Lesson $lesson)
    {
        if ($lesson->pdf_path) {
            Storage::disk('public')->delete($lesson->pdf_path);
        }

        $lesson->delete();

        return back()->with('success', 'Xóa bài học thành công!');
    }
}