<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('coupon_translations');
        Schema::dropIfExists('coupons');


        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('apartment')->nullable();
            $table->string('postcode')->nullable();
            $table->string('role')->nullable();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);

            $table->string('shipping_name')->nullable();
            $table->string('shipping_lastname')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_apartment')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_postcode')->nullable();

            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_province')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_postcode')->nullable();
            $table->string('billing_apartment')->nullable();

            $table->string('payment_name')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('shipping_method')->nullable();

            $table->integer('coupon_id')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('order_status_id')->unsigned();
            $table->string('paypal_id')->nullable();
            $table->string('tracking')->nullable();
            $table->integer('notified')->unsigned()->default(0);

            $table->text('comment')->nullable();
            $table->string('currency')->nullable(); //ex: UAH
            $table->text('utm_data')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('option_id')->unsigned()->nullable();
            $table->text('option_data')->nullable();
            $table->string('name');
            $table->decimal('price')->default(0);
            $table->decimal('special')->nullable();
            $table->decimal('coupon_price')->nullable();
            $table->integer('count')->unsigned();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->nullable()->onDelete('set null');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('session_id')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('option_id')->unsigned()->nullable();
            $table->integer('count')->unsigned();
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->integer('value');
            $table->integer('quantity')->nullable();
            $table->string('type');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('coupon_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('lang')->index();
            $table->bigInteger('coupon_id')->unsigned();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('address');
            $table->dropColumn('apartment');
            $table->dropColumn('postcode');
            $table->dropColumn('role');
        });
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('coupon_translations');
    }
}
