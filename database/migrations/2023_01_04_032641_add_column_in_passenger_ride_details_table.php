<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInPassengerRideDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('passenger_ride_details', function (Blueprint $table) {
            //
		$table->decimal('subscription_charge', 12,2)->default(0);
		$table->decimal('npo', 12,2)->default(0);
		$table->decimal('card_base', 12,2)->default(0);
		$table->decimal('card_charge', 12,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('passenger_ride_details', function (Blueprint $table) {
            //
        });
    }
}
