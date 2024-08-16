<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // the owner of the auction
        'name', // Name of the item
        'type', // Type of the item
        'description', // Description of the item
        'image', // Image URL of the item
        '3d_model_link', // 3d_model URL of the 3d item
        'minimum_bid', // Minimum bid price
        'increment_amount', // increment amount for bid
        'highest_bidder_id', // Highest bidder ID
        'winner_id', // Winner's ID
        'start_time',// Start time of the auction
        'end_time', // End time of the auction
        'category_id', // Category ID
        'status', // Category ID
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    public function details()
    {
        return $this->hasOne(AuctionDetail::class);
    }

    public function highestBid()
    {
        return $this->hasOne(Bid::class)->latest('amount');
    }

    public function highestBidAmount()
    {
        $highestBid = $this->highestBid;
        return $highestBid ? $highestBid->amount : null;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'attachable');
    }

}
