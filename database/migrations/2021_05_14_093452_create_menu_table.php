<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('visibility')->default(1);
            $table->string('tag');
            $table->tinyInteger('const')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->BigInteger('model_id')->nullable();
            $table->nestedSet();
        });

        Schema::create('menu_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('menu_id');
            $table->string('lang',10);
            $table->string('name', 255)->nullable();
            $table->string('url', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
        Schema::dropIfExists('menu_translations');
    }
}
