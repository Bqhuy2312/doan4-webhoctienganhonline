<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotification;
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

        if ($course->student_limit > 0) {
            $currentEnrollments = $course->enrollments()->count();
            if ($currentEnrollments >= $course->student_limit) {
                return back()->withErrors(['error' => 'Rất tiếc, khóa học này đã đủ sĩ số.']);
            }
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0
        ]);

        $this->checkAndNotifyAdminIfCourseIsFull($course);

        $firstLesson = $course->lessons()->orderBy('id', 'asc')->first();
        if (!$firstLesson) {
            return back()->withErrors(['error' => 'Khóa học này chưa có nội dung, vui lòng quay lại sau.']);
        }

        return redirect()->route('user.learn', ['course' => $course, 'lesson' => $firstLesson]);
    }

    private function checkAndNotifyAdminIfCourseIsFull(Course $course)
    {
        if ($course->student_limit > 0) {

            $currentEnrollments = $course->enrollments()->count();

            if ($currentEnrollments >= $course->student_limit) {

                $admin = User::find(1);
                $admins = $admin ? [$admin] : [];
                
                $message = "Khóa học '{$course->title}' đã đạt sĩ số tối đa ({$currentEnrollments}).";

                foreach ($admins as $admin) {
                    $notification = Notification::create([
                        'user_id' => $admin->id,
                        'message' => $message,
                        'url' => route('admin.courses.show', $course->id),
                    ]);

                    broadcast(new NewNotification($notification))->toOthers();
                }
            }
        }
    }
}