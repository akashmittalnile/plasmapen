<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course';
    protected $key = 'id';

    public function lessonCount(){
        return $this->hasMany(CourseLesson::class, 'course_id', 'id')->count();
    }

    public function lessons(){
        return $this->hasMany(CourseLesson::class, 'course_id', 'id')->orderByDesc('id')->get();
    }
}
