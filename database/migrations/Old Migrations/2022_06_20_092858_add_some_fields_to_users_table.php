<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('cur_lat',11,8)->after('driver_approval')->nullable();
            $table->decimal('cur_long', 11,8)->after('cur_lat')->nullable();
            $table->string('location')->after('cur_long')->nullable();            
            $table->string('is_online')->after('location')->comment('Y= yes, N = no')->default('N');
            $table->decimal('avg_rating', 11,2)->after('is_online')->nullable();
            $table->string('is_covid_accepted')->after('avg_rating')->comment('Y= yes, N = no')->default('N');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cur_lat', 'cur_long', 'location','is_online']);
        });
    }
}
