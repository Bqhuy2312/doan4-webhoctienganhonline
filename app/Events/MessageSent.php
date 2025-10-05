<?php
namespace App\Events;
use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;
    public Message $message;
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }
    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->message->receiver_id)];
    }
}