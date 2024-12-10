<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'logo', 'founded_year', 'address', 'city'];

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}