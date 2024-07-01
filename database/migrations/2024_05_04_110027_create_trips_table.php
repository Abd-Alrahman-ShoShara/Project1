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

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('from');
            $table->foreign('from')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('to');
            $table->foreign('to')->references('id')->on('cities')->onDelete('cascade');
            $table->date('dateOfTrip');
            $table->date('dateEndOfTrip');
            $table->bigInteger('numOfPersons');
            $table->enum('state',['UnderConstruction','completed','cancelled'])->default('UnderConstruction');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
