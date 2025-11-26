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
            $submittedAnswer = $answers[$question->id];

            if ($question->type === 'multiple_choice' || $question->type === 'listening_choice') {

                // Logic chấm trắc nghiệm (so sánh $option->id)
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $submittedAnswer == $correctOption->id) {
                    $correctAnswersCount++;
                }

            } elseif ($question->type === 'fill_in_blank') {

                $correctAnswer = $question->options->where('is_correct', true)->first()->option_text;

                if (trim(strtolower($submittedAnswer)) === trim(strtolower($correctAnswer))) {
                    $correctAnswersCount++;
                }
            } elseif ($question->type === 'ordering') {

                $correctOrder = $question->options()
                    ->orderBy('order', 'asc')
                    ->pluck('id')
                    ->toArray();

                $submittedOrder = explode(',', $submittedAnswer);
                $submittedOrder = array_map('intval', $submittedOrder);

                if ($submittedOrder === $correctOrder) {
                    $correctAnswersCount++;
                }
            }
        }

        $score = ($totalQuestions > 0) ? round(($correctAnswersCount / $totalQuestions) * 100) : 0;

        DB::transaction(function () use ($user, $quiz, $score, $totalQuestions, $correctAnswersCount, $attemptAnswersData, $lesson // <-- Thêm biến $lesson vào đây
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