<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course';
    protected $key = 'id';

    public function lessons(){
        return $this->hasMany(CourseLesson::class, 'course_id', 'id')->orderByDesc('id');
    }

    public function category(){
        return $this->belongsTo(CourseCategory::class, 'category_id', 'id');
    }

    public function reviewList(){
        return $this->hasMany(UserReview::class, 'object_id', 'id')->where('object_type', 'course')->orderByDesc('id');
    }

    public function allCourses($request, $limit = null){
        $data = $this->newQuery();
        if ($request->filled('search'))
            $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
        if($request->filled('category_id'))
            $data->where("category_id", $request->category_id);
        if ($limit)
            $data->limit($limit);
        $data = $data->where('status', 1)->with('lessons')->withAvg('reviewList as rating', 'rating')->orderByDesc('rating', 'id')->get()->toArray();
        $lengths = array_map( function($item) {
            $item['lesson_count'] = count($item['lessons']);
            $item['rating'] = isset($item['rating']) ? number_format((float)$item['rating'], 1, '.', '') : 0;
            $item['video'] = isset($item['video']) ? assets('uploads/course/video/'.$item['video']) : null;
            $item['image'] = isset($item['image']) ? assets('uploads/course/image/'.$item['image']) : null;
            $item['created_at'] = date('m-d-Y h:iA', strtotime($item['created_at']));
            $item['updated_at'] = date('m-d-Y h:iA', strtotime($item['updated_at']));
            return $item;
        } , $data);
        return $lengths;
    }

    public function details($id){
        $data = $this->newQuery();
        $data = $data->where('id', $id)->with('lessons', 'category', 'reviewList')->withAvg('reviewList as rating', 'rating')->first();
        return $data;
    }
}
