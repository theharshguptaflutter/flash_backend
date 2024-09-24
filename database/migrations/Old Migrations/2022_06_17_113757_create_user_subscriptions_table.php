<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('plan_id')->nullable();
            $table->string('subscription_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->dateTime('plan_period_start_date')->nullable();
            $table->dateTime('plan_period_end_date')->nullable();
            $table->integer('event_type')->nullable()->comment('1=initial, 2=renew');
            $table->integer('is_cancel')->nullable()->comment('1=active,2=cancel');
            $table->integer('is_runing')->nullable()->comment('1=running,2=not running');
            $table->integer('status')->nullable()->comment('2=inactive,1=active');
            $table->text('api_subscription_response')->nullable();
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
        Schema::dropIfExists('user_subscriptions');
    }
}
