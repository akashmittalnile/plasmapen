<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLessonQuiz extends Model
{
    use HasFactory;
    protected $table = 'course_lesson_quiz';
    protected $key = 'id';

    public function quizOption()
    {
        return $this->hasMany(CourseLessonQuizOption::class, 'quiz_id', 'id');
    }
}
