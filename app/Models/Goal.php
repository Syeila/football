<?php

// app/Models/Goal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'match_id',
        'player_id',
        'goal_time',
        'score',
    ];

    // Relasi ke match
    public function match()
    {
        return $this->belongsTo(Matches::class);
    }

    // Relasi ke pemain
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
    
}
