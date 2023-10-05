<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_filters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->string('link', 255)->nullable();
            $table->integer('order')->nullable()->default(25);
            $table->timestamps();
        });

        Schema::create('category_filter_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_filter_id');
            $table->string('lang', 10);
            $table->string('name', 255)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_filters');
        Schema::dropIfExists('category_filter_translations');
    }
}
