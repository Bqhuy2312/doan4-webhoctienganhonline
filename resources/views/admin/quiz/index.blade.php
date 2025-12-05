@extends('admin.layout')

@section('title', 'Quản lý Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz.css') }}">
@endpush

@section('content')

    <div class="page-header">
        <h1>Danh sách Quiz</h1>
        <div class="head-actions">
            <a href="{{ route('admin.quizzes.create') }}" class="add-new-btn"><i class="fa-solid fa-plus"></i> Thêm Quiz
                mới</a>
            <a href="#" class="add-new-btn" data-bs-toggle="modal" data-bs-target="#importQuizModal" role="button">
                <i class="fa-solid fa-file-import"></i> Import từ Word
            </a>
        </div>

        <div class="modal fade" id="importQuizModal" tabindex="-1" aria-labelledby="importQuizModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importQuizModalLabel">Import bài Quiz từ file Word</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.quizzes.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="quiz_title" class="form-label"><strong>Tiêu đề bài Quiz mới</strong></label>
                                <input type="text" class="form-control" name="quiz_title" id="quiz_title" required>
                            </div>
                            <div class="mb-3">
                                <label for="word_file" class="form-label"><strong>Chọn file Word (.docx)</strong></label>
                                <input class="form-control" type="file" name="word_file" id="word_file" accept=".docx"
                                    required>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <strong>Lưu ý:</strong> File Word phải tuân thủ đúng định dạng đã được quy định.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Bắt đầu Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.quizzes.index') }}" class="filter-form">

            <input type="text" name="keyword" placeholder="Tìm theo tên Quiz..." value="{{ request('keyword') }}">

            <input type="date" name="from_date" value="{{ request('from_date') }}">
            <input type="date" name="to_date" value="{{ request('to_date') }}">

            <button type="submit">Lọc</button>
            <a href="{{ route('admin.quizzes.index') }}" class="clear-btn">Xóa lọc</a>

        </form>
    </div>

    <div class="table-container">
        <table class="quizzes-table">
            <thead>
                <tr>
                    <th>Tên bài Quiz</th>
                    <th>Số câu hỏi</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                    <tr>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->questions_count }}</td>
                        <td>{{ $quiz->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="action-btn"
                                    title="Quản lý câu hỏi">
                                    <i class="fa-solid fa-list-check"></i> <span>Câu hỏi</span>
                                </a>
                                <a href="{{ route('admin.quizzes.results', $quiz->id) }}" class="action-btn"
                                    title="Xem kết quả">
                                    <i class="fa-solid fa-square-poll-vertical"></i> <span>Kết quả</span>
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="action-btn" title="Sửa"><i
                                        class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" title="Xóa"><i
                                            class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Chưa có bài quiz nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-container">{{ $quizzes->links() }}</div>
@endsection