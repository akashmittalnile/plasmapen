<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLessonStep extends Model
{
    use HasFactory;
    protected $table = 'course_lesson_steps';
    protected $key = 'id';

    public function quiz()
    {
        return $this->hasMany(CourseLessonQuiz::class, 'step_id', 'id');
    }
}
