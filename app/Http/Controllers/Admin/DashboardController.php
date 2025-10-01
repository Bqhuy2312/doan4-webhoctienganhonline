<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

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

        // 2. Biểu đồ tròn: Tỷ lệ hoàn thành (hiện tại dùng dữ liệu giả)
        // Logic thực tế cho phần này sẽ phức tạp hơn, cần CSDL để theo dõi tiến độ
        $completionRateData = [
            'labels' => ['Đã hoàn thành', 'Đang học', 'Chưa bắt đầu'],
            'values' => [65, 25, 10]
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