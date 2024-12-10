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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id'); // Relasi ke match
            $table->unsignedBigInteger('player_id'); // Relasi ke pemain
            $table->dateTime('goal_time'); // Waktu gol menggunakan dateTime
            $table->integer('score')->nullable(); // Kolom untuk cetak skor
            $table->timestamps();
            $table->softDeletes(); // Soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
