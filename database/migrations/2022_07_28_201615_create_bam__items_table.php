<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBamItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bam__items', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->integer('category_id');
            $table->string('unit');
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
        Schema::dropIfExists('bam__items');
    }
}
