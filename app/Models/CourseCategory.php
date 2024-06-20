<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;
    protected $table = 'course_category';
    protected $key = 'id';

    public function allCategory($request){
        $data = $this->newQuery();
        if ($request->filled('search'))
            $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
        $data = $data->orderByDesc('id')->get()->toArray();
        $lengths = array_map( function($item) {
            $item['created_at'] = date('m-d-Y h:iA', strtotime($item['created_at']));
            $item['updated_at'] = date('m-d-Y h:iA', strtotime($item['updated_at']));
            return $item;
        } , $data);
        return $lengths;
    }
}
