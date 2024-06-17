<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpSupport extends Model
{
    use HasFactory;
    protected $table = 'help_and_supports';
    protected $key = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(['name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }
}
