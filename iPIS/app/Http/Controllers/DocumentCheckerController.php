<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentCheckerController extends Controller
{
    public function approveDocument($playerId, $document)
    {
        $player = Player::findOrFail($playerId);

        // Approving the document
        $player->update([
            $document => 'approved',
            'status' => 'Approved',
            'last_update' => now(),
        ]);

        return back()->with('status', ucfirst($document).' approved successfully!');
    }

    public function rejectDocument($playerId, $document)
    {
        $player = Player::findOrFail($playerId);

        // Rejecting the document
        $player->update([
            $document => 'rejected',
            'status' => 'Rejected',
            'last_update' => now(),
        ]);

        return back()->with('status', ucfirst($document).' rejected.');
    }

    public function downloadDocument($playerId, $document)
    {
        $player = Player::findOrFail($playerId);
        $filePath = $player->$document;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        }

        return back()->with('error', 'File not found.');
    }

    public function deleteDocument($playerId, $document)
    {
        $player = Player::findOrFail($playerId);
        $filePath = $player->$document;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            $player->update([
                $document => null,
                'status' => 'No File Attached',
                'last_update' => now(),
            ]);
            return back()->with('status', ucfirst($document).' deleted successfully!');
        }

        return back()->with('error', 'File not found.');
    }
}
