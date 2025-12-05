@extends('admin.layout')

@section('title', 'Quản lý học viên')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_students.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Tất cả học viên</h1>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.students.index') }}" class="filter-form">

            <input type="text" name="keyword" placeholder="Tìm theo tên hoặc email..." value="{{ request('keyword') }}">

            <input type="date" name="from_date" value="{{ request('from_date') }}">
            <input type="date" name="to_date" value="{{ request('to_date') }}">

            <button type="submit">Lọc</button>
            <a href="{{ route('admin.students.index') }}" class="clear-btn">Xóa lọc</a>
        </form>
    </div>

    <div class="table-container">
        <table class="students-table">
            <thead>
                <tr>
                    <th>Tên học viên</th>
                    <th>Email</th>
                    <th>Ngày tham gia</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allStudents as $student)
                    <tr>
                        <td class="student-name">{{ $student->last_name }} {{ $student->first_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.students.show', $student->id) }}" class="action-btn"
                                style="text-decoration: none;" title="Xem chi tiết tiến độ của học viên">Xem chi tiết</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Chưa có học viên nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection