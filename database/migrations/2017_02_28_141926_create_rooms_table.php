<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', '255');
                $table->string('type', '32')->nullable();
                $table->string('sourcesPath', '1000');
                $table->string('iconfile', '255')->nullable();
                $table->integer('mapSize')->default('1024')->nullable();
                $table->integer('cameraFov')->default('60')->nullable();
                $table->text('size');
                $table->integer('firstPersonViewHeight')->default('1500')->nullable();
                $table->text('endPoints');
                $table->text('parts');
                $table->text('mirrors');
                $table->text('tiledSurfaces');
                $table->boolean('useMirrors')->default(false)->nullable();
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
        Schema::dropIfExists('rooms');
    }
}
