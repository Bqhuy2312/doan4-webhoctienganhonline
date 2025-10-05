<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * 
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $thumbnail_url
 * @property int $price
 * @property int|null $student_limit
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Course extends Model
{
	protected $table = 'courses';

	protected $casts = [
		'price' => 'int',
		'student_limit' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title',
		'description',
		'thumbnail_url',
		'price',
		'student_limit',
		'is_active',
		'category_id'
	];

	public function students()
	{
		return $this->belongsToMany(User::class, 'enrollments');
	}

	public function lessons()
	{
		return $this->hasManyThrough(Lesson::class, Section::class);
	}

	public function sections()
	{
		return $this->hasMany(Section::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function level()
{
    return $this->belongsTo(Level::class);
}
}
