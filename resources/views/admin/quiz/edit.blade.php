@extends('admin.layout')

@section('title', 'Chỉnh sửa Quiz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_create_course.css') }}">
@endpush

@section('content')

    <div class="page-header">
        <h1>Chỉnh sửa bài Quiz</h1>
    </div>
    <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-container">
            <div class="main-form">
                <div class="form-card">
                    <div class="form-group">
                        <label for="title">Tên bài Quiz</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả ngắn</label>
                        <textarea id="description"
                            name="description">{{ old('description', $quiz->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="sidebar-options">
                <div class="form-card">
                    <div class="form-actions">
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection