@extends('admin.layout')

@section('title', 'Nội dung khóa học')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_course_show.css') }}">
@endpush

@section('content')

    @php
        $courseName = "IELTS Foundation - Xây dựng nền tảng vững chắc";
        $sections = [
            (object) [
                'title' => 'Chương 1: Giới thiệu về IELTS',
                'lessons' => [
                    (object) ['type' => 'video', 'title' => 'Bài 1: IELTS là gì? Cấu trúc bài thi'],
                    (object) ['type' => 'video', 'title' => 'Bài 2: Các thang điểm và cách tính điểm'],
                    (object) ['type' => 'pdf', 'title' => 'Tài liệu: Tổng hợp các chủ đề thường gặp'],
                ]
            ],
            (object) [
                'title' => 'Chương 2: Kỹ năng Listening',
                'lessons' => [
                    (object) ['type' => 'video', 'title' => 'Bài 1: Dạng bài Multiple Choice'],
                    (object) ['type' => 'video', 'title' => 'Bài 2: Dạng bài Map Labeling'],
                    (object) ['type' => 'quiz', 'title' => 'Kiểm tra từ vựng chương 2'],
                ]
            ],
            (object) [
                'title' => 'Chương 3: Kỹ năng Reading',
                'lessons' => [
                    (object) ['type' => 'video', 'title' => 'Bài 1: Kỹ thuật Skimming và Scanning'],
                    (object) ['type' => 'pdf', 'title' => 'Bài đọc mẫu và giải thích chi tiết'],
                    (object) ['type' => 'quiz', 'title' => 'Bài kiểm tra cuối khóa'],
                ]
            ],
        ];
    @endphp

    <div class="page-header">
        <h1>Quản lý nội dung</h1>
        <h2 class="course-subtitle">{{ $courseName }}</h2>
    </div>

    <div class="page-actions">
        <a href="#" class="action-btn"><i class="fa-solid fa-plus"></i> Thêm chương mới</a>
        <a href="#" class="action-btn"><i class="fa-solid fa-video"></i> Thêm Video</a>
        <a href="#" class="action-btn"><i class="fa-solid fa-file-pdf"></i> Thêm PDF</a>
        <a href="#" class="action-btn"><i class="fa-solid fa-square-check"></i> Thêm Quiz</a>
    </div>

    @foreach ($sections as $section)
        <div class="content-section">
            <div class="section-header">
                <h3>{{ $section->title }}</h3>
                <div class="lesson-actions">
                    <a href="#" title="Sửa tên chương"><i class="fa-solid fa-pencil"></i></a>
                    <a href="#" title="Xóa chương"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
            <ul class="lesson-list">
                @foreach ($section->lessons as $lesson)
                    <li class="lesson-item">
                        <span class="drag-handle" title="Kéo thả để sắp xếp"><i class="fa-solid fa-grip-vertical"></i></span>
                        @if ($lesson->type == 'video')
                            <i class="fa-solid fa-circle-play lesson-icon icon-video"></i>
                        @elseif ($lesson->type == 'pdf')
                            <i class="fa-solid fa-file-lines lesson-icon icon-pdf"></i>
                        @elseif ($lesson->type == 'quiz')
                            <i class="fa-solid fa-square-check lesson-icon icon-quiz"></i>
                        @endif
                        <span class="lesson-title">{{ $lesson->title }}</span>
                        <div class="lesson-actions">
                            <a href="#" title="Sửa"><i class="fa-solid fa-pencil"></i></a>
                            <a href="#" title="Xóa"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

@endsection