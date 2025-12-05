@extends('admin.layout')

@section('title', 'Quản lý khóa học')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_courses.css') }}">
@endpush

@section('content')

    <div class="page-header">
        <h1>Danh sách khóa học</h1>
        <a href="{{ route('admin.courses.create') }}" class="add-new-btn" style="text-decoration: none;"><i
                class="fa-solid fa-plus"></i> Thêm khóa học</a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="filter-form">

            <input type="text" name="keyword" placeholder="Tìm theo tên khóa học..." value="{{ request('keyword') }}">

            <input type="date" name="from_date" value="{{ request('from_date') }}">
            <input type="date" name="to_date" value="{{ request('to_date') }}">

            <select name="status">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm ẩn</option>
            </select>

            <button type="submit">Lọc</button>
            <a href="{{ route('admin.courses.index') }}" class="clear-btn">Xóa lọc</a>
        </form>
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
                                <img src="{{ asset('storage/' . $course->thumbnail_url) }}" alt="{{ $course->title }}"
                                    class="course-thumbnail-img">
                                <span class="course-title">{{ $course->title }}</span>
                            </div>
                        </td>
                        <td>{{ $course->created_at->format('d/m/Y') }}</td>
                        <td>{{ $course->students_count }} / {{ $course->student_limit }}</td>
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

                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST"
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
        {{ $courses->links() }}
    </div>
@endsection