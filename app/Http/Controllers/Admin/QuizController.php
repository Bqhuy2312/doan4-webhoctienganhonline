<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::latest()->withCount('questions')->paginate(10);
        return view('admin.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        return view('admin.quiz.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.show', $quiz->id)
                         ->with('success', 'Tạo quiz thành công! Bây giờ hãy thêm câu hỏi.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options'); // Tải trước câu hỏi và các lựa chọn
        return view('admin.quiz.question', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        return view('admin.quiz.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.index')->with('success', 'Cập nhật quiz thành công!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Xóa quiz thành công!');
    }
}