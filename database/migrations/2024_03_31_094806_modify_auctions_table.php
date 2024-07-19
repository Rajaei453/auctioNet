<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Remove specific columns for cars
            $table->dropColumn(['make', 'model', 'year']);

            // Add general columns for any type of item
            $table->string('name'); // Name of the item
            $table->string('description')->nullable(); // Description of the item
            $table->string('image')->nullable(); // Image URL of the item
            // Add more general columns as needed

            // Add foreign key for item categories
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories'); // Adjusted to 'categories'

            // Remove unnecessary columns
            $table->dropColumn(['starting_bid', 'current_bid', 'highest_bidder_id', 'auction_end_time']);

            // Add new columns for bid tracking
            $table->decimal('minimum_bid', 10, 2)->default(0); // Minimum bid amount
            $table->unsignedBigInteger('winner_id')->nullable(); // Winner's ID
            $table->timestamp('end_time')->nullable(); // End time of the auction
            // Add more bid tracking columns as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Reverse the changes made in the 'up' method
            $table->string('make');
            $table->string('model');
            $table->unsignedSmallInteger('year');

            $table->dropColumn(['name', 'description', 'image', 'category_id', 'minimum_bid', 'winner_id', 'end_time']);

            $table->decimal('starting_bid', 10, 2)->default(0);
            $table->decimal('current_bid', 10, 2)->default(0);
            $table->unsignedBigInteger('highest_bidder_id')->nullable();
            $table->timestamp('auction_end_time')->nullable();
        });
    }
};
