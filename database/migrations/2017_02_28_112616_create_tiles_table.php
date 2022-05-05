<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tiles')) {
            Schema::create('tiles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', '255');
                $table->string('shape', '32')->default('square');
                $table->integer('width');
                $table->integer('height');
                $table->string('surface', '32')->nullable();
                $table->string('finish', '32')->nullable()->default('glossy');
                $table->string('file', '1000');
                $table->boolean('grout')->nullable()->default(true);
                $table->string('url', '1000')->nullable();
                $table->string('rotoPrintSetName', '100')->nullable();
                $table->text('expProps')->nullable();
                $table->integer('access_level')->nullable();
                $table->boolean('enabled')->default(true);
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
        Schema::dropIfExists('tiles');
    }
}
