<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $allStudents = User::where('role', '!=', 'admin')->latest()->paginate(15);
        return view('admin.students.index', compact('allStudents'));
    }

    public function show(User $user)
    {
        $enrolledCourses = $user->enrollments()->with('course.lessons')->get()->pluck('course');

        $coursesWithProgress = $enrolledCourses->map(function ($course) use ($user) {

            $totalLessons = $course->lessons->count();

            if ($totalLessons > 0) {
                $completedLessons = $user->completedLessons()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->count();

                $course->progress = round(($completedLessons / $totalLessons) * 100);
            } else {
                $course->progress = 0;
            }

            return $course;
        });

        return view('admin.students.show', [
            'student' => $user,
            'enrolledCourses' => $coursesWithProgress
        ]);
    }
}
