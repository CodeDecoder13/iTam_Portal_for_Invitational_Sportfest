<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentActionController extends Controller
{
    public function approve($playerId, $documentType)
    {
        try {
            $player = Player::findOrFail($playerId);
            $statusField = $this->getStatusField($documentType);
            
            $player->update([
                $statusField => 2 // 2 represents approved status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($playerId, $documentType)
    {
        try {
            $player = Player::with(['user', 'team'])->findOrFail($playerId);
            $statusField = $this->getStatusField($documentType);
            $commentField = $this->getCommentField($documentType);
            
            // Get the comment from the request
            $comment = request()->input('comment');
            
            // Update the status
            $player->update([
                $statusField => 3 // 3 represents rejected status
            ]);

            // Add the comment
            $comments = $player->$commentField ?? [];
            $comments[] = [
                'user_id' => auth()->id(),
                'comment' => $comment,
                'created_at' => now()->toDateTimeString(),
            ];
            
            $player->update([
                $commentField => $comments
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document rejected successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in reject method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($playerId, $documentType)
    {
        try {
            $player = Player::with(['user', 'team'])->findOrFail($playerId);
            $documentField = $this->getDocumentField($documentType);
            $statusField = $this->getStatusField($documentType);
            
            // Get the file path using the centralized structure
            $filePath = 'public/teams/' . 
                Str::slug($player->user->school_name) . '/' . 
                Str::slug($player->team->sport_category) . '/' . 
                $player->team_id . '/players/' . 
                $player->id . '/' . 
                $player->$documentField;

            \Log::info('Attempting to delete file: ' . $filePath); // Add logging

            // Delete the file if it exists
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
                \Log::info('File deleted successfully');
            } else {
                \Log::warning('File not found: ' . $filePath);
            }

            // Update the database
            $player->update([
                $documentField => null,
                $statusField => 0 // 0 represents no file status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in delete method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting document: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStatusField($documentType)
    {
        // Convert "Birth Certificate" to "birth_certificate_status"
        $field = Str::snake(str_replace('-', ' ', $documentType)) . '_status';
        return $field;
    }

    private function getDocumentField($documentType)
    {
        // Convert "Birth Certificate" to "birth_certificate"
        $field = Str::snake(str_replace('-', ' ', $documentType));
        return $field;
    }

    private function getCommentField($documentType)
    {
        // Convert "Birth Certificate" to "birth_certificate_comments"
        return Str::snake(str_replace('-', ' ', $documentType)) . '_comments';
    }
} 