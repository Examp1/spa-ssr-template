<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('main_category_id')->nullable();
            $table->string('slug')->unique();
            $table->integer('order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('template')->nullable();
            $table->bigInteger('views')->default(0);
            $table->bigInteger('user_id')->default(0)->index();
            $table->dateTime('public_date')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_article_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_articles_id');
            $table->string('lang', 10);
            $table->tinyInteger('status_lang')->default(1);
            $table->string('name', 255)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('text')->nullable();
            $table->longText('constructor_html')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('blog_articles');
        Schema::dropIfExists('blog_article_translations');
    }
}
