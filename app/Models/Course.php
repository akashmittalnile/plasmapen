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

    public function wishlist(){
        return $this->hasMany(UserWishlist::class, 'object_id', 'id')->where('object_type', 1)->where('user_id', auth()->user()->id)->where('status', 1);
    }

    public function reviewList(){
        return $this->hasMany(UserReview::class, 'object_id', 'id')->where('object_type', 1)->orderByDesc('id');
    }

    public function allCourses($request, $limit = null){
        $data = $this->newQuery();
        if ($request->filled('filter'))
            $data->whereRaw("(`title` LIKE '%" . $request->filter . "%' or `description` LIKE '%" . $request->filter . "%' or `course_fee` LIKE '%" . $request->filter . "%')");
        if ($request->filled('date')) {
                $data->whereDate('created_at', $request->date);
        };
        if ($request->filled('search'))
            $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
        if($request->filled('category_id'))
            $data->where("category_id", $request->category_id);
        if ($limit)
            $data->limit($limit);
        $data = $data->where('status', 1)->with('lessons', 'wishlist')->withAvg('reviewList as rating', 'rating')->orderByDesc('rating', 'id')->get()->toArray();
        $lengths = array_map( function($item) {
            $item['lesson_count'] = count($item['lessons']);
            $item['wishlist'] = count($item['wishlist']) ? true : false;
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
        $data = $data->where('id', $id)->with('lessons', 'category', 'reviewList', 'wishlist')->withAvg('reviewList as rating', 'rating')->first();
        return $data;
    }
}
