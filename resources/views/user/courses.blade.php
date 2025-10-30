@extends('user.layout')

@section('title', 'Danh sách khóa học')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/courses.css') }}">
@endpush

@section('content')
<h2 class="section-title">Danh sách khóa học</h2>

<div class="filter-bar">
    <form method="GET" class="filter-form">
        <input type="text" name="search" placeholder="Tìm khóa học..."
               value="{{ request('search') }}"
               class="search-input">

        <select name="category_id" class="filter-select">
            <option value="">-- Tất cả danh mục --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="search-button">Tìm</button>
    </form>
</div>

<div class="course-grid">
    @forelse($courses as $course)
        <div class="course-card">
            <img src="{{ $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : 'https://via.placeholder.com/300x200' }}" 
                 alt="{{ $course->title }}" 
                 class="course-thumbnail">

            <h3 class="course-title">{{ $course->title }}</h3>

            <p class="course-meta">
                Danh mục: {{ $course->category->name ?? 'Chưa có danh mục' }}
            </p>

            <p class="course-description">{{ Str::limit($course->description, 80) }}</p>

            <a href="{{ route('user.course.detail', $course->id) }}" 
               class="course-link">
                Xem chi tiết
            </a>
        </div>
    @empty
        <p class="no-courses-message">Không tìm thấy khóa học nào.</p>
    @endforelse
</div>
@endsection