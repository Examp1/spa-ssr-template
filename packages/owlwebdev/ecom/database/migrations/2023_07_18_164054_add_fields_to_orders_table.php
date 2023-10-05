<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_surname')->nullable()->after('shipping_lastname');
            $table->string('shipping_city_id')->nullable()->after('shipping_city'); // NP
            $table->string('shipping_street')->nullable()->before('shipping_apartment');
            $table->string('shipping_house')->nullable()->before('shipping_apartment');
            $table->string('shipping_branch')->nullable()->after('shipping_postcode');
            $table->string('shipping_branch_id')->nullable()->after('shipping_postcode');

            $table->string('billing_lastname')->nullable()->before('billing_country');
            $table->string('billing_surname')->nullable()->before('billing_country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_surname', 'shipping_city_id', 'shipping_street', 'shipping_house', 'shipping_branch', 'shipping_branch_id',
                'billing_lastname', 'billing_surname'
                ]);
        });
    }
}
