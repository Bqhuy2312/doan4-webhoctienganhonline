<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user()->load('courses'); // nếu user có khóa học
        return view('user.profile', compact('user'));
    }

    /**
     * Cập nhật thông tin hồ sơ
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validate dữ liệu
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'avatar' => [
                'nullable', // Avatar không bắt buộc
                File::image() // Chỉ chấp nhận file ảnh
                    ->max(2048) // Tối đa 2MB
            ],
        ]);

        // 2. Xử lý upload Avatar (Nếu có file mới)
        if ($request->hasFile('avatar')) {

            // Xóa avatar cũ (nếu có) để tiết kiệm dung lượng
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu file mới vào 'storage/app/public/avatars'
            // và lấy đường dẫn lưu vào CSDL
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 3. Cập nhật các thông tin khác
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];

        // 4. Lưu lại
        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    /**
     * Đổi mật khẩu người dùng
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
