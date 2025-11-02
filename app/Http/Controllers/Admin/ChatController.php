<?php
namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        $userIds = Message::where('sender_id', $admin->id)->orWhere('receiver_id', $admin->id)
            ->get(['sender_id', 'receiver_id'])
            ->flatMap(fn($msg) => [$msg->sender_id, $msg->receiver_id])
            ->unique()
            ->reject(fn($id) => $id == $admin->id);

        $conversations = User::whereIn('id', $userIds)->get();

        $activeChatPartner = null;
        $messages = [];

        if ($request->has('user_id') && $userToChat = User::find($request->user_id)) {
            $activeChatPartner = $userToChat;
            $messages = $this->fetchMessages($activeChatPartner);
        }

        return view('admin.chat.index', compact('conversations', 'activeChatPartner', 'messages'));
    }

    public function fetchMessages(User $user)
    {
        $adminId = Auth::id();

        return Message::where(function ($q) use ($user, $adminId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user, $adminId) {
            $q->where('sender_id', $user->id)->where('receiver_id', $adminId);
        })->with('sender')->get();
    }

    public function sendMessage(Request $request, User $user)
    {
        $message = Auth::user()->messages()->create([
            'receiver_id' => $user->id,
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($message));

        return response()->json(['status' => 'Message Sent!']);
    }
}