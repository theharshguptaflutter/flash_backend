<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('owner_name')->nullable();
            $table->string('id_number')->nullable();
            $table->integer('make')->nullable();
            $table->integer('model')->nullable();
            $table->longText('vehicle_description')->nullable();
            $table->string('year')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('km_reading')->nullable();
            $table->string('license_number')->nullable();
            $table->string('vin_number')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('interior_trim')->nullable();
            $table->string('transmission')->nullable();
            $table->dateTime('start_date_registration')->nullable();
            $table->dateTime('end_date_road_worthy')->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->dateTime('vehicle_license_expiry')->nullable();
            
            $table->dateTime('inspection_date')->nullable();
            $table->string('is_admin_approve')->default('P')->comment('P=pending, Y=yes,R=reject');
            $table->string('unique_inspection_id')->nullable();
            

            $table->dateTime('driver_complete_date')->nullable();
            $table->string('is_driver_complete')->default('N')->comment('P=pending, Y=yes, N=no');
            $table->longText('reject_document_reason')->nullable();
            $table->string('is_update_inspection')->default('N')->comment('Y=yes, N=no');
            $table->string('is_payment_completed')->default('N')->comment('Y=yes, N=no');
            $table->string('status')->default('A')->comment('A=Active, I=Inactive');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_details');
    }
}
