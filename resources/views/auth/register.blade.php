<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow w-96">
        <h1 class="text-xl font-bold mb-4">Đăng ký</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm">Họ (Last name)</label>
                <input type="text" name="last_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Tên (First name)</label>
                <input type="text" name="first_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Mật khẩu</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Đăng ký</button>
        </form>
        <div class="text-sm text-center mt-4 space-y-2">
            <p>
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Đăng nhập</a>
            </p>
        </div>
    </div>
</body>
</html>
