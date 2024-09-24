<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverVerificationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_verification_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_detail_id')->nullable();
            $table->integer('driver_id');
            $table->integer('inspector_id');
            $table->string('is_road_worth')->nullable()->comment('Y=yes, N=no');
            $table->string('is_functional_defects')->nullable()->comment('Y=yes, N=no');
            $table->string('is_warning_light_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_wheels_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_steering_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_window_screen_wiper')->nullable()->comment('Y=yes, N=no');
            $table->string('is_head_light_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_indicator_light_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_brake_light_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_hooter_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_seat_belts_present')->nullable()->comment('Y=yes, N=no');
            $table->string('is_spare_jack_triangle_present')->nullable()->comment('Y=yes, N=no');
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
        Schema::dropIfExists('driver_verification_documents');
    }
}
