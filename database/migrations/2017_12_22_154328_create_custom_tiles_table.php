<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomTilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('custom_tiles')) {
            Schema::create('custom_tiles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', '255')->nullable();
                $table->string('file', '1000')->nullable();
                $table->integer('width')->nullable();
                $table->integer('height')->nullable();
                $table->string('shape', '32')->default('square');
                $table->integer('user_id')->nullable();
                $table->string('session_token', '1000')->nullable();
                $table->text('settings')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_tiles');
    }
}
