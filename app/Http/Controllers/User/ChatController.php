<?php

namespace App\Http\Controllers\User;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Events\NewNotification;

class ChatController extends Controller
{
    // Giả định Admin luôn là user có ID = 1
    const ADMIN_USER_ID = 1;

    public function index()
    {
        return view('user.chat');
    }

    /**
     * Lấy lịch sử chat giữa User và Admin
     */
    public function fetchMessages()
    {
        $userId = Auth::id();

        $messages = Message::where(function ($query) use ($userId) {
            // Tin nhắn User gửi cho Admin
            $query->where('sender_id', $userId)
                ->where('receiver_id', self::ADMIN_USER_ID);
        })
            ->orWhere(function ($query) use ($userId) {
                // Tin nhắn Admin gửi cho User
                $query->where('sender_id', self::ADMIN_USER_ID)
                    ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * User gửi tin nhắn mới (luôn gửi cho Admin)
     */
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'sender_id' => Auth::id(), // Người gửi là user
            'receiver_id' => self::ADMIN_USER_ID, // Người nhận là Admin
            'message' => $request->message,
        ]);

        // <-- 2. PHÁT SÓNG SỰ KIỆN CHO ADMIN -->
        // Giống hệt như adChatController
        $message->load('sender');
        $notification = Notification::create([
            'user_id' => self::ADMIN_USER_ID,
            'message' => Auth::user()->name . ' vừa gửi 1 tin nhắn mới.',
            'url' => route('admin.chat.index'),
        ]);

        // Phát sự kiện thông báo (chuông thông báo của admin)
        broadcast(new NewNotification($notification))->toOthers();
        broadcast(new MessageSent($message));

        return response()->json($message);
    }
}