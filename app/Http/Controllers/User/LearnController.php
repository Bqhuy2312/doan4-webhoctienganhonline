<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearnController extends Controller
{
    public function show(Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->update(['last_viewed_lesson_id' => $lesson->id]);

        $isRetry = $request->input('retry', false);

        $course->load('sections.lessons');

        $latestAttempt = null;
        if ($lesson->type == 'quiz' && $lesson->quiz_id) {
            $lesson->load('quiz.questions.options');

            $latestAttempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $lesson->quiz_id)
                ->latest()
                ->first();
        }

        $completedLessons = $user->completedLessons->pluck('id')->flip();

        return view('user.learn', [
            'course' => $course,
            'currentLesson' => $lesson,
            'completedLessons' => $completedLessons,
            'latestAttempt' => $latestAttempt,
            'isRetry' => $isRetry,
        ]);
    }

    public function resume(Course $course)
    {
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $lessonToResume = null;

        if ($enrollment->last_viewed_lesson_id) {
            $lessonToResume = Lesson::find($enrollment->last_viewed_lesson_id);
        }

        if (!$lessonToResume) {
            $lessonToResume = $course->lessons()->orderBy('id', 'asc')->first();
        }

        if (!$lessonToResume) {
            return back()->withErrors(['error' => 'Khóa học này hiện chưa có nội dung.']);
        }

        return redirect()->route('user.learn', ['course' => $course->id, 'lesson' => $lessonToResume->id]);
    }
}