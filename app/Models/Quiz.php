<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Quiz
 * 
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Lesson[] $lessons
 * @property Collection|Question[] $questions
 *
 * @package App\Models
 */
class Quiz extends Model
{
	protected $table = 'quizzes';

	protected $fillable = [
		'title',
		'description'
	];

	public function lessons()
	{
		return $this->hasMany(Lesson::class);
	}

	public function questions()
	{
		return $this->hasMany(Question::class);
	}

	public function quizAttempts()
	{
		return $this->hasMany(QuizAttempt::class);
	}
}
