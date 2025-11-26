@extends('admin.layout')

@section('title', 'Quản lý câu hỏi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz_questions.css') }}">
@endpush

@section('content')

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
                        onclick="openEditModal({{ $question->id }}, `{{ e($question->question_text) }}`, {{ json_encode($question->options) }}, `{{ $question->type }}`)"
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

            <form action="{{ route('admin.quizzes.questions.store', $quiz->id) }}" method="POST" id="addQuestionForm">
                @csrf

                <div class="form-group">
                    <label for="question_text">Nội dung câu hỏi:</label>
                    <textarea name="question_text" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="question_type_select">Loại câu hỏi:</label>
                    <select name="type" id="question_type_select" class="form-select">
                        <option value="multiple_choice">Trắc nghiệm (1 đáp án)</option>
                        <option value="fill_in_blank">Điền từ</option>
                        <option value="ordering">Sắp xếp từ</option>
                    </select>
                </div>

                <hr>

                <!-- TRẮC NGHIỆM -->
                <div id="type-multiple_choice" class="question-type-container">
                    <div class="form-group">
                        <label>Các đáp án (chọn đáp án đúng)</label>
                        <div id="add_options_container">
                            <div id="options-container">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="correct_option"
                                                value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }}>
                                        </div>
                                        <input type="text" name="options[]" class="form-control"
                                            placeholder="Lựa chọn {{ $i + 1 }}">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ĐIỀN TỪ -->
                <div id="type-fill_in_blank" class="question-type-container" style="display: none;">
                    <div class="form-group">
                        <label for="fill_in_blank_answer" class="form-label">Đáp án đúng (Điền từ):</label>
                        <input type="text" name="fill_in_blank_answer" class="form-control"
                            placeholder="Nhập đáp án chính xác...">
                    </div>
                </div>

                <!-- SẮP XẾP TỪ -->
                <div id="type-ordering" class="question-type-container" style="display: none;">
                    <label class="form-label">Các mảnh câu (Sắp xếp):</label>
                    <div id="ordering-options-wrapper"></div>
                    <button type="button" id="add-ordering-option" class="btn btn-sm btn-outline-secondary mt-2">
                        + Thêm mảnh câu
                    </button>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addQuestionModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>

            </form> <!-- ĐÓNG FORM ĐÚNG CHỖ -->
        </div>
    </div>

    <div id="editQuestionModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sửa câu hỏi</h3>
                <button class="close-modal" onclick="closeModal('editQuestionModal')">&times;</button>
            </div>

            <form id="editQuestionForm" method="POST">
                @csrf
                @method('PUT')

                <!-- Nội dung -->
                <div class="form-group">
                    <label>Nội dung câu hỏi</label>
                    <textarea name="question_text" id="edit_question_text" required></textarea>
                </div>

                <!-- Chọn loại câu hỏi -->
                <div class="form-group">
                    <label for="edit_question_type_select">Loại câu hỏi:</label>
                    <select name="type" id="edit_question_type_select" class="form-select">
                        <option value="multiple_choice">Trắc nghiệm (1 đáp án)</option>
                        <option value="fill_in_blank">Điền từ</option>
                        <option value="ordering">Sắp xếp từ</option>
                    </select>
                </div>

                <hr>

                <!-- TRẮC NGHIỆM -->
                <div id="edit-multiple_choice" class="question-type-container">
                    <label>Các đáp án (chọn đáp án đúng)</label>
                    <div id="edit_options_container"></div>
                    <button type="button" class="add-option-btn" onclick="addEditOption()">Thêm đáp án</button>
                </div>

                <!-- ĐIỀN TỪ -->
                <div id="edit-fill_in_blank" class="question-type-container" style="display:none;">
                    <label>Đáp án đúng:</label>
                    <input type="text" id="edit_fill_in_blank_answer" name="fill_in_blank_answer" class="form-control">
                </div>

                <!-- SẮP XẾP TỪ -->
                <div id="edit-ordering" class="question-type-container" style="display:none;">
                    <label>Các mảnh câu:</label>
                    <div id="edit_ordering-options-wrapper"></div>

                    <button type="button" class="btn btn-sm btn-outline-secondary add-ordering-option-btn">
                        + Thêm mảnh câu
                    </button>
                </div>

                <!-- Nút -->
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