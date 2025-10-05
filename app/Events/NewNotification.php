<?php
namespace App\Events;
use App\Models\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;
    public Notification $notification;
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }
    public function broadcastOn(): array
    {
        return [new PrivateChannel('App.Models.User.' . $this->notification->user_id)];
    }
}