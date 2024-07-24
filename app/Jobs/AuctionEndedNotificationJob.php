<?php

namespace App\Jobs;

use App\Models\Auction;
use App\Models\Notification;
use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AuctionEndedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auction;

    /**
     * Create a new job instance.
     *
     * @param Auction $auction
     * @return void
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $auction = $this->auction;

        // Get all bids for the auction
        $bids = Bid::where('auction_id', $auction->id)->get();

        // Get the winner of the auction
        $winner = $auction->highestBidder;

        foreach ($bids as $bid) {
            $message = $bid->user_id === $winner->id
                ? 'Congratulations! You won the auction for ' . $auction->name . '.'
                : 'Sorry, you did not win the auction for ' . $auction->name . '.';

            Notification::create([
                'user_id' => $bid->user_id,
                'name' => 'Auction Ended',
                'content' => $message,
                'status' => 0,
            ]);
        }
    }
}
