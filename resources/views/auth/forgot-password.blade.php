<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow w-96">
        <h1 class="text-xl font-bold mb-4">Quên mật khẩu</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-600 p-2 mb-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm">Nhập email để đặt lại mật khẩu</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
                Gửi liên kết đặt lại mật khẩu
            </button>
        </form>

        <p class="text-sm text-center mt-4">
            <a href="{{ route('login') }}" class="text-gray-600 hover:underline">Quay lại đăng nhập</a>
        </p>
    </div>
</body>
</html>
