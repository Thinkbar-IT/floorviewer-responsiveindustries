<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('filters')) {
            Schema::create('filters', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', '100')->nullable();
                $table->string('field', '32');
                $table->string('surface', '32')->nullable();
                $table->string('type', '32')->default('checkbox');
                $table->text('values')->nullable();
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
        Schema::dropIfExists('filters');
    }
}
