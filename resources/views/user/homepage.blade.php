@extends('user.layout')

@section('title', 'Trang chủ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/homepage.css') }}">
@endpush

@section('content')
<div class="hero-banner-slider">
    <div class="slider-track">

        <div class="slide" style="background-image: url('{{ asset('img/banner_1.jpg') }}')">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <h1>Học tiếng Anh mọi lúc, mọi nơi</h1>
                <p>Khóa học online với video, tài liệu và quiz</p>
                <a href="{{ route('user.courses') }}" class="cta-button">Xem khóa học</a>
            </div>
        </div>

        <div class="slide" style="background-image: url('{{ asset('img/banner_2.jpg') }}')">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <h1>Nâng trình độ mỗi ngày</h1>
                <p>Bài học chất lượng, lộ trình rõ ràng</p>
                <a href="{{ route('user.courses') }}" class="cta-button">Bắt đầu ngay</a>
            </div>
        </div>

    </div>

    <div class="slider-dots">
        <span class="dot active" data-slide="0"></span>
        <span class="dot" data-slide="1"></span>
    </div>
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

<script>
document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector(".slider-track");
    const dots = document.querySelectorAll(".dot");
    let current = 0;

    function goToSlide(index) {
        current = index;
        track.style.transform = `translateX(-${index * 100}%)`;

        dots.forEach(dot => dot.classList.remove("active"));
        dots[index].classList.add("active");
    }

    dots.forEach(dot => {
        dot.addEventListener("click", () => {
            goToSlide(parseInt(dot.dataset.slide));
        });
    });

    setInterval(() => {
        current = (current + 1) % 2;
        goToSlide(current);
    }, 3000);
});
</script>

@endsection