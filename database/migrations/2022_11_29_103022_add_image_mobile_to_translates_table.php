<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageMobileToTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('image_mob',255)->nullable();
            $table->string('alt_mob',255)->nullable();
        });

        Schema::table('landing_translations', function (Blueprint $table) {
            $table->string('image_mob',255)->nullable();
            $table->string('alt_mob',255)->nullable();
        });

        Schema::table('blog_article_translations', function (Blueprint $table) {
            $table->string('image_mob',255)->nullable();
            $table->string('alt_mob',255)->nullable();
        });

        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->string('image_mob',255)->nullable();
            $table->string('alt_mob',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
