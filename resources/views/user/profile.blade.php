@extends('user.layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Hồ sơ cá nhân</h2>
    <div class="flex items-center space-x-6">
        <img src="{{ $user->avatar ?? 'https://via.placeholder.com/150' }}" 
             class="w-24 h-24 rounded-full border">
        <div>
            <p><strong>Tên:</strong> {{ $user->last_name }} {{ $user->first_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    {{-- Tiến độ học tập --}}
    <h3 class="text-xl font-semibold mt-6 mb-3">Tiến độ học tập</h3>
<ul>
    @foreach($user->courses as $course)
        <li class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <span class="font-medium">{{ $course->title }}</span>
                <span class="text-sm text-gray-600">{{ $course->pivot->progress }}%</span>
            </div>

            <!-- Thanh tiến độ -->
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                    style="width: {{ $course->pivot->progress . '%' }}">
                </div>

            </div>

            <!-- Chức năng thêm -->
            <div class="flex space-x-3 mt-2 text-sm">
                <!-- Nút tiếp tục học -->
                <a href="{{ route('user.course.detail', $course->id) }}" 
                   class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    Tiếp tục học
                </a>

                <!-- Nút xem chi tiết -->
                <a href="{{ route('user.course.detail', $course->id) }}" 
                   class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Chi tiết
                </a>

                <!-- Nút reset tiến độ -->
                <form action="{{ route('profile.resetProgress', $course->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                        Làm lại
                    </button>
                </form>
            </div>
        </li>
    @endforeach
</ul>


    {{-- Cập nhật hồ sơ --}}
    <h3 class="text-xl font-semibold mt-6 mb-3">Cập nhật hồ sơ</h3>
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
    <label class="block text-sm font-medium">Họ</label>
    <input type="text" name="first_name" value="{{ $user->first_name }}" 
           class="w-full border rounded p-2">
</div>
<div>
    <label class="block text-sm font-medium">Tên</label>
    <input type="text" name="last_name" value="{{ $user->last_name }}" 
           class="w-full border rounded p-2">
</div>

        <div>
            <label class="block text-sm font-medium">Avatar</label>
            <input type="file" name="avatar" class="w-full border rounded p-2">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Cập nhật
        </button>
    </form>

    {{-- Đổi mật khẩu --}}
    <h3 class="text-xl font-semibold mt-6 mb-3">Đổi mật khẩu</h3>
    <form action="{{ route('user.profile.password') }}" method="POST">
        @csrf
        <div>
            <label class="block text-sm font-medium">Mật khẩu hiện tại</label>
            <input type="password" name="current_password" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Mật khẩu mới</label>
            <input type="password" name="new_password" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Xác nhận mật khẩu mới</label>
            <input type="password" name="new_password_confirmation" class="w-full border rounded p-2">
        </div>
        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Đổi mật khẩu
        </button>
    </form>
</div>
@endsection
