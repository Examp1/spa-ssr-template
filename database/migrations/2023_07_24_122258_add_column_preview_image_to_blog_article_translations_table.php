<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPreviewImageToBlogArticleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_article_translations', function (Blueprint $table) {
            $table->string('preview_image')->nullable();
            $table->string('preview_alt', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_article_translations', function (Blueprint $table) {
            $table->dropColumn([
                'preview_image',
                'preview_alt',
                ]);
        });
    }
}
