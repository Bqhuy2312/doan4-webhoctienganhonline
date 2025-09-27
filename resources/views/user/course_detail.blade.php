@extends('user.layout')

@section('title', $course->title)

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-4">{{ $course->title }}</h1>
    <p class="text-gray-600 mb-6">{{ $course->description }}</p>

    <h2 class="text-xl font-semibold mb-3">Nội dung khóa học</h2>
    <ul class="list-disc list-inside">
        @foreach($course->lessons as $lesson)
            <li class="mb-2">
                <a href="{{ route('user.lesson', [$course->id, $lesson->id]) }}" 
                   class="text-blue-600 hover:underline">
                   {{ $lesson->title }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-6">
        <a href="{{ route('user.enroll', $course->id) }}" 
           class="bg-blue-600 text-white px-6 py-2 rounded-lg">Đăng ký khóa học</a>
    </div>
</div>
@endsection
