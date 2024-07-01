<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $key = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function community(){
        return $this->belongsTo(User::class, 'community_id', 'id');
    }

    public function images(){
        return $this->hasMany(Image::class, 'item_id', 'id')->where('item_type', 'post')->orderByDesc('id');
    }
}
