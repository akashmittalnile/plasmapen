<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLessonQuizOption extends Model
{
    use HasFactory;
    protected $table = 'course_lesson_quiz_options';
    protected $key = 'id';
}
