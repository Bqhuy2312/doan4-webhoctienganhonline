<?php
namespace App\Events;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;
    public Message $message;
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }
    public function broadcastOn(): array
    {
        return [new Channel('public-chat-channel')];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}