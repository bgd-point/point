<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_item', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("code")->nullable();
            $table->string('chart_of_account')->nullable();
            $table->string("unit_of_measurement_small")->nullable();
            $table->string("unit_of_measurement_big")->nullable();
            $table->string("unit_converter")->nullable();
            $table->date("expired_date")->nullable();
            $table->string("production_number")->nullable();
            $table->string('group')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_item');
    }
}
