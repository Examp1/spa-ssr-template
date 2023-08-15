<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('path', 255)->nullable();
            $table->integer('order')->nullable()->default(0);
            $table->tinyInteger('status')->default(1);
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::create('blog_category_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_categories_id');
            $table->string('lang', 10);
            $table->string('name', 255)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('alt', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->tinyInteger('meta_created_as')->nullable()->default(0)->comment('Як створено (вручну, авто)');
            $table->boolean('meta_auto_gen')->nullable()->default(true)->comment('Дозволено автогенерацію чи ні, true - дозволено');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blog_category_translations');
    }
}
