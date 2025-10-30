<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Enrollment
 * 
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $progress
 * @property int|null $last_viewed_lesson_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course $course
 * @property User $user
 *
 * @package App\Models
 */
class Enrollment extends Model
{
	protected $table = 'enrollments';

	protected $casts = [
		'user_id' => 'int',
		'course_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'course_id',
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
