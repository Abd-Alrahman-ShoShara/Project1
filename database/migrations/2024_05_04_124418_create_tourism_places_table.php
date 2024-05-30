<?php

use App\Models\City;
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
        Schema::create('tourism_places', function (Blueprint $table) {
            $table->id();
            $table->string('images')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('openingHours');
            $table->string('recommendedTime')->nullable();
            $table->enum('type',['Sports','Entertainment','Culitural','Natural','Relaxation','Restaurants','Historical','Shopping'])->nullable();
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_places');
    }
};
