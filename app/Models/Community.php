<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $table = 'communities';
    protected $key = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function images(){
        return $this->hasMany(Image::class, 'item_id', 'id')->where('item_type', 'community')->orderByDesc('id');
    }

    public function communityFollower(){
        return $this->hasMany(FollowCommunity::class, 'community_id', 'id')->orderByDesc('id');
    }

    public function communityPost(){
        return $this->hasMany(Post::class, 'community_id', 'id')->orderByDesc('id');
    }

    public function allCommunities($request, $limit = null){
        $data = $this->newQuery();
        if ($request->filled('filter'))
            $data->whereRaw("(`name` LIKE '%" . $request->filter . "%' or `description` LIKE '%" . $request->filter . "%')");
        if ($request->filled('date')) {
            $data->whereDate('created_at', $request->date);
        };
        if ($request->filled('search'))
            $data->whereRaw("(`name` LIKE '%" . $request->search . "%')");
        if ($limit)
            $data->limit($limit);
        $data = $data->where('status', 1)->with('images', 'communityPost')->orderByDesc('id')->get()->toArray();
        $lengths = array_map( function($item) {
            $images = array();
            foreach($item['images'] as $val){
                $img['id'] = $val['id'];
                $img['image'] = isset($val['item_name']) ? assets('uploads/community/'.$val['item_name']) : assets('assets/images/no-image.jpg');
                $images[] = $img;
            }
            $item['images'] = $images;
            $item['community_post'] = count($item['community_post']);
            $item['created_at'] = date('m-d-Y h:iA', strtotime($item['created_at']));
            $item['updated_at'] = date('m-d-Y h:iA', strtotime($item['updated_at']));
            return $item;
        } , $data);
        return $lengths;
    }

    public function followedCommunities($request, $limit = null){
        $data = $this->newQuery()->join('user_followed_community', 'communities.id', '=', 'user_followed_community.community_id')->where('user_followed_community.user_id', auth()->user()->id);
        if ($request->filled('search'))
            $data->whereRaw("(`name` LIKE '%" . $request->search . "%')");
        if ($limit)
            $data->limit($limit);
        $data = $data->where('status', 1)->with('images', 'communityPost')->orderByDesc('id')->get()->toArray();
        $lengths = array_map( function($item) {
            $images = array();
            foreach($item['images'] as $val){
                $img['id'] = $val['id'];
                $img['image'] = isset($val['item_name']) ? assets('uploads/community/'.$val['item_name']) : assets('assets/images/no-image.jpg');
                $images[] = $img;
            }
            $item['images'] = $images;
            $item['community_post'] = count($item['community_post']);
            $item['created_at'] = date('m-d-Y h:iA', strtotime($item['created_at']));
            $item['updated_at'] = date('m-d-Y h:iA', strtotime($item['updated_at']));
            return $item;
        } , $data);
        return $lengths;
    }

    public function details($id){
        $data = $this->newQuery();
        $data = $data->where('id', $id)->with('images', 'communityPost')->first();
        return $data;
    }
}
