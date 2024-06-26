<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowCommunity extends Model
{
    use HasFactory;
    protected $table = 'user_followed_community';
    protected $key = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
