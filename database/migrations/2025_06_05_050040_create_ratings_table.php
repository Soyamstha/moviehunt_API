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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('movie_id');
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade')->onUpdate('cascade');
            // $table->unsignedBigInteger('profile_id');
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('rating');
            $table->text('review')->nullable();
            $table->dateTime('rated_at')->nullable(); // Date and time when the rating was given
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
