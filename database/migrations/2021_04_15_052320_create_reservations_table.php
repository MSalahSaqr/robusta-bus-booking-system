<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_id')->constrained();
            $table->unsignedBigInteger('from_station');
            $table->foreign('from_station')->references('id')->on('stations');
            $table->unsignedBigInteger('to_station');
            $table->foreign('to_station')->references('id')->on('stations');
            $table->foreignId('trip_id')->constrained();

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
        Schema::dropIfExists('reservations');
    }
}
