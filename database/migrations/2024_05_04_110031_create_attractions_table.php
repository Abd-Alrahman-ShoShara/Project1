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
            $table->string('image');
            $table->unsignedBigInteger('publicTrip_id');
            $table->foreign('publicTrip_id')->references('id')->on('public_trips')->onDelete('cascade');
            $table->string('description');
            $table->boolean('display')->default(0);
            $table->enum('type',['Discount On The Ticket','Points Discount','Special Event']);
            $table->bigInteger('discount')->default(0);
            $table->Integer('discount_points');
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
