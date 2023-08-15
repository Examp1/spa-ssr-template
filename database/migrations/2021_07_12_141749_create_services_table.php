<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug',255)->unique();
            $table->boolean('status')->default(0);
            $table->bigInteger('main_category_id')->nullable();
            $table->string('price', 255)->unique()->default('');
            $table->string('old_price', 255)->unique()->default('');
            $table->bigInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id');
            $table->string('lang',10);
            $table->tinyInteger('status_lang')->default(1);
            $table->string('title',255)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->text('options')->nullable();
            $table->longText('constructor_html')->nullable();
            $table->string('meta_title',255)->nullable();
            $table->string('meta_description',255)->nullable();
            $table->tinyInteger('meta_created_as')->nullable()->default(0);
            $table->boolean('meta_auto_gen')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_translations');
    }
}
