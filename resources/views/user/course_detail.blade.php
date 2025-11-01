@extends('user.layout')

@section('title', $course->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/course_detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="course-detail-container">

    {{-- CỘT BÊN TRÁI --}}
    <main class="course-content">
        <div class="course-header">
            <h1>{{ $course->title }}</h1>
            <p>{{ $course->description }}</p>
        </div>

        <h2 class="curriculum-title">Nội dung khóa học</h2>
        
        <div class="curriculum-list">
            @forelse($course->sections as $section)
                <div class="section-item">
                    
                    <div class="section-header">
                        <h3>{{ $section->title }}</h3>
                    </div>
                    
                    <ul class="lesson-list">
                        @forelse ($section->lessons as $lesson)
                            <li class="lesson-item">
                                @if ($lesson->type == 'video')
                                    <i class="fa-solid fa-circle-play lesson-icon icon-video"></i>
                                @elseif ($lesson->type == 'pdf')
                                    <i class="fa-solid fa-file-lines lesson-icon icon-pdf"></i>
                                @elseif ($lesson->type == 'quiz')
                                    <i class="fa-solid fa-square-check lesson-icon icon-quiz"></i>
                                @endif
                                <span class="lesson-title">{{ $lesson->title }}</span>
                            </li>
                        @empty
                            <li class="lesson-item" style="justify-content: center;">Chương này chưa có bài học.</li>
                        @endempty
                    </ul>
                </div>
            @empty
                <p>Khóa học này chưa có nội dung.</p>
            @endforelse
        </div>
    </main>

    {{-- CỘT BÊN PHẢI --}}
    <aside class="course-sidebar">
        <div class="info-card">
            <img src="{{ $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : 'https://via.placeholder.com/300x200' }}" 
                 alt="{{ $course->title }}" 
                 class="info-card-image">
            
            <div class="info-card-body">
                {{-- Hiển thị giá --}}
                <div class="course-price">
                    @if($course->price && $course->price > 0)
                        {{ number_format($course->price, 0, ',', '.') }} VNĐ
                    @else
                        <span class="free">Miễn phí</span>
                    @endif
                </div>

                {{-- Kiểm tra xem user đã đăng ký khóa này chưa --}}
                @if ($isEnrolled)
                    <a href="{{ route('user.resume', $course->id) }}" class="enroll-button continue-learning-btn">
                        Tiếp tục học
                    </a>
                @elseif ($isFull)
                    <button type="button" class="enroll-button" disabled style="background-color: #6c757d; cursor: not-allowed;">
                        Đã hết chỗ
                    </button>
                @else
                    <form action="{{ route('user.enroll', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="enroll-button">
                            Đăng ký khóa học
                        </button>
                    </form>
                @endif
                <ul>
                    <li>Danh mục: {{ $course->category->name }}</li>
                    <li>Số lượng bài học: {{ $course->lessons->count() }}</li>
                    <li>Số suất học viên: {{ $course->student_limit }}</li>
                </ul>
            </div>
        </div>
    </aside>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        var sectionHeaders = document.querySelectorAll('.section-header');

        sectionHeaders.forEach(function(header) {
            header.addEventListener('click', function() {
                
                this.classList.toggle('active');

                var lessonList = this.nextElementSibling;

                if (lessonList && lessonList.classList.contains('lesson-list')) {
                    lessonList.classList.toggle('show');
                }
            });
        });

    });
</script>
@endsection