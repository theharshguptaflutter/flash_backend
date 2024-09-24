<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->index();
            $table->unsignedBigInteger('ride_id')->index();

            $table->integer('step_number');
            $table->string('step_name');
            $table->enum('status', config('flash_app.steps.status'));
            $table->timestamps();

            # Create forign key
            $table->foreign('driver_id')->references('id')->on('users');
            $table->foreign('ride_id')->references('id')->on('passenger_ride_details')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ride_histories');
    }
}
