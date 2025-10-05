@extends('admin.layout')

@section('title', 'Kết quả Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz_results.css') }}">
@endpush

@section('content')

    {{-- @php
    $quizName = "Bài kiểm tra cuối khóa IELTS Foundation";
    $results = [
    (object) ['student_name' => 'Nguyễn Thu Trang', 'score' => '18/20', 'percentage' => '90%', 'submitted_at' => '25/09/2025
    10:30'],
    (object) ['student_name' => 'Trần Minh Hoàng', 'score' => '15/20', 'percentage' => '75%', 'submitted_at' => '25/09/2025
    11:15'],
    (object) ['student_name' => 'Lê Thị Kim Anh', 'score' => '19/20', 'percentage' => '95%', 'submitted_at' => '26/09/2025
    08:00'],
    ];
    @endphp --}}

    <div class="page-header">
        <h1>Kết quả Quiz</h1>
        <h2 class="quiz-subtitle">Quiz: {{ $quiz->title }}</h2>
    </div>

    <div class="table-container">
        <table class="results-table">
            <thead>
                <tr>
                    <th>Tên học viên</th>
                    <th>Điểm số</th>
                    <th>Tỷ lệ đúng</th>
                    <th>Ngày nộp bài</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizAttempts as $attempt)
                    <tr>
                        <td class="student-name">{{ $attempt->user->name }}</td>
                        <td class="score">{{ $attempt->score }} / {{ $totalQuestions }}</td>
                        <td>
                            @if ($totalQuestions > 0)
                                {{ round(($attempt->score / $totalQuestions) * 100) }}%
                            @else
                                0%
                            @endif
                        </td>
                        <td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Chưa có học viên nào làm bài quiz này.</td>
                    </tr>
                @endforelse
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 1.5rem;">
        {{ $quizAttempts->links() }}
    </div>
@endsection