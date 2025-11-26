<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Option
 * 
 * @property int $id
 * @property int $question_id
 * @property string $option_text
 * @property bool $is_correct
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Question $question
 *
 * @package App\Models
 */
class Option extends Model
{
	protected $table = 'options';

	protected $casts = [
		'question_id' => 'int',
		'is_correct' => 'bool'
	];

	protected $fillable = [
		'question_id',
		'option_text',
		'is_correct',
		'order',
	];

	public function question()
	{
		return $this->belongsTo(Question::class);
	}
}
