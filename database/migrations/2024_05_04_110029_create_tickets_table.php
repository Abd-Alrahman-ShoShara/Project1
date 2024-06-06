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

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airline_id');
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->unsignedBigInteger('airport_id1');
            $table->foreign('airport_id1')->references('id')->on('airports')->onDelete('cascade');
            $table->unsignedBigInteger('airport_id2');
            $table->foreign('airport_id2')->references('id')->on('airports')->onDelete('cascade');
            $table->enum('typeOfTicket',['Economy','PremiumEconomy','Business','FirstClass'])->default('Economy');
            $table->time('timeOfticket');
            $table->enum('roundOrOne_trip',['RoundTrip','OneWay'])->default('RoundTrip');
            $table->date('dateOfTicket');
            $table->date('dateEndOfTicket')->nullable();
            $table->string('duration');
            $table->float('price')->default(0);
            $table->bigInteger('numOfTickets');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
