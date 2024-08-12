<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Bid extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'user_id', // The user who placed the bid
        'auction_id', // The auction for which the bid is placed
        'amount', // The bid amount
        // Add more fillable properties as needed
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
