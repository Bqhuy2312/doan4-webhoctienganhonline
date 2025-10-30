<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Học Tiếng Anh Online')</title>
    <!-- Bootstrap trước -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS của bạn đặt SAU để ghi đè -->
    <link rel="stylesheet" href="{{ asset('css/user/app.css') }}">
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="site-navbar">
        <div class="nav-container">
            <!-- Logo -->
            <a href="{{ route('user.home') }}" class="logo">E-Online</a>

            <!-- Menu -->
            <ul class="nav-links">
                <li><a href="{{ route('user.home') }}">Trang chủ</a></li>
                <li><a href="{{ route('user.courses') }}">Khóa học</a></li>
                <li><a href="{{ route('user.my_courses') }}">Khóa học của tôi</a></li>
                <li><a href="{{ route('user.chat') }}">Hỗ trợ</a></li>
            </ul>

            <!-- Auth -->
            <div class="auth-section">
                @if(Auth::check())
                    <div class="dropdown">
                        <button onclick="toggleDropdown()" class="dropdown-btn">
                            {{ Auth::user()->first_name }}
                            <span class="arrow">▼</span>
                        </button>
                        <div id="dropdownMenu" class="dropdown-content">
                            <a href="{{ route('user.profile') }}">Hồ sơ</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn-register">Đăng ký</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Nội dung -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} E-Online. All rights reserved.</p>
    </footer>

    <script>
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }
    </script>
    @stack('scripts')
</body>
</html>
