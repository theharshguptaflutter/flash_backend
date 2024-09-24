<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_document_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_detail_id')->nullable();
            $table->integer('user_id');
            $table->string('professional_driving_permit_name')->nullable();
            $table->string('driver_photo')->nullable();
            $table->string('driving_evaluation_report')->nullable();
            $table->string('safety_screening_result')->nullable();
          
            $table->string('vehicle_insurance_policy')->nullable();
            $table->string('vehicle_card_double_disk')->nullable();

            $table->string('vehicle_inspection_id')->nullable();
            $table->string('locate_inspection_center_name')->nullable();
            $table->string('vehicle_document')->nullable();
           
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
        Schema::dropIfExists('driver_document_details');
    }
}
