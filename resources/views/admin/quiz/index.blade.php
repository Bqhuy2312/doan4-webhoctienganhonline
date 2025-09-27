@extends('admin.layout')

@section('title', 'Quản lý Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz.css') }}">
@endpush

@section('content')

    @php
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
    @endphp

    <div class="page-header">
        <h1>Quản lý Quiz</h1>
        <a href="#" class="add-new-btn"><i class="fa-solid fa-plus" style="text-decoration: none;"></i> Tạo Quiz mới</a>
    </div>

    <div class="table-container">
        <table class="quizzes-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Bài Quiz</th>
                    <th>Số câu hỏi</th>
                    <th>Ngày tạo</th>
                    <th style="width: 20%;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                    <tr>
                        <td>
                            <div class="quiz-info">
                                <div class="quiz-title">{{ $quiz->title }}</div>
                                <a href="{{ route('admin.courses.show', $quiz->course->id) }}"
                                    class="course-link">{{ $quiz->course->title }}</a>
                            </div>
                        </td>
                        <td>{{ $quiz->questions_count }}</td>
                        <td>{{ $quiz->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="action-btn" title="Quản lý các câu hỏi của quiz này">
                                    <i class="fa-solid fa-list-check"></i>
                                    <span>Câu hỏi</span>
                                </a>

                                <a href="#" class="action-btn" title="Xem kết quả làm bài của học viên">
                                    <i class="fa-solid fa-square-poll-vertical"></i>
                                    <span>Kết quả</span>
                                </a>

                                <a href="#" class="action-btn" title="Sửa thông tin quiz">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>

                                <form action="#" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài quiz này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" title="Xóa quiz">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Chưa có bài quiz nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection