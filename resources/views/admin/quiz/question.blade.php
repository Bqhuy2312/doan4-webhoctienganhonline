@extends('admin.layout')

@section('title', 'Quản lý câu hỏi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz_questions.css') }}">
@endpush

@section('content')

    {{-- @php
    $quizName = "Bài kiểm tra cuối khóa IELTS Foundation";
    $questions = [
    (object) [
    'id' => 1,
    'text' => 'Which of the following is a synonym for "ubiquitous"?',
    'options' => ['Rare', 'Everywhere', 'Hidden', 'Complex'],
    'correct_option' => 'Everywhere'
    ],
    (object) [
    'id' => 2,
    'text' => 'Fill in the blank: The company decided to ___ its operations to Asia.',
    'options' => ['Expand', 'Shrink', 'Sell', 'Close'],
    'correct_option' => 'Expand'
    ],
    ];
    @endphp --}}

    <div class="page-header">
        <h1>Quản lý câu hỏi</h1>
        <h2 class="quiz-subtitle">Quiz: {{ $quiz->title }}</h2>
    </div>

    @forelse ($quiz->questions as $index => $question)
        <div class="question-card">
            <div class="question-header">
                <div class="question-text"><strong>Câu {{ $index + 1 }}:</strong> {{ $question->question_text }}</div>
                <div class="question-actions">
                    <a href="#"
                        onclick="openEditModal({{ $question->id }}, `{{ e($question->question_text) }}`, {{ json_encode($question->options) }})"
                        title="Sửa"><i class="fa-solid fa-pencil"></i></a>
                    <form action="{{ route('admin.quizzes.questions.destroy', $question->id) }}" method="POST"
                        onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-icon-btn" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <ul class="answer-options">
                @foreach ($question->options as $option)
                    <li class="{{ $option->is_correct ? 'correct-answer' : '' }}">{{ $option->option_text }}</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p style="text-align: center;">Chưa có câu hỏi nào. Hãy thêm một câu hỏi mới.</p>
    @endforelse

    <a href="#" class="add-question-btn" onclick="openModal('addQuestionModal')"><i class="fa-solid fa-plus"></i> Thêm câu
        hỏi mới</a>

    <div id="addQuestionModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm câu hỏi mới</h3>
            </div>
            <form action="{{ route('admin.quizzes.questions.store', $quiz->id) }}" method="POST">
                @csrf
                <div class="form-group"><label>Nội dung câu hỏi</label><textarea name="question_text" required></textarea>
                </div>
                <div class="form-group">
                    <label>Các đáp án (chọn đáp án đúng)</label>
                    <div id="options-container">
                        <div class="option-group"><input type="radio" name="correct_option" value="0" required><input
                                type="text" name="options[]" placeholder="Đáp án A" required></div>
                        <div class="option-group"><input type="radio" name="correct_option" value="1" required><input
                                type="text" name="options[]" placeholder="Đáp án B" required></div>
                    </div>
                    <button type="button" class="add-option-btn" onclick="addOption()">Thêm đáp án</button>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addQuestionModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editQuestionModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sửa câu hỏi</h3><button class="close-modal" onclick="closeModal('editQuestionModal')">&times;</button>
            </div>
            <form id="editQuestionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group"><label>Nội dung câu hỏi</label><textarea name="question_text"
                        id="edit_question_text" required></textarea></div>
                <div class="form-group">
                    <label>Các đáp án (chọn đáp án đúng)</label>
                    <div id="edit_options_container"></div>
                    <button type="button" class="add-option-btn" onclick="addEditOption()">Thêm đáp án</button>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editQuestionModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin_quiz_questions.js') }}"></script>
@endpush