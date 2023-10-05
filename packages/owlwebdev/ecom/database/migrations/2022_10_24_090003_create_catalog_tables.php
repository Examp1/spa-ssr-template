<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->default(0);
            $table->bigInteger('attribute_group_id')->unsigned()->nullable();
            $table->string('slug', 255)->unique();
            $table->string('path', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->bigInteger('order')->default(25);
            $table->timestamps();
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->string('lang', 10);
            $table->tinyInteger('status_lang')->default(1);
            $table->string('name', 255)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->text('options')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('alt', 255)->nullable();
            $table->string('ban_image', 255)->nullable();
            $table->string('ban_alt', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->tinyInteger('meta_created_as')->nullable()->default(0)->comment('Как создано (вручную, авто)');
            $table->boolean('meta_auto_gen')->nullable()->default(true)->comment('Разрешина автогенерация или нет, true - разрешено');
            $table->text('main_screen')->nullable();
            $table->longText('constructor_html')->nullable();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_group_id')->unsigned()->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('type', 255)->default('text');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('attribute_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_id');
            $table->string('lang', 10);
            $table->string('name', 255)->nullable();
        });

        Schema::create('attribute_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_id');
            $table->bigInteger('category_id');
        });

        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('attribute_group_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lang', 5);
            $table->bigInteger('attribute_group_id')->unsigned();

            $table->string('name')->nullable();

            $table->unique(['lang', 'attribute_group_id']);
            $table->foreign('attribute_group_id')
            ->references('id')->on('attribute_groups')
            ->onDelete('cascade');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::create('attribute_attribute_group', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_id');
            $table->bigInteger('attribute_group_id');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('code', 255)->nullable();
            $table->unsignedDecimal('price', 10, 2)->nullable();
            $table->unsignedDecimal('old_price', 10, 2)->nullable();
            $table->boolean('status')->default(0);
            $table->integer('quantity')->default(0);
            $table->string('currency')->nullable(); //ex: UAH
            $table->unsignedDecimal('rating', 10, 2)->nullable();
            $table->integer('reviews_count')->default(0);
            $table->bigInteger('order')->default(25);
            $table->tinyInteger('preorder')->default(1);
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('lang', 10);
            $table->tinyInteger('status_lang')->default(1);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('info')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('alt', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->tinyInteger('meta_created_as')->nullable()->default(0)->comment('Как создано (вручную, авто)');
            $table->boolean('meta_auto_gen')->nullable()->default(true)->comment('Разрешина автогенерация или нет, true - разрешено');
            $table->text('main_screen')->nullable();
            $table->longText('constructor_html')->nullable();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('category_id');
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('attribute_id');
            $table->string('group', 255);
            $table->string('lang', 10);
            $table->string('value', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('alt', 255)->nullable();
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('count')->default(0)->nullable();
            $table->string('color')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->default(0)->nullable();
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->boolean('is_main')->default(false);
        });

        Schema::create('price_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_price_id');
            $table->unsignedBigInteger('product_attribute_id');

            $table->foreign('product_price_id')->references('id')->on('product_prices')->onDelete('cascade');
            $table->foreign('product_attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('price_id')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_id')->unsigned();
            $table->bigInteger('attribute_group_id')->unsigned()->index();
            $table->string('display', 32)->default('select');
            $table->tinyInteger('expanded')->default(0);
            $table->tinyInteger('logic')->default(1);
            $table->tinyInteger('is_main')->default(0);
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->onUpdate('RESTRICT')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
        Schema::dropIfExists('category_translations');

        Schema::table('attributes', function (Blueprint $table) {
            $table->dropForeign('attribute_group_id');
        });
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_translations');

        Schema::dropIfExists('attribute_category');


        Schema::dropIfExists('attribute_group_translations');
        Schema::dropIfExists('attribute_groups');

        Schema::dropIfExists('products');
        Schema::dropIfExists('product_translations');

        Schema::dropIfExists('category_products');

        Schema::dropIfExists('product_attributes');

        Schema::dropIfExists('product_prices');

        Schema::dropIfExists('price_attributes');

        Schema::dropIfExists('product_images');

        Schema::table('filters', function (Blueprint $table) {
            $table->dropForeign('attribute_id');
            $table->dropForeign('attribute_group_id');
        });

        Schema::dropIfExists('filters');
    }
};
