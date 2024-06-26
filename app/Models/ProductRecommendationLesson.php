<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRecommendationLesson extends Model
{
    use HasFactory;
    protected $table = 'product_recommendation_lessons';
    protected $key = 'id';

    public function product(){
        return $this->belongsTo(User::class, 'product_id', 'id');
    }
}
