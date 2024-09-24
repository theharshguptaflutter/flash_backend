<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverDocumentPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_document_pictures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->nullable();
            $table->bigInteger('driver_detail_id')->nullable();
            $table->string('id_number_picture')->nullable();
            $table->string('registration_picture')->nullable();
            $table->string('license_picture')->nullable();
            $table->string('vin_picture')->nullable();
            $table->string('exterior_color_picture')->nullable();
            $table->string('interior_color_picture')->nullable();
            $table->string('first_registration_picture')->nullable();
            $table->string('road_worthy_picture')->nullable();
            $table->string('license_expiration_picture')->nullable();
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
        Schema::dropIfExists('driver_document_pictures');
    }
}
