@extends('user.layout')

@section('title', 'Danh sách khóa học')

@section('content')
<h2 class="text-2xl font-bold mb-6">Danh sách khóa học</h2>

<div class="flex justify-between mb-4">
    <form method="GET">
        <input type="text" name="search" placeholder="Tìm khóa học..."
               class="border px-3 py-2 rounded w-64">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Tìm</button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($courses as $course)
        <div class="bg-white rounded-lg shadow p-4">
            <img src="{{ $course->thumbnail }}" class="rounded-md mb-3">
            <h3 class="font-semibold text-lg">{{ $course->title }}</h3>
            <p class="text-gray-600 text-sm">{{ Str::limit($course->description, 80) }}</p>
            <a href="{{ route('user.course.detail', $course->id) }}" class="text-blue-500 font-semibold mt-2 inline-block">Xem chi tiết</a>
        </div>
    @endforeach
</div>
@endsection
