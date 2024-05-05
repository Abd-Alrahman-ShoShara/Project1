<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('room_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('citiesHotel_id');
            $table->foreign('citiesHotel_id')->references('id')->on('cities_hotels');
            $table->enum('typeOfRoom',['SingleRoom','DeluxeRoom','SuiteRoom']);
            $table->string('description');
            $table->bigInteger('numberOfRoom');
            $table->bigInteger('price');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_hotels');
    }
};
