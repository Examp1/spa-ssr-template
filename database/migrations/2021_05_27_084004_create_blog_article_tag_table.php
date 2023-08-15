<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogArticleTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_article_tag', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_article_id')->unsigned();
            $table->bigInteger('blog_tag_id')->unsigned();

            $table->foreign('blog_article_id')->references('id')->on('blog_articles')->onDelete('cascade');
            $table->foreign('blog_tag_id')->references('id')->on('blog_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_article_tag');
    }
}
