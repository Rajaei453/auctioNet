<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_details';

    protected $fillable = [
        'auction_id',
        'brand',
        'model',
        'manufacturing_year',
        'registration_year',
        'engine_type',
        'country',
        'city',
        'area',
        'street',
        'floor',
        'total_area',
        'num_bedrooms',
        'num_bathrooms',
    ];

    /**
     * Get the auction that owns the details.
     */
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
