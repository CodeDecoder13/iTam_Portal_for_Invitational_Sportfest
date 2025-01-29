<?php

namespace App\Http\Controllers;
use App\Models\Standing;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StandingController extends Controller
{
    

    public function store(Request $request)
    {
        $request->validate([
            'sport_category' => 'required',
            'team_id' => 'required|exists:teams,id',
            'wins' => 'required|integer|min:0'
        ]);

        Standing::updateOrCreate(
            [
                'team_id' => $request->team_id,
                'sport_category' => $request->sport_category
            ],
            ['wins' => $request->wins]
        );

        return redirect()->back()->with('success', 'Standing updated successfully');
    }
    public function edit($id)
    {
        try {
            $standing = Standing::with(['team.coach'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'standing' => $standing
            ]);
        } catch (\Exception $e) {
            \Log::error('Edit standing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching standing'
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $standing = Standing::findOrFail($id);
            $standing->update([
                'wins' => $request->wins
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Standing updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Update standing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating standing'
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $standing = Standing::findOrFail($id);
            $standing->delete();
            return response()->json([
                'success' => true,
                'message' => 'Standing deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete standing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting standing'
            ], 500);
        }
    }
    public function getSchoolsByCategory(Request $request)
    {
        try {
            \Log::info('Received request for sport category: ' . $request->sport_category);
            
            $teams = Team::with('coach')
                ->where('sport_category', $request->sport_category)
                ->get()
                ->map(function ($team) {
                    return [
                        'id' => $team->id,
                        'school_name' => $team->coach->school_name . ' - ' . $team->name
                    ];
                });
                
            \Log::info('Found teams: ' . $teams->count());
            return response()->json($teams);
        } catch (\Exception $e) {
            \Log::error('Error in getSchoolsByCategory: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
