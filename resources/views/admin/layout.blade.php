<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_layout.css') }}">
    @stack('styles')
</head>
<body>

    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <span>Admin Panel</span>
        </a>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <p>Thống kê</p>
                    </a>
                </li>

                <li class="nav-header">QUẢN LÝ</li>

                <li class="nav-item">
                    <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-book"></i>
                        <p>Quản lý khóa học</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <p>Quản lý học viên</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.quiz.index') }}" class="nav-link {{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-question-circle"></i>
                        <p>Quản lý Quiz</p>
                    </a>
                </li>

                <li class="nav-header">TƯƠNG TÁC</li>

                <li class="nav-item">
                    <a href="{{ route('admin.chat.index') }}" class="nav-link {{ request()->routeIs('admin.chat') ? 'active' : '' }}">
                        <i class="fa-solid fa-comments"></i>
                        <p>
                            Chat/Hỗ trợ
                            </p>
                    </a>
                </li>

                <li class="nav-header">TÀI KHOẢN</li>

                 <li class="nav-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <p>Đăng xuất</p>
                    </a>
                    <form id="logout-form" action="#" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
        </aside>
    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>