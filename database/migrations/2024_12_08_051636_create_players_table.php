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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('height');
            $table->float('weight');
            $table->enum('position', ['penyerang', 'gelandang', 'bertahan', 'penjaga gawang']);
            $table->integer('shirt_number');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->softDeletes(); // Menambahkan soft delete
            $table->timestamps();
            $table->unique(['team_id', 'shirt_number']); // Pastikan nomor punggung unik per tim
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
