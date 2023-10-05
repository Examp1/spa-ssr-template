<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeGridTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_grids', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->boolean('status')->default(0);
            $table->bigInteger('order')->default(25);
            $table->timestamps();
        });

        Schema::create('size_grid_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('size_grid_id');
            $table->string('lang', 10);
            $table->tinyInteger('status_lang')->default(1);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('alt', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('size_grids');
        Schema::dropIfExists('size_grid_translations');
    }
};
