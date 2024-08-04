<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\Notification;
use App\Models\Bid;
use Carbon\Carbon;
use Illuminate\Console\Command;

class   UpdateAuctionStatus extends Command
{
    protected $signature = 'auction:update-status';
    protected $description = 'Update the status of auctions based on start and end times';

    public function handle()
    {
        // Get auctions where the start time is before or equal to the current time
        // and the end time is after the current time
        $ongoingAuctions = Auction::whereNotNull('start_time')
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>', Carbon::now())
            ->where('status', 'pending')
            ->get();

        // Update the status of ongoing auctions to 'ongoing'
        foreach ($ongoingAuctions as $auction) {
            $auction->update(['status' => 'ongoing']);
        }

        // Get auctions where the end time is before or equal to the current time
        // and the current status is 'ongoing'
        $closedAuctions = Auction::where('end_time', '<=', Carbon::now())
            ->where('status','!=', 'closed')
            ->get();

        // Update the status of closed auctions to 'closed'
        foreach ($closedAuctions as $auction) {
            $auction->update(['status' => 'closed']);
            $this->sendNotifications($auction);
        }

        $this->info('Auction statuses updated successfully.');
    }

    protected function sendNotifications(Auction $auction)
    {
        // Get all bids for the auction
        $bids = Bid::where('auction_id', $auction->id)->get();

        // Get the highest bidder (winner) of the auction
        $highestBid = $bids->sortByDesc('amount')->first();
        $winnerId = $highestBid ? $highestBid->user_id : null;

        // Get unique user IDs of all bidders
        $userIds = $bids->pluck('user_id')->unique();

        foreach ($userIds as $userId) {
            $message = $userId === $winnerId
                ? 'Congratulations! You won the auction for ' . $auction->name . '.'
                : 'Sorry, you did not win the auction for ' . $auction->name . '.';

            Notification::create([
                'user_id' => $userId,
                'name' => 'Auction Ended',
                'content' => $message,
                'status' => '0',
                'attachable_id' => $auction->id,
                'attachable_type' => get_class($auction),
            ]);
        }
    }
}
