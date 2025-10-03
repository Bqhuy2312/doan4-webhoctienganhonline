<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section = new Section();
        $section->title = $request->title;
        $section->course_id = $course->id;
        $section->save();

        return back()->with('success', 'Thêm chương mới thành công!');
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section->update([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Cập nhật chương thành công!');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return back()->with('success', 'Đã xóa chương thành công!');
    }
}