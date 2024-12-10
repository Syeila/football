<?php

// app/Models/Result.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'match_id',
        'home_score',
        'away_score',
        'total_score',
        'winner_team_id'
    ];

    // Relasi ke Match
    public function match()
    {
        return $this->belongsTo(Matches::class);
    }

    // Relasi ke Tim Pemenang
    public function winnerTeam()
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }
}

