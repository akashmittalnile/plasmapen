<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory;
    protected $table = 'course_lessons';
    protected $key = 'id';

    public function steps(){
        return $this->hasMany(CourseLessonStep::class, 'course_lesson_id', 'id');
    }
}
