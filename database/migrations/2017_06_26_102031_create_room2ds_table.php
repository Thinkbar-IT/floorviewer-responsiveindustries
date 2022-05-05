<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoom2dsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('room2ds')) {
            Schema::create('room2ds', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', '255');
                $table->string('type', '32')->nullable();
                $table->string('icon', '255')->nullable();
                $table->string('image', '1000');
                $table->string('shadow', '1000');
                $table->string('shadow_matt', '1000');
                $table->text('surfaces');
                $table->boolean('enabled')->default(true)->nullable();
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
        Schema::dropIfExists('room2ds');
    }
}
