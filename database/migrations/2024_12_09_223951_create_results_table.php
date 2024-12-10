<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_results_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');  // Relasi ke matches
            $table->integer('home_score');
            $table->integer('away_score');
            $table->integer('total_score')->nullable(); // Kolom untuk total skor
            $table->foreignId('winner_team_id')->nullable()->constrained('teams'); // Kolom pemenang
            $table->softDeletes();  // Soft delete
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
};
