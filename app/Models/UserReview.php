<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;
    protected $table = 'user_review';
    protected $key = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
