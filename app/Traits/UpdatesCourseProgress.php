<?php

namespace App\Traits;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

trait UpdatesCourseProgress
{
    protected function updateProgress(User $user, Course $course)
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons == 0) {
            return;
        }

        $completedLessons = $user->completedLessons()
                                 ->whereIn('lesson_id', $course->lessons->pluck('id'))
                                 ->count();
        
        $progress = round(($completedLessons / $totalLessons) * 100);

        Enrollment::where('user_id', $user->id)
                  ->where('course_id', $course->id)
                  ->update(['progress' => $progress]);
    }
}