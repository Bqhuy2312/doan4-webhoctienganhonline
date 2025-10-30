<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Traits\UpdatesCourseProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonCompletionController extends Controller
{
    use UpdatesCourseProgress;

    public function store(Request $request, Lesson $lesson)
    {
        $user = Auth::user();
        $course = $lesson->section->course;

        $user->completedLessons()->syncWithoutDetaching($lesson->id);

        $this->updateProgress($user, $course);

        return response()->json(['success' => true]);
    }
}