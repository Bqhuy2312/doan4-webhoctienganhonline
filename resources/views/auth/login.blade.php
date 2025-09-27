<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow w-96">
        <h1 class="text-xl font-bold mb-4">Đăng nhập</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Mật khẩu</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Đăng nhập</button>
        </form>

        <div class="text-sm text-center mt-4 space-y-2">
            <p>
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Đăng ký</a>
            </p>
            <p>
                <a href="{{ route('password.request') }}" class="text-gray-600 hover:underline">Quên mật khẩu?</a>
            </p>
        </div>
    </div>
</body>
</html>
