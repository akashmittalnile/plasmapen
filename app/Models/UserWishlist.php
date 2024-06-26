<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWishlist extends Model
{
    use HasFactory;
    protected $table = 'user_wishlist';
    protected $key = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'object_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'object_id', 'id');
    }
}
