@extends('admin.layout')

@section('title', 'Quản lý học viên')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_students.css') }}">
@endpush

@section('content')

    {{-- @php
        use Carbon\Carbon;
        $allStudents = [
            (object) ['id' => 1, 'name' => 'Nguyễn Thu Trang', 'email' => 'trang.nt@example.com', 'created_at' => Carbon::parse('2025-09-24')],
            (object) ['id' => 2, 'name' => 'Trần Minh Hoàng', 'email' => 'hoang.tm@example.com', 'created_at' => Carbon::parse('2025-09-22')],
            (object) ['id' => 3, 'name' => 'Lê Thị Kim Anh', 'email' => 'anh.ltk@example.com', 'created_at' => Carbon::parse('2025-09-20')],
            (object) ['id' => 4, 'name' => 'Phạm Văn Đức', 'email' => 'duc.pv@example.com', 'created_at' => Carbon::parse('2025-09-18')],
            (object) ['id' => 5, 'name' => 'Vũ Hải Yến', 'email' => 'yen.vh@example.com', 'created_at' => Carbon::parse('2025-09-15')],
        ];
    @endphp --}}

    <div class="page-header">
        <h1>Tất cả học viên</h1>
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
                            <a href="{{ route('admin.students.show', $student->id) }}" class="action-btn" style="text-decoration: none;" title="Xem chi tiết tiến độ của học viên">Xem chi tiết</a>
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