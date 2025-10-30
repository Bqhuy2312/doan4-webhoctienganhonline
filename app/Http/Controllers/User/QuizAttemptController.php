<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Lesson; // <-- Đảm bảo bạn đã use
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;
use App\Traits\UpdatesCourseProgress; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizAttemptController extends Controller
{
    use UpdatesCourseProgress; 

    public function store(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $answers = $request->input('question', []);
        
        $lesson = Lesson::find($request->input('lesson_id'));

        $questions = $quiz->questions()->with('options')->get();
        $totalQuestions = $questions->count();
        $correctAnswersCount = 0;
        $attemptAnswersData = [];

        foreach ($questions as $question) {
            $correctOptionId = $question->options->where('is_correct', true)->first()->id;
            $userOptionId = $answers[$question->id] ?? null;
            $isCorrect = ($userOptionId == $correctOptionId);
            if ($isCorrect) {
                $correctAnswersCount++;
            }
            $attemptAnswersData[] = [
                'question_id' => $question->id,
                'option_id' => $userOptionId,
                'is_correct' => $isCorrect,
            ];
        }

        $score = ($totalQuestions > 0) ? round(($correctAnswersCount / $totalQuestions) * 100) : 0;
        
        DB::transaction(function () use (
            $user, 
            $quiz, 
            $score, 
            $totalQuestions, 
            $correctAnswersCount, 
            $attemptAnswersData, 
            $lesson // <-- Thêm biến $lesson vào đây
        ) {
            
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => $score,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswersCount,
            ]);
            $attempt->attemptAnswers()->createMany($attemptAnswersData);
            
            if ($lesson) {
                $user->completedLessons()->syncWithoutDetaching($lesson->id);
                $course = $lesson->section->course;
                $this->updateProgress($user, $course);
            }

        });
        
        return redirect()->route('user.learn', [
            'course' => $lesson->section->course_id, 
            'lesson' => $lesson->id
        ]);
    }
}