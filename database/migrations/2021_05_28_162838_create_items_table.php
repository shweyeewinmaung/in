<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('itemname_id');            
            $table->string('model')->nullable();
            $table->string('mac')->nullable();
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable()->default(null);
            $table->unsignedBigInteger('store_id')->nullable()->default(null);
            $table->integer('unit_price')->default(0);
            $table->integer('amount')->default(0);
            $table->integer('total_qty')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('used_qty')->default(0);
            $table->integer('transfer_qty')->default(0);
            $table->integer('damage_qty')->default(0);
            $table->text('damage_reason')->nullable();
            $table->integer('category_id');
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
        Schema::dropIfExists('items');
    }
}
