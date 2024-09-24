<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('passenger_id');
            $table->integer('driver_id');
            $table->integer('ride_id')->nullable();           
            $table->integer('rating')->nullable();
            $table->text('comment')->nullable();
            $table->text('improve_type')->nullable();
            $table->decimal('tip_amount',10,2)->nullable();
            $table->decimal('charity_amount',10,2)->nullable();
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
        Schema::dropIfExists('driver_reviews');
    }
}
