<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lesson
 * 
 * @property int $id
 * @property int $course_id
 * @property string $title
 * @property string $type
 * @property string|null $video_url
 * @property string|null $pdf_path
 * @property int|null $quiz_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course $course
 * @property Quiz|null $quiz
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Lesson extends Model
{
	protected $table = 'lessons';

	protected $casts = [
		'course_id' => 'int',
		'quiz_id' => 'int'
	];

	protected $fillable = [
		'course_id',
		'title',
		'type',
		'video_url',
		'pdf_path',
		'quiz_id'
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function quiz()
	{
		return $this->belongsTo(Quiz::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class)
					->withPivot('id', 'completed_at')
					->withTimestamps();
	}
}
