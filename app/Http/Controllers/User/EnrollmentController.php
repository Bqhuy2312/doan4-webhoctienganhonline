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

        // Kiểm tra xem khóa học CÓ PHÍ hay không
        // Nếu có phí, không cho phép đăng ký qua route này (phải qua thanh toán)
        if ($course->price > 0) {
            return back()->withErrors(['error' => 'Khóa học này có phí. Vui lòng thực hiện thanh toán.']);
        }

        // Kiểm tra sĩ số
        if ($course->student_limit > 0) {
            $currentEnrollments = $course->enrollments()->count();
            if ($currentEnrollments >= $course->student_limit) {
                return back()->withErrors(['error' => 'Rất tiếc, khóa học này đã đủ sĩ số.']);
            }
        }

        // Tạo đăng ký
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0
        ]);

        // Thông báo Admin
        $this->notifyAdminUserEnrolled($course, $user);
        $this->checkAndNotifyAdminIfCourseIsFull($course);

        $firstLesson = $course->lessons()->orderBy('id', 'asc')->first();
        if (!$firstLesson) {
            return back()->withErrors(['error' => 'Khóa học này chưa có nội dung, vui lòng quay lại sau.']);
        }

        return redirect()->route('user.learn', ['course' => $course, 'lesson' => $firstLesson]);
    }

    private function checkAndNotifyAdminIfCourseIsFull(Course $course)
    {
        if ($course->student_limit <= 0)
            return;

        $currentEnrollments = $course->enrollments()->count();

        if ($currentEnrollments < $course->student_limit)
            return;

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {

            $message = "Khóa học '{$course->title}' đã đạt sĩ số tối đa ({$currentEnrollments}).";

            $notification = Notification::create([
                'user_id' => $admin->id,
                'message' => $message,
                'url' => route('admin.courses.show', $course->id),
                'read_at' => null
            ]);

            broadcast(new NewNotification($notification));
        }
    }

    private function notifyAdminUserEnrolled(Course $course, User $user)
    {
        // Danh sách admin
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {

            // Tạo thông báo trong DB
            $notification = Notification::create([
                'user_id' => $admin->id,
                'message' => "{$user->name} đã đăng ký khóa học: {$course->title}",
                'url' => route('admin.courses.show', $course->id),
                'read_at' => null
            ]);

            // Bắn realtime
            broadcast(new NewNotification($notification));
        }
    }
}