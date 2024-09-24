<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;

class PlayerDocumentController extends Controller
{
    public function getDocument($playerId, $documentType)
    {
        $player = Player::findOrFail($playerId);
        $document = $documentType === 'parental_consent' ? $player->parental_consent : $player->birth_certificate;
        $status = $documentType === 'parental_consent' ? $player->parental_consent_status : $player->birth_certificate_status;

        return response()->json([
            'document' => $document,
            'status' => $status,
        ]);
    }

    public function getComments($playerId, $documentType)
    {
        $player = Player::findOrFail($playerId);
        $comments = $player->getComments($documentType);
        
        $comments = collect($comments)->map(function ($comment) {
            $user = User::find($comment['user_id']);
            $comment['user_name'] = $user ? $user->name : 'Unknown User';
            return $comment;
        });

        return response()->json($comments);
    }

    public function addComment(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'document_type' => 'required|in:parental_consent,birth_certificate',
            'comment' => 'required|string',
        ]);

        $player = Player::findOrFail($request->player_id);
        $player->addComment($request->document_type, $request->comment, auth()->id());

        $newComment = [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'comment' => $request->comment,
            'created_at' => now()->toDateTimeString(),
        ];

        return response()->json($newComment);
    }
}