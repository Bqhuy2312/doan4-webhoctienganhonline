@extends('admin.layout')

@section('title', 'Quản lý khóa học')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_courses.css') }}">
@endpush

@section('content')

    @php
        use Carbon\Carbon;
        $courses = [
            (object) [
                'id' => 1,
                'title' => 'IELTS Foundation - Xây dựng nền tảng vững chắc',
                'students_count' => 125,
                'is_active' => true,
                'created_at' => Carbon::parse('2025-09-25'),
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
            ],
            (object) [
                'id' => 2,
                'title' => 'TOEIC 500+ Cấp tốc trong 3 tháng',
                'students_count' => 210,
                'is_active' => true,
                'created_at' => Carbon::parse('2025-08-15'),
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
            ],
            (object) [
                'id' => 3,
                'title' => 'Giao tiếp cơ bản cho người mất gốc',
                'students_count' => 95,
                'is_active' => true,
                'created_at' => Carbon::parse('2025-07-30'),
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
            ],
            (object) [
                'id' => 4,
                'title' => 'Tiếng Anh chuyên ngành Công nghệ thông tin',
                'students_count' => 78,
                'is_active' => false,
                'created_at' => Carbon::parse('2025-06-01'),
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
            ],
            (object) [
                'id' => 5,
                'title' => 'Ngữ pháp tiếng Anh nâng cao toàn tập',
                'students_count' => 60,
                'is_active' => true,
                'created_at' => Carbon::parse('2024-12-20'),
                'thumbnail_url' => 'https://i.postimg.cc/pXW3C9Y4/course-thumbnail.jpg',
            ],
        ];
    @endphp

    <div class="page-header">
        <h1>Danh sách khóa học</h1>
        <a href="{{ route('admin.courses.create') }}" class="add-new-btn" style="text-decoration: none;"><i
                class="fa-solid fa-plus"></i> Thêm khóa học</a>
    </div>

    <div class="table-container">
        <table class="courses-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Khóa học</th>
                    <th>Ngày tạo</th>
                    <th>Số học viên</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr>
                        <td>
                            <div class="course-info">
                                <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/130x73' }}"
                                    alt="{{ $course->title }}" class="course-thumbnail-img">
                                <span class="course-title">{{ $course->title }}</span>
                            </div>
                        </td>
                        <td>{{ $course->created_at->format('d/m/Y') }}</td>
                        <td>{{ $course->students_count }}</td>
                        <td>
                            @if ($course->is_active)
                                <span class="status-badge status-active">Hoạt động</span>
                            @else
                                <span class="status-badge status-inactive">Tạm ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.courses.show', $course->id) }}" class="action-btn"
                                    title="Quản lý nội dung khóa học">
                                    <i class="fa-solid fa-list-check"></i>
                                    <span>Nội dung</span>
                                </a>

                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="action-btn"
                                    title="Sửa thông tin khóa học">
                                    <i class="fa-solid fa-pencil"></i>
                                    <span>Sửa</span>
                                </a>

                                <form action="#" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này không?');"
                                    style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" title="Xóa khóa học">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>Xóa</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">Chưa có khóa học nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 1.5rem;">
    </div>
@endsection