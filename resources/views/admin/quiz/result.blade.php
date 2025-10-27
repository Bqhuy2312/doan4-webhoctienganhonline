@extends('admin.layout')

@section('title', 'Kết quả Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz_results.css') }}">
@endpush

@section('content')

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