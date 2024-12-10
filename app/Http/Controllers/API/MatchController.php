<?php

namespace App\Http\Controllers\API;

use App\Models\Matches;
use App\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    // Menampilkan semua pertandingan
    public function index()
    {
        try {
            $Matches = Matches::with(['homeTeam', 'awayTeam', 'result'])->get();
            return response()->json([
                'success' => true,
                'message' => 'Matches retrieved successfully',
                'data' => $Matches,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Matches',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan semua team
    public function getTeam()
    {
        try {
            $teams = Team::all(); // Mengambil semua data pemain
            return response()->json([
                'success' => true,
                'message' => 'Teams retrieved successfully',
                'data' => $teams,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve teams',
                'error' => $e->getMessage(),
            ], 500); // Status code 500 untuk internal server error
        }
    }

    // Menambahkan pertandingan baru
    public function store(Request $request)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'match_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $Matches = Matches::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Matches created successfully',
                'data' => $Matches,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Matches',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan pertandingan berdasarkan ID
    public function show($id)
    {
        try {
            $Matches = Matches::with(['homeTeam', 'awayTeam', 'result', 'goals'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Matches retrieved successfully',
                'data' => $Matches,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Matches not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the Matches',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mengubah data pertandingan
    public function update(Request $request, $id)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'match_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $Matches = Matches::findOrFail($id);
            $Matches->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Matches updated successfully',
                'data' => $Matches,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Matches not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Matches',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus pertandingan
    public function destroy($id)
    {
        try {
            $Matches = Matches::findOrFail($id);
            $Matches->delete();

            return response()->json([
                'success' => true,
                'message' => 'Matches deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Matches not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Matches',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
