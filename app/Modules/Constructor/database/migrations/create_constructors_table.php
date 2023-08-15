<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstructorsTable extends Migration
{
    /**
     * Run migrations
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constructors', function (Blueprint $table) {
            $table->id();
            $table->integer('constructorable_id')->nullable();
            $table->string('constructorable_type')->nullable();
            $table->longText('data')->nullable();
        });
    }

    /**
     * Reverse migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constructors');
    }
}
