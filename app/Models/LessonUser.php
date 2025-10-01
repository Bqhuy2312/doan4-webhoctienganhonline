<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LessonUser
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Lesson $lesson
 * @property User $user
 *
 * @package App\Models
 */
class LessonUser extends Model
{
	protected $table = 'lesson_user';

	protected $casts = [
		'user_id' => 'int',
		'lesson_id' => 'int',
		'completed_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'lesson_id',
		'completed_at'
	];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
