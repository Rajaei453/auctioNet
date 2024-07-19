<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'stars',
        'user_id',
        'provider_id',
    ];

    protected $hidden = [];

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }
    public function provider(){

        return $this->belongsTo(Provider::class,'provider_id','id');
    }
}
