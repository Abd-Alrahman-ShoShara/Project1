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

        Schema::create('booking_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roomHotel_id');
            $table->foreign('roomHotel_id')->references('id')->on('room_hotels')->onDelete('cascade');
            $table->unsignedBigInteger('trip_id');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->double('price')->default(0);
            $table->bigInteger('numberOfRoom');
            $table->date('checkIn');
            $table->date('checkOut');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_hotels');
    }
};
