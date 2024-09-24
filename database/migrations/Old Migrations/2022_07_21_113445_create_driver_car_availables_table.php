<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverCarAvailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_car_availables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->nullable();
            $table->decimal('cur_lat',11,8)->nullable();
            $table->decimal('cur_long', 11,8)->nullable();
            $table->string('cur_location')->nullable();
            $table->string('for_hire')->comment('Y=yes, N =no, P=pending')->default('N');
            $table->string('is_available')->comment('Y=yes, N =no, P=pending')->default('N');
            $table->string('is_requested')->comment('Y=yes, N =no')->default('N');
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
        Schema::dropIfExists('driver_car_availables');
    }
}
