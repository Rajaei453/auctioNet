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
            // Add user_id column
            $table->unsignedBigInteger('user_id');

            // Define foreign key constraint
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);

            // Drop user_id column
            $table->dropColumn('user_id');
        });
    }
};
