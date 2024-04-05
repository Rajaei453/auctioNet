<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use mysql_xdevapi\Table;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Reservation;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    //protected $table = ['user'];
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'city',
        'created_at',
        'updated_at',
        'device_token',
        'bidding_history', // Bidding history of the user (you may need to serialize this field)
        'favorite_cars', // Favorite cars of the user (you may need to serialize this field)


    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        ];



    public function reviews()
    {
        return $this->hasMany(Review::class,'user_id','id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class,'user_id','id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
