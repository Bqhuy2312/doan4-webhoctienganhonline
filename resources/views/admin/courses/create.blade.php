@extends('admin.layout')

@section('title', 'Tạo khóa học mới')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_create_course.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Tạo khóa học mới</h1>
    </div>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-container">
            <div class="main-form">
                <div class="form-card">
                    <div class="form-group">
                        <label for="title">Tên khóa học</label>
                        <input type="text" id="title" name="title" placeholder="Ví dụ: IELTS Foundation" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả chi tiết</label>
                        <textarea id="description" name="description"
                            placeholder="Giới thiệu về nội dung, mục tiêu của khóa học..."></textarea>
                    </div>
                </div>

                <div class="form-card">
                    <label>Ảnh bìa khóa học</label>
                    <div class="image-upload-box" onclick="document.getElementById('thumbnail').click();">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <div class="upload-icon">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <p>Kéo thả file hoặc <strong>nhấn vào đây</strong> để tải ảnh lên</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-options">
                <div class="form-card">
                    <div class="form-group">
                        <label for="category_id">Danh mục</label>
                        <select id="category_id" name="category_id">
                            <option value="">-- Không có danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Học phí (VNĐ)</label>
                        <input type="number" id="price" name="price" placeholder="Nhập 0 nếu miễn phí" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="student_limit">Giới hạn học viên</label>
                        <input type="number" id="student_limit" name="student_limit"
                            placeholder="Để trống nếu không giới hạn" min="1">
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status">
                            <option value="1" selected>Hoạt động</option>
                            <option value="0">Tạm ẩn</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Lưu khóa học</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection