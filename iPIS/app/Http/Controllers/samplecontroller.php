<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use App\Models\Player;
use App\Models\Standing;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class Samplecontroller extends Controller

    public function createSchoolFolder($schoolName)
    {
        $schoolFolderPath = "public/schools/" . Str::slug($schoolName);
        
        if (!Storage::exists($schoolFolderPath)) {
            Storage::makeDirectory($schoolFolderPath);
        }

        return $schoolFolderPath;
    }

    public function createSportCategoryFolder($sportCategory, $schoolName)
    {
        $schoolSlug = Str::slug($schoolName);
        $sportSlug = Str::slug($sportCategory);
        $sportFolderPath = "public/schools/{$schoolSlug}/sports/{$sportSlug}";
        
        if (!Storage::exists($sportFolderPath)) {
            Storage::makeDirectory($sportFolderPath);
        }

        return $sportFolderPath;
    }

    // Example usage in storing a new school
    public function storeSchool(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'school_name' => 'required|string|max:255',
                'school_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Create school folder
            $schoolFolderPath = $this->createSchoolFolder($request->school_name);

            // Handle logo upload
            if ($request->hasFile('school_logo')) {
                $file = $request->file('school_logo');
                $fileName = 'logo.' . $file->getClientOriginalExtension();
                $logoPath = Storage::putFileAs($schoolFolderPath, $file, $fileName);
                
                // Store school info in database...
            }

            return response()->json(['success' => true, 'message' => 'School added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Example usage in storing a new sport category
    public function storeSportCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sport_name' => 'required|string|max:255',
                'school_name' => 'required|string|exists:schools,name',
                'sport_icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Create sport category folder
            $sportFolderPath = $this->createSportCategoryFolder(
                $request->sport_name,
                $request->school_name
            );

            // Handle icon upload
            if ($request->hasFile('sport_icon')) {
                $file = $request->file('sport_icon');
                $fileName = 'icon.' . $file->getClientOriginalExtension();
                $iconPath = Storage::putFileAs($sportFolderPath, $file, $fileName);
                
                // Store sport category info in database...
            }

            return response()->json(['success' => true, 'message' => 'Sport category added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}