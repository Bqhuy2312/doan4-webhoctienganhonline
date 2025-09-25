<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin/admin_layout.css') }}">
    
    @stack('styles')
</head>
<body>

    <div class="page-container">
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <span>Admin Panel</span>
            </a>
    
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Thống kê</span>
                        </a>
                    </li>
    
                    <li class="nav-category">QUẢN LÝ</li>
    
                    <li class="nav-item">
                        <a href="{{ route('admin.courses.index') }}" class="sidebar-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-book"></i>
                            <span>Quản lý khóa học</span>
                        </a>
                    </li>
    
                    <li class="nav-item">
                        <a href="{{ route('admin.students.index') }}" class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i>
                            <span>Quản lý học viên</span>
                        </a>
                    </li>
    
                    <li class="nav-item">
                        <a href="{{ route('admin.quiz.index') }}" class="sidebar-link {{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-question-circle"></i>
                            <span>Quản lý Quiz</span>
                        </a>
                    </li>
    
                    <li class="nav-category">TƯƠNG TÁC</li>
    
                    <li class="nav-item">
                        <a href="#" class="sidebar-link {{ request()->routeIs('admin.chat') ? 'active' : '' }}">
                            <i class="fa-solid fa-comments"></i>
                            <span>Chat/Hỗ trợ</span>
                        </a>
                    </li>
    
                    <li class="nav-category">TÀI KHOẢN</li>
    
                     <li class="nav-item">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-link">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Đăng xuất</span>
                        </a>
                        <form id="logout-form" action="#" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
    
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>