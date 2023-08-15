<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->integer('order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('blog_tag_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_tags_id');
            $table->string('lang',10);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image',255)->nullable();
            $table->string('alt',255)->nullable();
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
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_tag_translations');
    }
}
