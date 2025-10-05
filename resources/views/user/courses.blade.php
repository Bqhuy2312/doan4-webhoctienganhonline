@extends('user.layout')

@section('title', 'Danh sách khóa học')

@section('content')
<h2 class="text-2xl font-bold mb-6">Danh sách khóa học</h2>

<!-- Search + Filter -->
<div class="flex space-x-3 mb-4">
    <form method="GET" class="flex space-x-2">
        <input type="text" name="search" placeholder="Tìm khóa học..."
               value="{{ request('search') }}"
               class="border px-3 py-2 rounded w-64">

        <select name="level" class="border px-3 py-2 rounded">
            <option value="">-- Cấp bậc --</option>
            @foreach($levels as $level)
                <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Tìm</button>
    </form>
</div>

<!-- Danh sách khóa học -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($courses as $course)
        <div class="bg-white rounded-lg shadow p-4">
            <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/300x200' }}" 
                 alt="{{ $course->title }}" 
                 class="rounded-md mb-3 w-full h-40 object-cover">

            <h3 class="font-semibold text-lg">{{ $course->title }}</h3>

            <p class="text-sm text-gray-500 mb-1">
                Cấp bậc: {{ $course->level->name ?? 'Chưa có cấp bậc' }}
            </p>

            <p class="text-gray-600 text-sm">{{ Str::limit($course->description, 80) }}</p>

            <a href="{{ route('user.course.detail', $course->id) }}" 
               class="text-blue-500 font-semibold mt-2 inline-block">
                Xem chi tiết
            </a>
        </div>
    @empty
        <p class="text-gray-500">Không tìm thấy khóa học nào.</p>
    @endforelse
</div>
@endsection
