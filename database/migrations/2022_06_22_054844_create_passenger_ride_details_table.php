<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassengerRideDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passenger_ride_details', function (Blueprint $table) {
            $table->id();
            $table->integer('passenger_id');
            $table->integer('driver_id')->nullable();
            $table->text('from_address')->nullable();
            $table->text('to_address')->nullable();
            $table->decimal('from_latitude',11,8)->nullable();
            $table->decimal('from_longitude',11,8)->nullable();
            $table->decimal('to_latitude',11,8)->nullable();
            $table->decimal('to_longitude',11,8)->nullable();
            $table->integer('seat_no')->nullable();
            $table->dateTime('schedule_date')->nullable();
            $table->string('schedule_time')->nullable();
            $table->integer('car_type')->nullable();
            $table->decimal('distance',10,2)->nullable();
            $table->decimal('estimated_distance',10,2)->nullable();
            $table->decimal('total_distance',10,2)->nullable();
            $table->decimal('fare',10,2)->default(0.00);
            $table->decimal('estimated_fare',10,2)->default(0.00);
            $table->decimal('total_fare',10,2)->default(0.00);
            $table->decimal('discount',10,2)->nullable();
            $table->string('coupon_id')->nullable();
            $table->integer('ride_rating')->default(0);
            $table->integer('paid_by')->comment('1= cash, 2 = card')->nullable();
            $table->integer('paid_status')->comment('1= paid, 2 = pending, 3= not paid')->default(2);
            $table->integer('trip_status')->comment('1= start trip, 2 = end trip, 3= cancel trip,4= upcoming trip')->default(4);
            $table->string('cancel_ride_by')->comment('P= Passenger, D = Driver, N = None')->default('N');
            $table->text('cancel_reason')->nullable();
            $table->string('refund_status')->comment('Y= yes, N = no')->nullable();
            $table->dateTime('start_trip_date')->nullable();
            $table->dateTime('end_trip_date')->nullable();
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
        Schema::dropIfExists('passenger_ride_details');
    }
}
