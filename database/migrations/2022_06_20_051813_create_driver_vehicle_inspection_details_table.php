<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverVehicleInspectionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_vehicle_inspection_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_detail_id')->nullable();
            $table->integer('user_id');
            $table->string('vehicle_inspection_document')->nullable();
            $table->string('vehicle_document_type')->nullable();
            $table->integer('status')->default('1')->comment('1=Active, 2=Inactive');
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
        Schema::dropIfExists('driver_vehicle_inspection_details');
    }
}
