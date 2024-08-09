<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $dates = ['birthday']; 
    protected $fillable = ['team_id', 'first_name', 'middle_name', 'last_name', 'birthday', 'gender','jersey_no','status'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
