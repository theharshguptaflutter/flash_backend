<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverVerificationDocumentPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_verification_document_pictures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->nullable();
            $table->bigInteger('driver_detail_id')->nullable();
            $table->string('road_worthiness_picture')->nullable();
            $table->string('functional_defect_picture')->nullable();
            $table->string('warning_light_picture')->nullable();
            $table->string('wheel_picture')->nullable();
            $table->string('steering_picture')->nullable();
            $table->string('window_screen_picture')->nullable();
            $table->string('head_light_picture')->nullable();
            $table->string('indicator_light_picture')->nullable();
            $table->string('brake_light_picture')->nullable();
            $table->string('hooter_picture')->nullable();
            $table->string('seat_belt_picture')->nullable();
            $table->string('jack_triangle_picture')->nullable();
            $table->string('status')->default('A')->comment('A=Active, I=Inactive');
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
        Schema::dropIfExists('driver_verification_document_pictures');
    }
}
