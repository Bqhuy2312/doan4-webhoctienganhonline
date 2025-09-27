<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Học Tiếng Anh Online')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-3 px-6">
            <!-- Logo -->
            <a href="{{ route('user.home') }}" class="text-2xl font-bold text-blue-600">E-Online</a>

            <!-- Menu -->
            <ul class="flex space-x-6">
                <li><a href="{{ route('user.home') }}" class="hover:text-blue-500">Trang chủ</a></li>
                <li><a href="{{ route('user.courses') }}" class="hover:text-blue-500">Khóa học</a></li>
                <li><a href="{{ route('user.quiz') }}" class="hover:text-blue-500">Quiz</a></li>
                <li><a href="{{ route('user.chat') }}" class="hover:text-blue-500">Hỗ trợ</a></li>
            </ul>

            <!-- Auth -->
            <div>
                @if(Auth::check())
                    <!-- Dropdown user -->
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none">
                            {{ Auth::user()->first_name }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Menu -->
                        <div id="dropdownMenu" 
                             class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg">
                            <a href="{{ route('user.profile') }}" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                               Hồ sơ
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Nếu chưa đăng nhập -->
                    <a href="{{ route('login') }}" class="mr-3">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Đăng ký</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Nội dung -->
    <main class="container mx-auto py-6 px-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-10">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} E-Learning. All rights reserved.</p>
        </div>
    </footer>

    <!-- Script dropdown -->
    <script>
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("hidden");
        }
        
    </script>
</body>
</html>
