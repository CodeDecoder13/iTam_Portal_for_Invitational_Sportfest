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
        'birth_certificate_comments',
        'parental_consent_comments',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class,'team_id');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function addComment($documentType, $comment, $userId)
    {
        $commentsField = $documentType . '_comments';
        $comments = $this->$commentsField ?? [];
        $comments[] = [
            'user_id' => $userId,
            'comment' => $comment,
            'created_at' => now()->toDateTimeString(),
        ];
        $this->$commentsField = $comments;
        $this->save();
    }

    public function getComments($documentType)
    {
        $commentsField = $documentType . '_comments';
        return $this->$commentsField ?? [];
    }
}
