<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->uuid('product_id');
            $table->unsignedBigInteger('brand_id'); //check it later
            $table->unsignedBigInteger('supplier_id');
            $table->decimal('mrp')->default(0);
            $table->integer('quantity');
            $table->integer('sold')->default(0);
            $table->integer('defective')->default(0);
            $table->integer('available');
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
        Schema::dropIfExists('purchases');
    }
}
