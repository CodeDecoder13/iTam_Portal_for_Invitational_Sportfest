<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'admin_id',
        'content',
    ];

    /**
     * Get the admin that owns the comment.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class); 
    }

    /**
     * Get the game that owns the comment.
     */
    public function game()
    {
        return $this->belongsTo(Game::class); 
    }
    
}