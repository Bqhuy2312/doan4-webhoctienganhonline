@extends('user.layout')

@section('title', 'Khóa học của tôi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/my_course.css') }}">
@endpush

@section('content')
<h2 class="section-title">Khóa học của tôi</h2>

<div class="course-grid">
    @forelse($enrollments as $enrollment)
        {{-- Lấy thông tin khóa học từ bản ghi enrollment --}}
        @php $course = $enrollment->course; @endphp

        <div class="course-card">
            <img src="{{ $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : 'https://via.placeholder.com/300x200' }}" 
                 alt="{{ $course->title }}" 
                 class="course-thumbnail">
            
            <div class="course-card-body">
                <h3 class="course-title">{{ $course->title }}</h3>

                {{-- Thanh tiến độ --}}
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $enrollment->progress }}%;"></div>
                </div>
                <p class="progress-percent-text">{{ $enrollment->progress }}% Hoàn thành</p>

                <a href="{{ route('user.resume', $course->id) }}" class="course-link">
                    Học tiếp
                </a>
            </div>
        </div>
    @empty
        <p class="no-courses-message">Bạn chưa đăng ký khóa học nào. Hãy khám phá các <a href="{{ route('user.courses') }}">khóa học mới</a>!</p>
    @endforelse
</div>
@endsection