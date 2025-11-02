<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'read_at'
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i d/m/Y');
    }
}
