<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration
{
    /**
     * Run migrations
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('instance')->index();
            $table->longText('data')->nullable();
            $table->string('lang', 2)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
