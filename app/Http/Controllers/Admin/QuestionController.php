<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    // public function store(Request $request, Quiz $quiz)
    // {
    //     $request->validate([
    //         'question_text' => 'required|string',
    //         'options' => 'required|array|min:2',
    //         'options.*' => 'required|string',
    //         'correct_option' => 'required|integer',
    //     ]);

    //     DB::transaction(function () use ($request, $quiz) {
    //         $question = $quiz->questions()->create([
    //             'question_text' => $request->question_text,
    //         ]);

    //         foreach ($request->options as $index => $optionText) {
    //             $question->options()->create([
    //                 'option_text' => $optionText,
    //                 'is_correct' => ($index == $request->correct_option),
    //             ]);
    //         }
    //     });

    //     return back()->with('success', 'Thêm câu hỏi thành công!');
    // }
    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,fill_in_blank,ordering',
        ]);

        DB::transaction(function () use ($request, $quiz, $validated) {

            // Tạo câu hỏi với 'type'
            $question = $quiz->questions()->create([
                'question_text' => $validated['question_text'],
                'type' => $validated['type'],
            ]);

            // Dùng SWITCH để lưu Options
            switch ($validated['type']) {

                // TRẮC NGHIỆM
                case 'multiple_choice':
                    $request->validate([
                        'options' => 'required|array|min:2',
                        'options.*' => 'required|string',
                        'correct_option' => 'required|integer',
                    ]);

                    foreach ($request->options as $index => $optionText) {
                        $question->options()->create([
                            'option_text' => $optionText,
                            'is_correct' => ($index == $request->correct_option),
                        ]);
                    }
                    break;

                // ĐIỀN TỪ
                case 'fill_in_blank':
                    $request->validate([
                        'fill_in_blank_answer' => 'required|string',
                    ]);

                    // Tạo một Option duy nhất chứa đáp án đúng
                    $question->options()->create([
                        'option_text' => $request->fill_in_blank_answer,
                        'is_correct' => true,
                        'order' => null, // Không dùng order
                    ]);
                    break;

                //  SẮP XẾP CÂU
                case 'ordering':
                    $request->validate([
                        'ordering_options' => 'required|array|min:2',
                        'ordering_options.*.text' => 'required|string',
                        'ordering_options.*.order' => 'required|integer',
                    ]);

                    foreach ($request->ordering_options as $option) {
                        $question->options()->create([
                            'option_text' => $option['text'],
                            'is_correct' => false,
                            'order' => $option['order'],
                        ]);
                    }
                    break;
            }
        });

        return back()->with('success', 'Thêm câu hỏi thành công!');
    }

    // public function update(Request $request, Question $question)
    // {
    //     $request->validate([
    //         'question_text' => 'required|string',
    //         'options' => 'required|array|min:2',
    //         'options.*' => 'required|string',
    //         'correct_option' => 'required|integer',
    //     ]);

    //     DB::transaction(function () use ($request, $question) {
    //         $question->update(['question_text' => $request->question_text]);

    //         $question->options()->delete();

    //         foreach ($request->options as $index => $optionText) {
    //             $question->options()->create([
    //                 'option_text' => $optionText,
    //                 'is_correct' => ($index == $request->correct_option),
    //             ]);
    //         }
    //     });

    //     return back()->with('success', 'Cập nhật câu hỏi thành công!');
    // }
    public function update(Request $request, Question $question)
    {
        // Validate các trường chung
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,fill_in_blank,ordering',
        ]);

        DB::transaction(function () use ($request, $question, $validated) {

            // Cập nhật câu hỏi
            $question->update([
                'question_text' => $validated['question_text'],
                'type' => $validated['type'],
            ]);

            // Xóa tất cả Option cũ
            $question->options()->delete();

            // Tạo lại Options (Logic y hệt hàm store)
            switch ($validated['type']) {

                case 'multiple_choice':
                    $request->validate([
                        'options' => 'required|array|min:2',
                        'options.*' => 'required|string',
                        'correct_option' => 'required|integer',
                    ]);
                    foreach ($request->options as $index => $optionText) {
                        $question->options()->create([
                            'option_text' => $optionText,
                            'is_correct' => ($index == $request->correct_option),
                        ]);
                    }
                    break;

                case 'fill_in_blank':
                    $request->validate(['fill_in_blank_answer' => 'required|string']);
                    $question->options()->create([
                        'option_text' => $request->fill_in_blank_answer,
                        'is_correct' => true,
                    ]);
                    break;

                case 'ordering':
                    $request->validate([
                        'ordering_options' => 'required|array|min:2',
                        'ordering_options.*.text' => 'required|string',
                        'ordering_options.*.order' => 'required|integer',
                    ]);
                    foreach ($request->ordering_options as $option) {
                        $question->options()->create([
                            'option_text' => $option['text'],
                            'order' => $option['order'],
                        ]);
                    }
                    break;
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