<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    protected $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }
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

    public function import(Request $request)
    {
        $request->validate([
            'quiz_title' => 'required|string|max:255',
            'word_file' => 'required|mimes:docx',
        ]);

        $file = $request->file('word_file');
        $quizTitle = $request->input('quiz_title');

        try {
            // 1. ĐỌC NỘI DUNG TỪ FILE WORD
            $phpWord = IOFactory::load($file->getPathname());
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }

            // 2. PHÂN TÍCH VĂN BẢN
            $questionsData = $this->parseQuizText($text);

            if (empty($questionsData)) {
                return back()->withErrors(['error' => 'Không tìm thấy câu hỏi nào hoặc file không đúng định dạng.']);
            }

            // 3. LƯU VÀO CSDL (Sử dụng transaction để đảm bảo an toàn)
            DB::transaction(function () use ($quizTitle, $questionsData) {
                $quiz = $this->quiz->create(['title' => $quizTitle]); // Giả sử bạn đã inject Quiz model

                foreach ($questionsData as $qData) {
                    $question = $quiz->questions()->create(['question_text' => $qData['question']]);
                    $question->options()->createMany($qData['options']);
                }
            });

            return redirect()->route('admin.quizzes.index')->with('success', 'Import bài quiz thành công!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    private function parseQuizText($text)
    {
        $questionsData = [];
        $currentQuestion = null;

        $lines = preg_split('/(\r\n|\n)/', trim($text));

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if (empty($trimmedLine)) {
                continue;
            }

            if (preg_match('/^(\d+)\.\s*(.*)/', $trimmedLine, $questionMatches)) {
                if ($currentQuestion !== null && !empty($currentQuestion['options'])) {
                    $questionsData[] = $currentQuestion;
                }

                $currentQuestion = [
                    'question' => $questionMatches[2],
                    'options' => [],
                ];
            }
            elseif (preg_match('/^(\*?)([A-Z])\.\s*(.*)/', $trimmedLine, $optionMatches)) {
                if ($currentQuestion !== null) {
                    $isCorrect = ($optionMatches[1] === '*');
                    $optionText = $optionMatches[3];

                    $currentQuestion['options'][] = [
                        'option_text' => $optionText,
                        'is_correct' => $isCorrect,
                    ];
                }
            }
        }

        if ($currentQuestion !== null && !empty($currentQuestion['options'])) {
            $questionsData[] = $currentQuestion;
        }

        return $questionsData;
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