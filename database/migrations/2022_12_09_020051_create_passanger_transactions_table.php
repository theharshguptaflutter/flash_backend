<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassangerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passanger_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('pay_request_id');
            $table->string('paygate_id');
            $table->string('reference')->nullable();         
            $table->string('transaction_id')->nullable();
            $table->string('result_code')->nullable();
            $table->string('auth_code')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->string('result_desc')->nullable();
            $table->string('pay_method')->nullable();
            $table->text('pay_method_detail')->nullable();
            $table->string('vault_id')->nullable();
            $table->string('payvault_data_1')->nullable();
            $table->string('payvault_data_2')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('checksum')->nullable();
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
        Schema::dropIfExists('passanger_transactions');
    }
}
