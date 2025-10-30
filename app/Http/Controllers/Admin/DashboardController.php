<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'user')->count();

        $activeCourses = Course::where('is_active', true)->count();

        $newStudentsThisWeek = User::where('role', 'user')
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $topCourses = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(6)
            ->get();

        $studentsPerCourseData = [

            'labels' => $topCourses->pluck('title'),
            'values' => $topCourses->pluck('students_count')
        ];

        $completionStats = Enrollment::select(
            DB::raw('COUNT(CASE WHEN progress = 100 THEN 1 END) as completed'),
            DB::raw('COUNT(CASE WHEN progress > 0 AND progress < 100 THEN 1 END) as in_progress'),
            DB::raw('COUNT(CASE WHEN progress = 0 THEN 1 END) as not_started')
        )
            ->first();

        $completionRateData = [
            'labels' => ['Đã hoàn thành', 'Đang học', 'Chưa bắt đầu'],
            'values' => [
                $completionStats->completed,
                $completionStats->in_progress,
                $completionStats->not_started
            ]
        ];

        return view('admin.dashboard', compact(
            'totalStudents',
            'activeCourses',
            'newStudentsThisWeek',
            'studentsPerCourseData',
            'completionRateData'
        ));
    }
}