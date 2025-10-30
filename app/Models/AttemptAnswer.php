<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'quiz_attempt_id', 
        'question_id', 
        'option_id',
        'is_correct'
    ];
    protected $casts = [
        'is_correct' => 'boolean',
    ];

}