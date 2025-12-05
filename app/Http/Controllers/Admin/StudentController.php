<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%$keyword%"])
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $allStudents = $query->latest()->paginate(15)->appends($request->query());

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
