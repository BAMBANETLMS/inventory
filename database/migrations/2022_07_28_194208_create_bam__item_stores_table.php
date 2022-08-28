<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBamItemStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bam__item_stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_code');
            $table->string('description');
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
        Schema::dropIfExists('bam__item_stores');
    }
}
