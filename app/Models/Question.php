<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * 
 * @property int $id
 * @property int $quiz_id
 * @property string $question_text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Quiz $quiz
 * @property Collection|Option[] $options
 *
 * @package App\Models
 */
class Question extends Model
{
	protected $table = 'questions';

	protected $casts = [
		'quiz_id' => 'int'
	];

	protected $fillable = [
		'quiz_id',
		'question_text',
		'type',
	];

	public function quiz()
	{
		return $this->belongsTo(Quiz::class);
	}

	public function options()
	{
		return $this->hasMany(Option::class);
	}
}
