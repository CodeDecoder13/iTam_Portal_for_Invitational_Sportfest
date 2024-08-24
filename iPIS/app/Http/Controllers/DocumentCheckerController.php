<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentCheckerController extends Controller
{
    /*public function approveDocument($playerId, $document)
    {
        $player = Player::findOrFail($playerId);

        // Approving the document
        $player->update([
            $document => 'approved',
            'status' => 'Approved',
            'last_update' => now(),
        ]);

        return back()->with('status', ucfirst($document) . ' approved successfully!');
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

        return back()->with('status', ucfirst($document) . ' rejected.');
    }*/

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
            return back()->with('status', ucfirst($document) . ' deleted successfully!');
        }

        return back()->with('error', 'File not found.');
    }

    //Dwei:

    public function updateDocument($playerId, $document, $type, $update)
    {
        $player = Player::findOrFail($playerId);
        $filePath = 'public/' . $player->user->school_name . "/" . $player->team->sport_category . "/" . $player->team_id . "/" . $player->id . "/" . $document;
        $action = "";
        //will be used for the update of status
        $typeStatus = "";
        switch ($type) {
            case "Birth Certificate":
                $typeStatus = "birth_certificate_status";
                break;
            case "Parental Consent":
                $typeStatus = "parental_consent_status";
                break;
        }
        switch ($update) {
            case 0:
                $action = "deleted";
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                break;
            case 2:
                $action = "approved";
                break;
            case 3:
                $action = "rejected";
                break;
            case 4:
                if (Storage::exists($filePath)) {
                    return Storage::download($filePath);
                }
                return back()->with('error', 'File not found.');
                break;
        }

        $player->update([
            $typeStatus => $update,
            'last_update' => now(),
        ]);

        return back()->with('success', ucfirst($document) . ' ' . $action . ' successfully!');
    }
}
