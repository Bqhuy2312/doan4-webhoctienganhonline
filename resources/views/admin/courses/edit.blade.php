@extends('admin.layout')

@section('title', 'Chỉnh sửa khóa học')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_courses.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin_course_form.css') }}">
@endpush

@section('content')

    @php
        $course = (object) [
            'id' => 1,
            'title' => 'IELTS Foundation - Xây dựng nền tảng vững chắc',
            'description' => 'Khóa học này được thiết kế để cung cấp cho học viên những kiến thức nền tảng nhất về kỳ thi IELTS, bao gồm cấu trúc bài thi, các dạng câu hỏi thường gặp và chiến lược làm bài cơ bản cho cả 4 kỹ năng: Nghe, Nói, Đọc, Viết.',
            'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
            'price' => 599000,
            'student_limit' => 50,
            'is_active' => true,
        ];
    @endphp

    <div class="page-header">
        <h1>Chỉnh sửa khóa học</h1>
    </div>

    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-container">
            <div class="main-form">
                <div class="form-card">
                    <div class="form-group">
                        <label for="title">Tên khóa học</label>
                        <input type="text" id="title" name="title" value="{{ $course->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả chi tiết</label>
                        <textarea id="description" name="description">{{ $course->description }}</textarea>
                    </div>
                </div>

                <div class="form-card">
                    <label>Ảnh bìa khóa học</label>
                    <div class="current-image-preview">
                        <p style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Ảnh hiện tại:</p>
                        <img src="{{ $course->thumbnail_url }}" alt="Ảnh bìa khóa học">
                    </div>
                    <hr style="margin: 1.5rem 0;">
                    <div class="image-upload-box" onclick="document.getElementById('thumbnail').click();">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <div class="upload-icon">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <p>Nhấn vào đây để <strong>thay đổi ảnh bìa</strong></p>
                    </div>
                </div>
            </div>

            <div class="sidebar-options">
                <div class="form-card">
                    <div class="form-group">
                        <label for="price">Học phí (VNĐ)</label>
                        <input type="number" id="price" name="price" value="{{ $course->price }}" required>
                    </div>
                    <div class="form-group">
                        <label for="student_limit">Giới hạn học viên</label>
                        <input type="number" id="student_limit" name="student_limit" value="{{ $course->student_limit }}"
                            min="1">
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status">
                            <option value="1" @if($course->is_active) selected @endif>Công khai (Hoạt động)</option>
                            <option value="0" @if(!$course->is_active) selected @endif>Bản nháp (Tạm ẩn)</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <a href="#" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection