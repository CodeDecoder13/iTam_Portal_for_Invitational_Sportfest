<?php

namespace App\Http\Controllers;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DocumentManagementController extends Controller
{
    public function getDocument(Request $request)
    {
        $playerId = $request->player_id;
        $docType = $request->doc_type;

        $player = Player::with(['user', 'team'])->find($playerId);

        if (!$player) {
            return response()->json(['error' => 'Player not found'], 404);
        }

        $data = [
            'player_name' => "{$player->first_name} {$player->last_name}",
            'school_name' => $player->user->school_name,
            'team' => $player->team->sport_category,
        ];

        // Build the file path based on the centralized structure
        $basePath = 'storage/teams/' . 
            Str::slug($player->user->school_name) . '/' . 
            Str::slug($player->team->sport_category) . '/' . 
            $player->team_id . '/players/' . 
            $player->id . '/';

        if ($docType === 'Parental Consent') {
            $data['document'] = $player->parental_consent;
            $data['status'] = $this->getStatusText($player->parental_consent_status);
            $data['view_url'] = $player->parental_consent ? asset($basePath . $player->parental_consent) : null;
        } elseif ($docType === 'Birth Certificate') {
            $data['document'] = $player->birth_certificate;
            $data['status'] = $this->getStatusText($player->birth_certificate_status);
            $data['view_url'] = $player->birth_certificate ? asset($basePath . $player->birth_certificate) : null;
        } else {
            return response()->json(['error' => 'Invalid document type'], 400);
        }

        return response()->json($data);
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 1:
                return 'For Review';
            case 2:
                return 'Approved';
            case 3:
                return 'Rejected';
            default:
                return 'No File Attached';
        }
    }

    public function getComments($playerId, $documentType)
    {
        try {
            $player = Player::with(['user', 'team'])->findOrFail($playerId);
            
            // Convert kebab-case to snake_case for field name
            $documentType = str_replace('-', '_', $documentType);
            $commentField = $documentType . '_comments';
            
            // Validate the comment field exists
            if (!in_array($commentField, ['birth_certificate_comments', 'parental_consent_comments'])) {
                throw new \Exception('Invalid document type');
            }
            
            $comments = $player->$commentField ?? [];
            
            // Get user names for each comment
            $comments = collect($comments)->map(function ($comment) {
                $user = \App\Models\Admin::find($comment['user_id']) ?? \App\Models\User::find($comment['user_id']);
                $comment['user_name'] = $user ? ($user->first_name ?? $user->name) . ' ' . ($user->last_name ?? '') : 'Unknown User';
                return $comment;
            })->sortByDesc('created_at')->values();

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting comments: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCommentField($documentType)
    {
        $field = str_replace('-', '_', $documentType) . '_comments';
        if (!in_array($field, ['birth_certificate_comments', 'parental_consent_comments'])) {
            throw new \Exception('Invalid document type');
        }
        return $field;
    }
}
