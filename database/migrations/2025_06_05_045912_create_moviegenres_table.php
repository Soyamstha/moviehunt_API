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
        Schema::create('moviegenres', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('movie_id');
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade')->onUpdate('cascade');
            // $table->unsignedBigInteger('genre_id');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moviegenres');
    }
};
