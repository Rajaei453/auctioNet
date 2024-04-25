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
            $table->decimal('increment_amount', 10, 2)->nullable()->after('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn('increment_amount');
        });
    }
};
