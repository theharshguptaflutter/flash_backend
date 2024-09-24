<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
             $table->string('password')->nullable();
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->string('gender',10)->nullable()->comment('F=Female, M=Male');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('driver_approval',10)->default("P")->comment('A=Approved, P=Pending, R=Rejected');
            $table->string('user_type',10)->nullable()->comment('A=Admin, D=Driver, P=Passenger,S=Service Center');
            $table->string('login_type',10)->default("N")->comment('N=Normal, F=Facebook, G=Google');
            $table->string('status')->default('P')->comment('Y=Active, N=Blocked, P=Pending, MP=Mobile Verification Pending, EP=Email Verification Pending, D=Deleted');
            $table->integer('step')->nullable()->comment('1, 2, 3, 4, 5');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
