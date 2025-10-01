@extends('admin.layout')

@section('title', 'Quản lý câu hỏi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_quiz_questions.css') }}">
@endpush

@section('content')

@php
$quizName = "Bài kiểm tra cuối khóa IELTS Foundation";
$questions = [
    (object)[
        'id' => 1, 'text' => 'Which of the following is a synonym for "ubiquitous"?',
        'options' => ['Rare', 'Everywhere', 'Hidden', 'Complex'], 'correct_option' => 'Everywhere'
    ],
    (object)[
        'id' => 2, 'text' => 'Fill in the blank: The company decided to ___ its operations to Asia.',
        'options' => ['Expand', 'Shrink', 'Sell', 'Close'], 'correct_option' => 'Expand'
    ],
];
@endphp
    <div class="page-header">
        <h1>Quản lý câu hỏi</h1>
        <h2 class="quiz-subtitle">Quiz: {{ $quizName }}</h2>
    </div>

    @foreach ($questions as $index => $question)
    <div class="question-card">
        <div class="question-header">
            <div class="question-text"><strong>Câu {{ $index + 1 }}:</strong> {{ $question->text }}</div>
            <div class="question-actions">
                <a href="#" title="Sửa"><i class="fa-solid fa-pencil"></i></a>
                <a href="#" title="Xóa"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
        <ul class="answer-options">
            @foreach ($question->options as $option)
                <li class="{{ $option == $question->correct_option ? 'correct-answer' : '' }}">
                    {{ $option }}
                </li>
            @endforeach
        </ul>
    </div>
    @endforeach

    <a href="#" class="add-question-btn"><i class="fa-solid fa-plus"></i> Thêm câu hỏi mới</a>
@endsection