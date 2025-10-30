<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        $isEnrolled = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->exists();

        if ($isEnrolled) {
            return back()->withErrors(['error' => 'Bạn đã đăng ký khóa học này rồi.']);
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        $firstLesson = $course->lessons()->orderBy('id', 'asc')->first();

        if (!$firstLesson) {
            return back()->withErrors(['error' => 'Khóa học này chưa có nội dung, vui lòng quay lại sau.']);
        }

        // *** QUAN TRỌNG: Chuyển hướng đến trang "Vào học" ***
        return redirect()->route('user.learn', ['course' => $course, 'lesson' => $firstLesson]);
    }
}