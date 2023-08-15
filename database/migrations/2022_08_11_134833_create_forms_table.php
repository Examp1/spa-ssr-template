<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run migrations
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('instance')->nullable();
            $table->string('lang', 2)->index();
            $table->longText('data')->nullable();
            $table->integer('main_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
