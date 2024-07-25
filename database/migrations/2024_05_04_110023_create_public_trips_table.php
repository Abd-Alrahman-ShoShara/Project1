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

        Schema::create('public_trips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->text('description');
            $table->unsignedBigInteger('citiesHotel_id');
            $table->foreign('citiesHotel_id')->references('id')->on('cities_hotels')->onDelete('cascade');
            $table->date('dateOfTrip');
            $table->date('dateEndOfTrip');
            $table->boolean('display')->default(false);
           // $table->bigInteger('discountType')->default(0);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_trips');
    }
};
