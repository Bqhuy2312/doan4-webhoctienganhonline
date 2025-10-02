<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * 
 * @property int $id
 * @property int $course_id
 * @property string $title
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course $course
 * @property Collection|Lesson[] $lessons
 *
 * @package App\Models
 */
class Section extends Model
{
	protected $table = 'sections';

	protected $casts = [
		'course_id' => 'int',
		'order' => 'int'
	];

	protected $fillable = [
		'course_id',
		'title',
		'order'
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function lessons()
	{
		return $this->hasMany(Lesson::class);
	}
}
