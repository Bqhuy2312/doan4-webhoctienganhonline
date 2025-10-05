<?php
namespace App\Listeners;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification;

class StoreNotification
{
    public function handle(object $event): void
    {
        if (isset($event->userId) && isset($event->message)) {
            Notification::create([
                'user_id' => $event->userId,
                'message' => $event->message,
                'url' => $event->url ?? null,
            ]);
        }
    }
}