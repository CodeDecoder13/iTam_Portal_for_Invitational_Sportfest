<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function documents()
    {
        // Return view for documents page
        return view('admin.documents');
    }

    public function summaryOfPlayers(Request $request)
    {
        // Handle search and filtering
        $query = Player::with(['user', 'team']);
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->sport) {
            $query->whereHas('team', function($q) use ($request) {
                $q->where('sport_category', $request->sport);
            });
        }
        
        if ($request->team) {
            $query->where('team_id', $request->team);
        }
        
        if ($request->status) {
            // Add status filtering logic based on your requirements
        }
        
        $players = $query->get();
        
        return view('admin.admin-sidebar.team-documents.SummaryOfPlayers_suggested', compact('players'));
    }

    public function updateDocument($playerId, $fileName, $docType, $status)
    {
        $player = Player::findOrFail($playerId);
        
        // Update document status based on docType
        if ($docType == 'Birth Certificate') {
            $player->birth_certificate_status = $status;
        } else if ($docType == 'Parental Consent') {
            $player->parental_consent_status = $status;
        }
        
        $player->save();
        
        // If status is 4, trigger download
        if ($status == 4) {
            // Handle document download
            $schoolName = strtolower(str_replace([' ', '.'], '-', $player->user->school_name));
            $sportCategory = strtolower(str_replace([' ', '.'], '-', $player->team->sport_category));
            $path = "teams/{$schoolName}/{$sportCategory}/{$player->team_id}/players/{$player->id}/{$fileName}";
            
            return Storage::download($path);
        }
        
        return redirect()->back()->with('success', 'Document status updated successfully');
    }

    public function deleteDocument($playerId, $fileName, $docType, $status)
    {
        $player = Player::findOrFail($playerId);
        
        // Delete the file and update status
        if ($docType == 'Birth Certificate') {
            Storage::delete($player->birth_certificate);
            $player->birth_certificate = null;
            $player->birth_certificate_status = 0;
        } else if ($docType == 'Parental Consent') {
            Storage::delete($player->parental_consent);
            $player->parental_consent = null;
            $player->parental_consent_status = 0;
        }
        
        $player->save();
        
        return redirect()->back()->with('success', 'Document deleted successfully');
    }
} 