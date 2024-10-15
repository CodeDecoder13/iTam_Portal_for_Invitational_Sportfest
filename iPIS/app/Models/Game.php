<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sport_category', 'team1_id', 'team2_id', 'game_date', 'start_time', 'end_time','team1_score','team2_score'
    ];

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class); // Assuming you have a Comment model
    }
}
