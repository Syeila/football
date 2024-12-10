<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'height', 'weight', 'position', 'shirt_number', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
