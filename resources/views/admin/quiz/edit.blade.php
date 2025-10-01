@extends('admin.layout')

@section('title', 'Chỉnh sửa Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_create_course.css') }}">
@endpush

@section('content')

@php
$quiz = (object)[
    'id' => 1,
    'title' => 'Bài kiểm tra cuối khóa IELTS Foundation',
    'description' => 'Bài quiz này dùng để đánh giá kiến thức tổng hợp của học viên sau khi hoàn thành khóa học.',
    'course_id' => 1,
];
@endphp
    <div class="page-header">
        <h1>Chỉnh sửa bài Quiz</h1>
    </div>

    <form action="#" method="POST">
        @csrf
        @method('PUT')

        <div class="form-container">
            <div class="main-form">
                <div class="form-card">
                    <div class="form-group">
                        <label for="title">Tên bài Quiz</label>
                        <input type="text" id="title" name="title" value="{{ $quiz->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả ngắn</label>
                        <textarea id="description" name="description">{{ $quiz->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="sidebar-options">
                <div class="form-card">
                    <div class="form-group">
                        <label for="course_id">Thuộc khóa học</label>
                        <select id="course_id" name="course_id" required>
                            <option value="1" @if($quiz->course_id == 1) selected @endif>IELTS Foundation - Xây dựng nền tảng vững chắc</option>
                            <option value="2" @if($quiz->course_id == 2) selected @endif>TOEIC 500+ Cấp tốc trong 3 tháng</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection