@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_dashboard.css') }}">
@endpush

@section('content')

    <div class="dashboard-header">
        <h1>Tổng quan</h1>
    </div>

    <div class="stat-cards-container">
        <div class="stat-card">
            <div class="card-icon icon-students">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="card-info">
                <h3>{{ number_format($totalStudents ?? 0) }}</h3>
                <p>Tổng số học viên</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon icon-new-students">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="card-info">
                <h3>{{ $newStudentsThisWeek ?? 0 }}</h3>
                <p>Học viên mới (tuần này)</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon icon-courses">
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="card-info">
                <h3>{{ $activeCourses ?? 0 }}</h3>
                <p>Khóa học đang hoạt động</p>
            </div>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-wrapper bar-chart">
            <h3>Số lượng học viên theo khóa học</h3>
            <canvas id="studentsPerCourseChart"></canvas>
        </div>

        <div class="chart-wrapper pie-chart">
            <h3>Tỷ lệ hoàn thành khóa học</h3>
            <canvas id="completionRateChart"></canvas>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const studentsPerCourseData = @json($studentsPerCourseData ?? ['labels' => [], 'values' => []]);
        const completionRateData = @json($completionRateData ?? ['labels' => [], 'values' => []]);

        document.addEventListener('DOMContentLoaded', function () {

            const ctxBar = document.getElementById('studentsPerCourseChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: studentsPerCourseData.labels,
                    datasets: [{
                        label: 'Số học viên',
                        data: studentsPerCourseData.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
            });

            const ctxPie = document.getElementById('completionRateChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: completionRateData.labels,
                    datasets: [{
                        label: 'Tỷ lệ',
                        data: completionRateData.values,
                        backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(255, 99, 132, 0.7)'],
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'top' } } }
            });
        });
    </script>
@endpush