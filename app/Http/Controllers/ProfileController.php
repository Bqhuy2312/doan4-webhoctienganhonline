<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // ✅ thêm để IDE hiểu

class ProfileController extends Controller
{
    // Hiển thị hồ sơ
    public function show()
    {
        /** @var User $user */   // ✅ chú thích cho VSCode hiểu user là model User
        $user = auth()->user()->load('courses');
        return view('user.profile', compact('user'));
    }

    // Cập nhật hồ sơ
    public function update(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $user->name = $request->name;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }

        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
