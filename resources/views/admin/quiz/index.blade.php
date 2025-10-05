@extends('admin.layout')

@section('title', 'Quản lý Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz.css') }}">
@endpush

@section('content')

    {{-- @php
    use Carbon\Carbon;
    $quizzes = [
    (object) [
    'id' => 1,
    'title' => 'Bài kiểm tra cuối khóa IELTS Foundation',
    'course' => (object) ['id' => 1, 'title' => 'IELTS Foundation - Xây dựng nền tảng vững chắc'],
    'questions_count' => 20,
    'created_at' => Carbon::parse('2025-09-25'),
    ],
    (object) [
    'id' => 2,
    'title' => 'Kiểm tra từ vựng Unit 1-5',
    'course' => (object) ['id' => 2, 'title' => 'TOEIC 500+ Cấp tốc trong 3 tháng'],
    'questions_count' => 50,
    'created_at' => Carbon::parse('2025-09-20'),
    ],
    (object) [
    'id' => 3,
    'title' => 'Quiz về các thì trong Tiếng Anh',
    'course' => (object) ['id' => 5, 'title' => 'Ngữ pháp tiếng Anh nâng cao toàn tập'],
    'questions_count' => 15,
    'created_at' => Carbon::parse('2025-09-18'),
    ],
    (object) [
    'id' => 4,
    'title' => 'Bài kiểm tra giữa kỳ Giao tiếp cơ bản',
    'course' => (object) ['id' => 3, 'title' => 'Giao tiếp cơ bản cho người mất gốc'],
    'questions_count' => 25,
    'created_at' => Carbon::parse('2025-09-10'),
    ],
    ];
    @endphp --}}

    <div class="page-header">
        <h1>Danh sách Quiz</h1>
        <a href="{{ route('admin.quizzes.create') }}" class="add-new-btn"><i class="fa-solid fa-plus"></i> Thêm Quiz mới</a>
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
                                <a href="{{ route('admin.quizzes.results', $quiz->id) }}" class="action-btn" title="Xem kết quả">
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