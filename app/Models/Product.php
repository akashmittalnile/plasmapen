<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function images(){
        return $this->hasMany(Image::class, 'item_id', 'id')->where('item_type', 'product')->orderByDesc('id');
    }

    public function lessons(){
        return $this->hasMany(ProductRecommendationLesson::class, 'product_id', 'id')->orderByDesc('id');
    }

    public function wishlist(){
        return $this->hasMany(UserWishlist::class, 'object_id', 'id')->where('object_type', 2)->where('user_id', auth()->user()->id)->where('status', 1);
    }

    public function reviewList(){
        return $this->hasMany(UserReview::class, 'object_id', 'id')->where('object_type', 2)->orderByDesc('id');
    }

    public function allProducts($request, $limit = null){
        $data = $this->newQuery();
        if ($request->filled('filter'))
            $data->whereRaw("(`title` LIKE '%" . $request->filter . "%' or `description` LIKE '%" . $request->filter . "%' or `price` LIKE '%" . $request->filter . "%')");
        if ($request->filled('date')) {
            $data->whereDate('created_at', $request->date);
        };
        if ($request->filled('search'))
            $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
        if ($limit)
            $data->limit($limit);
        $data = $data->where('status', 1)->with('images', 'lessons', 'wishlist')->withAvg('reviewList as rating', 'rating')->orderByDesc('id')->get()->toArray();
        $lengths = array_map( function($item) {
            $images = array();
            foreach($item['images'] as $val){
                $img['id'] = $val['id'];
                $img['image'] = isset($val['item_name']) ? assets('uploads/product/'.$val['item_name']) : assets('assets/images/no-image.jpg');
                $images[] = $img;
            }
            $item['images'] = $images;
            $item['wishlist'] = count($item['wishlist']) ? true : false;
            $item['rating'] = isset($item['rating']) ? number_format((float)$item['rating'], 1, '.', '') : 0;
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
