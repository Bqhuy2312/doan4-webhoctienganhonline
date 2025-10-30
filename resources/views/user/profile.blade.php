@extends('user.layout')

@section('title', 'Hồ sơ cá nhân')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile.css') }}">
@endpush

@section('content')
<div class="profile-card">
    <h2 class="section-title">Hồ sơ cá nhân</h2>
    
    {{-- Thông tin chung --}}
    <div class="user-info-header">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/150' }}" 
             alt="Avatar" class="avatar-image">
        <div class="user-details">
            <p><strong>Tên:</strong> {{ $user->last_name }} {{ $user->first_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    {{-- Tiến độ học tập --}}
    <h3 class="subsection-title">Tiến độ học tập</h3>
    <ul class="progress-list">
        @forelse($user->courses as $course)
            <li class="progress-item">
                <div class="progress-header">
                    <span class="progress-course-title">{{ $course->title }}</span>
                    <span class="progress-percent">{{ $course->pivot->progress }}%</span>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar-fill" 
                         style="width: {{ $course->pivot->progress . '%' }}">
                    </div>
                </div>

                <div class="progress-actions">
                    <a href="{{ route('user.resume', $course->id) }}" 
                       class="action-button btn-green">
                        Tiếp tục học
                    </a>

                    <a href="{{ route('user.course.detail', $course->id) }}" 
                       class="action-button btn-blue">
                        Chi tiết
                    </a>

                    <form action="{{-- route('profile.resetProgress', $course->id) --}}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="action-button btn-red"
                                onclick="return confirm('Bạn có chắc muốn làm lại khóa học này? Toàn bộ tiến độ sẽ bị xóa.')">
                            Làm lại
                        </button>
                    </form>
                </div>
            </li>
        @empty
            <li>Bạn chưa đăng ký khóa học nào.</li>
        @endforelse
    </ul>

    {{-- Cập nhật hồ sơ --}}
    <h3 class="subsection-title">Cập nhật hồ sơ</h3>
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="first_name">Họ</label>
            <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label" for="last_name">Tên</label>
            <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label" for="avatar">Avatar</label>
            <input type="file" id="avatar" name="avatar" class="form-input">
        </div>
        
        <button type="submit" class="submit-button btn-update">
            Cập nhật
        </button>
    </form>

    {{-- Đổi mật khẩu --}}
    <h3 class="subsection-title">Đổi mật khẩu</h3>
    <form action="{{-- route('user.profile.password') --}}" method="POST" class="profile-form">
        @csrf
        <div class="form-group">
            <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
            <input type="password" id="current_password" name="current_password" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label" for="new_password">Mật khẩu mới</label>
            <input type="password" id="new_password" name="new_password" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input">
        </div>
        
        <button type="submit" class="submit-button btn-password">
            Đổi mật khẩu
        </button>
    </form>
</div>
@endsection