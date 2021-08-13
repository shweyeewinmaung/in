<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number');
            $table->integer('user_id');
            $table->integer('confirm_user_id')->nullable();
            $table->text('transfer_sign_file')->nullable();
            $table->string('status');
            $table->string('from');
            $table->string('to');
            $table->text('content')->nullable();
            $table->string('staff_id')->nullable();
            $table->integer('store_id');
            
           
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
        Schema::dropIfExists('transfers');
    }
}
