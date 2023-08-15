<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->nullable();
            $table->integer('order')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('template')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pages_id');
            $table->string('lang', 10);
            $table->tinyInteger('status_lang')->default(1);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->longText('constructor_html')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('alt', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->tinyInteger('meta_created_as')->nullable()->default(0)->comment('Як створено (вручну, авто)');
            $table->boolean('meta_auto_gen')->nullable()->default(true)->comment('Дозволено автогенерацію чи ні, true - дозволено');
            $table->text('main_screen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_translations');
    }
}
