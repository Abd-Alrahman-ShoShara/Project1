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
            $table->unsignedBigInteger('cititesHotel_id');
            $table->foreign('cititesHotel_id')->references('id')->on('cities_hotels')->onDelete('cascade');
            $table->date('dateOfTripe');
            $table->date('dateEndOfTripe');
            $table->bigInteger('discountType');
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
