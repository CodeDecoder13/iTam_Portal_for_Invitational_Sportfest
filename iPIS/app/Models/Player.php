<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'coach_id',
        'first_name',
        'middle_name',
        'last_name',
        'birthday',
        'gender',
        'jersey_no',
        'birth_certificate',
        'birth_certificate_status',
        'parental_consent',
        'parental_consent_status',
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class,'team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
