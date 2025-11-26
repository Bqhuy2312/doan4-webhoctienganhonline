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
            // ĐỌC NỘI DUNG TỪ FILE WORD
            $phpWord = IOFactory::load($file->getPathname());
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }

            // PHÂN TÍCH VĂN BẢN
            $questionsData = $this->parseQuizText($text);

            if (empty($questionsData)) {
                return back()->withErrors(['error' => 'Không tìm thấy câu hỏi nào hoặc file không đúng định dạng.']);
            }

            // LƯU VÀO CSDL (Sử dụng transaction để đảm bảo an toàn)
            DB::transaction(function () use ($quizTitle, $questionsData) {
                $quiz = $this->quiz->create(['title' => $quizTitle]);

                foreach ($questionsData as $qData) {

                    $question = $quiz->questions()->create([
                        'question_text' => $qData['question'],
                        'type' => $qData['type']
                    ]);

                    // MULTIPLE CHOICE + FILL
                    if ($qData['type'] !== 'ordering') {
                        $question->options()->createMany($qData['options']);
                    }

                    // ORDERING
                    else {
                        foreach ($qData['options'] as $opt) {
                            $question->options()->create([
                                'option_text' => $opt['option_text'],
                                'order' => $opt['order']
                            ]);
                        }
                    }
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
        $current = null;

        $lines = preg_split('/(\r\n|\n)/', trim($text));

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '')
                continue;

            if (preg_match('/^(\d+)\.\s*(.+)/', $line, $m)) {
                if ($current !== null) {
                    $questionsData[] = $current;
                }

                $current = [
                    'question' => $m[2],
                    'type' => 'multiple_choice',
                    'options' => []
                ];
                continue;
            }

            if (!$current)
                continue;

            if (preg_match('/^ANSWER:\s*(.*)/i', $line, $m)) {
                $current['type'] = 'fill_in_blank';
                $current['options'] = [];

                $answerLine = trim($m[1]);

                // TRƯỜNG HỢP: ANSWER: cat | under | blue
                if ($answerLine !== '') {
                    $parts = array_map('trim', explode('|', $answerLine));
                    $index = 1;
                    foreach ($parts as $p) {
                        $current['options'][] = [
                            'option_text' => $p,
                            'blank_index' => $index++,
                            'is_correct' => true,
                        ];
                    }
                } else {
                    // TRƯỜNG HỢP: ANSWER: xuống dòng
                    $mode = 'fill';
                    continue;
                }

                continue;
            }

            // NẾU ĐANG Ở MODE FILL, MỖI DÒNG LÀ 1 ĐÁP ÁN
            if (isset($mode) && $mode === 'fill') {
                if ($line === '' || preg_match('/^\d+\./', $line)) {
                    unset($mode);
                } else {
                    $current['options'][] = [
                        'option_text' => trim($line),
                        'blank_index' => count($current['options']) + 1,
                        'is_correct' => true
                    ];
                    continue;
                }
            }

            if (preg_match('/^ORDER:\s*(.+)/i', $line, $m)) {
                $current['type'] = 'ordering';

                $parts = explode('|', $m[1]);
                $order = 1;

                foreach ($parts as $p) {
                    $current['options'][] = [
                        'option_text' => trim($p),
                        'order' => $order++
                    ];
                }
                continue;
            }

            if (preg_match('/^(\d+)[\.\)]\s*(.+)/', $line, $m)) {
                if ($current['type'] !== 'ordering') {
                    $current['type'] = 'ordering';
                    $current['options'] = [];
                }

                $current['options'][] = [
                    'option_text' => trim($m[2]),
                    'order' => intval($m[1])
                ];
                continue;
            }

            if (preg_match('/^(\*?)([A-Z])\.\s*(.+)/', $line, $m)) {
                $isCorrect = $m[1] === '*';

                $current['type'] = 'multiple_choice';

                $current['options'][] = [
                    'option_text' => trim($m[3]),
                    'is_correct' => $isCorrect
                ];
            }
        }

        if ($current !== null) {
            $questionsData[] = $current;
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