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

        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->string('images');
            $table->unsignedBigInteger('publicTrip_id');
            $table->foreign('publicTrip_id')->references('id')->on('public_trips');
            $table->string('description');
            $table->bigInteger('discount-value');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};
