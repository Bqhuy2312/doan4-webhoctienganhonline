@extends('user.layout')

@section('title', 'Trang chủ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/homepage.css') }}">
@endpush

@section('content')
<div class="hero-banner">
    <h1>Học tiếng Anh mọi lúc, mọi nơi</h1>
    <p>Khóa học online với video, tài liệu và quiz</p>
    <a href="{{ route('user.courses') }}" class="cta-button">Xem khóa học</a>
</div>

<div class="featured-courses">
    <h2 class="section-title">Khóa học nổi bật</h2>
    <div class="course-grid">
        @foreach($courses as $course)
            <div class="course-card">
                <img src="{{ $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : 'https://via.placeholder.com/300x200' }}" alt="thumbnail">
                <h3>{{ $course->title }}</h3>
                <p>{{ Str::limit($course->description, 80) }}</p>
                <a href="{{ route('user.course.detail', $course->id) }}" class="detail-link">Xem chi tiết</a>
            </div>
        @endforeach
    </div>
</div>
@endsection