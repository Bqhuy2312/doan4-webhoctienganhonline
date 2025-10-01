@extends('admin.layout')

@section('title', 'Tạo Quiz mới')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_create_course.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Tạo bài Quiz mới</h1>
    </div>

    <form action="#" method="POST">
        @csrf
        <div class="form-container">
            <div class="main-form">
                <div class="form-card">
                    <div class="form-group">
                        <label for="title">Tên bài Quiz</label>
                        <input type="text" id="title" name="title" placeholder="Ví dụ: Bài kiểm tra cuối khóa IELTS" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả ngắn (tùy chọn)</label>
                        <textarea id="description" name="description" placeholder="Hướng dẫn hoặc giới thiệu về bài quiz..."></textarea>
                    </div>
                </div>
            </div>

            <div class="sidebar-options">
                <div class="form-card">
                    <div class="form-group">
                        <label for="course_id">Thuộc khóa học</label>
                        <select id="course_id" name="course_id" required>
                            <option value="" disabled selected>-- Chọn khóa học --</option>
                            <option value="1">IELTS Foundation - Xây dựng nền tảng vững chắc</option>
                            <option value="2">TOEIC 500+ Cấp tốc trong 3 tháng</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu và Thêm câu hỏi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection