<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function index(Quiz $quiz)
    {
        $quizAttempts = $quiz->quizAttempts()->with('user')->latest()->paginate(15);

        $totalQuestions = $quiz->questions()->count();
        
        return view('admin.quiz.result', [
            'quiz' => $quiz,
            'quizAttempts' => $quizAttempts,
            'totalQuestions' => $totalQuestions,
        ]);
    }
}