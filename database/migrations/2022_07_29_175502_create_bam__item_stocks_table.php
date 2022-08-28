<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBamItemStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bam__item_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_category');
            $table->string('item');
            $table->string('supplier_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('document')->nullable();
            $table->string('description')->nullable();
            $table->string('school_id')->nullable();
            $table->string('added_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('bam__item_stocks');
    }
}
