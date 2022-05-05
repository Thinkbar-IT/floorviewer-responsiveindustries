<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('savedrooms')) {
            Schema::create('savedrooms', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('userid')->nullable();
                $table->integer('roomid');
                $table->string('engine', '16')->nullable();
                $table->string('url', '1000')->nullable();
                $table->string('image', '1000')->nullable();
                $table->text('note')->nullable();
                $table->text('roomsettings')->nullable();
                $table->boolean('enabled')->default(true);
                $table->string('session_token', '1000')->nullable();
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
        Schema::dropIfExists('savedrooms');
    }
}
