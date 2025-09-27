@extends('user.layout')

@section('title', 'Trang chủ')

@section('content')
<!-- Banner -->
<div class="bg-blue-600 text-white text-center py-20 rounded-xl shadow-lg">
    <h1 class="text-4xl font-bold">Học tiếng Anh mọi lúc, mọi nơi</h1>
    <p class="mt-4 text-lg">Khóa học online với video, tài liệu và quiz</p>
    <a href="{{ route('user.courses') }}" class="mt-6 inline-block bg-white text-blue-600 font-semibold px-6 py-2 rounded-lg">Xem khóa học</a>
</div>

<!-- Khóa học nổi bật -->
<div class="mt-12">
    <h2 class="text-2xl font-bold mb-6">Khóa học nổi bật</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow p-4">
                <img src="{{ $course->thumbnail }}" alt="thumbnail" class="rounded-md mb-3">
                <h3 class="font-semibold text-lg">{{ $course->title }}</h3>
                <p class="text-gray-600 text-sm">{{ Str::limit($course->description, 80) }}</p>
                <a href="{{ route('user.course.detail', $course->id) }}" class="text-blue-500 font-semibold mt-2 inline-block">Xem chi tiết</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
