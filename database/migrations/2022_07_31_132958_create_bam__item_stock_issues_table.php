<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBamItemStockIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bam__item_stock_issues', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->string('user_id');
            $table->string('user_id_issuer');
            $table->string('issue_date');
            $table->string('return_date');
            $table->string('notes');
            $table->string('item_category_id');
            $table->string('item');
            $table->string('quantity');
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
        Schema::dropIfExists('bam__item_stock_issues');
    }
}
