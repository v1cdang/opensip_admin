<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DawzCDRs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DawzCDRs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dialed_number');
            $table->string('callerid');
            $table->string('duration');
            $table->string('calldate');
            $table->string('rate');
            $table->string('total_cost');
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
        //
    }
}
