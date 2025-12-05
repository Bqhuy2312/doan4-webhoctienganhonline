@extends('admin.layout')

@section('title', 'Chi tiết học viên')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_student_show.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Thông tin chi tiết học viên</h1>
    </div>

    <div class="student-profile-container">
        <aside class="profile-sidebar">
            <div class="profile-card">
                @if(Str::startsWith($student->avatar, 'http'))
                    <img src="{{ $student->avatar }}" alt="avatar" class="avatar">
                @else
                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="avatar" class="avatar">
                @endif
                <h2 class="student-name">{{ $student->last_name }}</h2>
                <p class="student-email">{{ $student->email }}</p>

                <ul class="profile-info-list">
                    <li>
                        <strong>Ngày tham gia:</strong>
                        <span>{{ $student->created_at->format('d/m/Y') }}</span>
                    </li>
                    <li>
                        <strong>Số khóa học đã đăng ký:</strong>
                        <span>{{ $student->courses_enrolled }}</span>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="enrolled-courses">
            @forelse ($enrolledCourses as $course)
                <div class="course-card">
                    <img src="{{ $course->thumbnail_url ? asset('storage/' . $course->thumbnail_url) : 'https://via.placeholder.com/150' }}" alt="{{ $course->title }}" class="course-thumbnail">
                    <div class="course-details">
                        <h3>{{ $course->title }}</h3>
                        <div class="progress-bar" title="Hoàn thành {{ $course->progress }}%">
                            <div class="progress-bar-fill" style="width: {{ $course->progress }}%;">
                                @if ($course->progress > 10)
                                    {{ $course->progress }}%
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="form-card" style="text-align: center;">
                    <p>Học viên này chưa đăng ký khóa học nào.</p>
                </div>
            @endforelse
        </main>
    </div>
@endsection