<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public function images(){
        return $this->hasMany(Image::class, 'item_id', 'id')->where('item_type', 'blog')->orderByDesc('id');
    }

    public function allBlogs($request, $limit = null){
        $data = $this->newQuery();
        if ($request->filled('search'))
            $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
        if ($limit)
            $data->limit($limit);
        $data = $data->where('status', 1)->with('images')->orderByDesc('id')->get()->toArray();
        $lengths = array_map( function($item) {
            $images = array();
            foreach($item['images'] as $val){
                $img['id'] = $val['id'];
                $img['image'] = isset($val['item_name']) ? assets('uploads/blog/'.$val['item_name']) : assets('assets/images/no-image.jpg');
                $images[] = $img;
            }
            $item['images'] = $images;
            $item['created_at'] = date('m-d-Y h:iA', strtotime($item['created_at']));
            $item['updated_at'] = date('m-d-Y h:iA', strtotime($item['updated_at']));
            return $item;
        } , $data);
        return $lengths;
    }

    public function details($id){
        $data = $this->newQuery();
        $data = $data->where('id', $id)->with('images')->first();
        return $data;
    }
}
