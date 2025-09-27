@extends('admin.layout')

@section('title', 'Chi tiết học viên')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_student_show.css') }}">
@endpush

@section('content')

    @php
        use Carbon\Carbon;
        $student = (object) [
            'id' => 1,
            'name' => 'Nguyễn Thu Trang',
            'email' => 'trang.nt@example.com',
            'avatar_url' => 'https://i.pravatar.cc/150?img=1',
            'created_at' => Carbon::parse('2025-09-24'),
            'courses_enrolled' => 3,
        ];
        $enrolledCourses = [
            (object) [
                'title' => 'IELTS Foundation - Xây dựng nền tảng vững chắc',
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
                'progress' => 100
            ],
            (object) [
                'title' => 'Giao tiếp cơ bản cho người mất gốc',
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
                'progress' => 40
            ],
            (object) [
                'title' => 'Ngữ pháp tiếng Anh nâng cao toàn tập',
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
                'progress' => 0
            ],
        ];
    @endphp

    <div class="page-header">
        <h1>Thông tin chi tiết học viên</h1>
    </div>

    <div class="student-profile-container">
        <aside class="profile-sidebar">
            <div class="profile-card">
                <img src="{{ $student->avatar_url }}" alt="Avatar" class="avatar">
                <h2 class="student-name">{{ $student->name }}</h2>
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
                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="course-thumbnail">
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