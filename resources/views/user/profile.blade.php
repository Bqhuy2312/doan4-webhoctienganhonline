@extends('user.layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Hồ sơ cá nhân</h2>
    <div class="flex items-center space-x-6">
        <img src="{{ $user->avatar ?? 'https://via.placeholder.com/150' }}" class="w-24 h-24 rounded-full">
        <div>
            <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    <h3 class="text-xl font-semibold mt-6 mb-3">Tiến độ học tập</h3>
    <ul>
        @foreach($user->courses as $course)
            <li class="mb-2">
                {{ $course->title }} 
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                    <div class="bg-blue-600 h-2.5 rounded-full"style="width: {{ $course->pivot->progress . '%' }}"></div>
                </div>
                <span class="text-sm text-gray-600">{{ $course->pivot->progress }}%</span>
            </li>
        @endforeach
    </ul>
</div>
@endsection
