<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $quiz) {
            $question = $quiz->questions()->create([
                'question_text' => $request->question_text,
            ]);

            foreach ($request->options as $index => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => ($index == $request->correct_option),
                ]);
            }
        });

        return back()->with('success', 'Thêm câu hỏi thành công!');
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer',
        ]);
        
        DB::transaction(function () use ($request, $question) {
            $question->update(['question_text' => $request->question_text]);

            $question->options()->delete();

            foreach ($request->options as $index => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => ($index == $request->correct_option),
                ]);
            }
        });

        return back()->with('success', 'Cập nhật câu hỏi thành công!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Xóa câu hỏi thành công!');
    }
}