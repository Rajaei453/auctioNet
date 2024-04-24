<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Name of the item
        'description', // Description of the item
        'image', // Image URL of the item
        'minimum_bid', // Minimum bid price
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


}
