<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/admin_layout.css') }}">
    @stack('styles')
</head>

<body data-auth-user-id="{{ Auth::id() }}">

    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <span>Admin Panel</span>
        </a>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <p>Thống kê</p>
                    </a>
                </li>

                <li class="nav-header">QUẢN LÝ</li>

                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-list"></i>
                        <p>Quản lý danh mục</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.courses.index') }}"
                        class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-book"></i>
                        <p>Quản lý khóa học</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.students.index') }}"
                        class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <p>Quản lý học viên</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.quizzes.index') }}"
                        class="nav-link {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-question-circle"></i>
                        <p>Quản lý Quiz</p>
                    </a>
                </li>

                <li class="nav-header">TƯƠNG TÁC</li>

                <li class="nav-item">
                    <a href="{{ route('admin.chat.index') }}"
                        class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-comments"></i>
                        <p>
                            Chat/Hỗ trợ
                        </p>
                    </a>
                </li>

                <li class="nav-header">TÀI KHOẢN</li>

                <li class="nav-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <p>Đăng xuất</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
    </aside>
    <main class="main-content">

        <header class="main-header">
            <nav class="main-navbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#" id="notification-bell">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge" id="notification-count"
                                style="display: none;"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification-list">
                            <span class="dropdown-item dropdown-header">Thông báo</span>
                            <div class="dropdown-divider"></div>
                            <div id="notification-items">
                                <a href="#" class="dropdown-item text-center text-muted">Không có thông báo mới</a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">Xem tất cả thông báo</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="alert-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999; width: 350px;">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close-alert"
                        onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close-alert"
                        onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            @endif

        </div>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    @vite(['resources/js/app.js'])

    @auth 
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const adminId = document.body.dataset.authUserId;

                const notiCountEl = document.getElementById('notification-count');
                const notiListEl = document.getElementById('notification-items');

                // Lắng nghe channel dành RIÊNG cho admin đang đăng nhập
                Echo.channel('public-notification-channel')
                    .listen('.NewNotification', (e) => {

                        const data = e.notification;

                        console.log('Realtime Notification:', data);

                        // Hiển thị badge
                        notiCountEl.style.display = 'inline-block';
                        let current = parseInt(notiCountEl.innerText || 0);
                        notiCountEl.innerText = current + 1;

                        // Xóa dòng "Không có thông báo"
                        const emptyItem = notiListEl.querySelector('.text-muted');
                        if (emptyItem) emptyItem.remove();

                        // Tạo thông báo mới
                        const newItem = document.createElement('a');
                        newItem.href = data.url;
                        newItem.classList.add('dropdown-item');
                        newItem.innerHTML = `
                    <i class="fa-solid fa-bell mr-2"></i> ${data.message}
                    <span class="float-right text-muted text-sm">vừa xong</span>
                `;

                        notiListEl.prepend(newItem);

                        // Popup thông báo cho đẹp
                        Toastify({
                            text: "🔔 " + data.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                        }).showToast();
                    });
            });
        </script>
    @endauth
</body>

</html>