<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Add a column to indicate the type of auction
            $table->enum('type', ['regular', 'live', 'anonymous'])->default('regular');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Drop the added column if rolling back the migration
            $table->dropColumn('type');
        });
    }
};
