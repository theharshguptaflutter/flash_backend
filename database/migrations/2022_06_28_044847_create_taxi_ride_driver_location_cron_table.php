<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxiRideDriverLocationCronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxi_ride_driver_location_cron', function (Blueprint $table) {
            $table->id();
            $table->string('passenger_ride_id');
            $table->string('driver_id');
            $table->string('passenger_id');
            $table->integer('status')->nullable()->comment('1=active, 2= Inactive');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxi_ride_driver_location_cron');
    }
}
