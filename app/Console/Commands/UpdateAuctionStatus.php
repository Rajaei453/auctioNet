<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateAuctionStatus extends Command
{
    protected $signature = 'auction:update-status';
    protected $description = 'Update the status of auctions based on start and end times';

    public function handle()
    {
        // Get auctions where the start time is before or equal to the current time
        $ongoingAuctions = Auction::whereNotNull('start_time')
            ->where('start_time', '<=', Carbon::now())
            ->where('status', 'pending')
            ->get();

        // Update the status of ongoing auctions to 'ongoing'
        foreach ($ongoingAuctions as $auction) {
            $auction->update(['status' => 'ongoing']);
        }

        // Get auctions where the end time is before or equal to the current time
        $closedAuctions = Auction::where('end_time', '<=', Carbon::now())
            ->where('status', 'ongoing')
            ->get();

        // Update the status of closed auctions to 'closed'
        foreach ($closedAuctions as $auction) {
            $auction->update(['status' => 'closed']);
        }

        $this->info('Auction statuses updated successfully.');
    }
}
