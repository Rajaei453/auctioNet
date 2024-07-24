<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Notification extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'content',
        'status',
        'created_at',
        'updated_at',

    ];

    protected $hidden = [];

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }

}
