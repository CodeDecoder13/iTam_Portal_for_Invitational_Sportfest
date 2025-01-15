<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'sport_category',
        'wins',
        'losses',
        'points',
        'rank'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
