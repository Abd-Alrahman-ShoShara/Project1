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
        Schema::create('trip_day_places', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tripDay_id');
            $table->unsignedBigInteger('tourismPlace_id');
            $table->foreign('tripDay_id')->references('id')->on('trip_days')->onDelete('cascade');
            $table->foreign('tourismPlace_id')->references('id')->on('tourism_places')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_day_places');
    }
};
