<?php

// app/Http/Controllers/API/GoalController.php
namespace App\Http\Controllers\API;

use App\Models\Goal;
use App\Models\Matches;
use App\Models\Player;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    // Menampilkan semua gol
    public function index(Request $request)
    {
        try {
            $goals = Goal::with(['match', 'player'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Goals retrieved successfully',
                'data' => $goals,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve goals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan semua match
    public function getMatch()
    {
        try {
            $match = Matches::all(); // Mengambil semua data pemain
            return response()->json([
                'success' => true,
                'message' => 'Match retrieved successfully',
                'data' => $match,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve match',
                'error' => $e->getMessage(),
            ], 500); // Status code 500 untuk internal server error
        }
    }

    // Mengambil pemain berdasarkan match_id
    public function Getplayers($match_id)
    {
        try {
            // Cari match berdasarkan ID
            $match = Matches::findOrFail($match_id);

            // Ambil semua pemain dari home_team_id dan away_team_id
            $players = Player::where('team_id', $match->home_team_id)
                            ->orWhere('team_id', $match->away_team_id)
                            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Players retrieved successfully',
                'data' => $players,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve players',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menyimpan gol baru
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|exists:matches,id',
            'player_id' => 'required|exists:players,id',
            'goal_time' => 'required|date_format:Y-m-d H:i:s',
            'score' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Simpan gol
            $goal = Goal::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Goal created successfully',
                'data' => $goal,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create goal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan gol berdasarkan ID
    public function show($id)
    {
        try {
            $goal = Goal::with(['match', 'player'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Goal retrieved successfully',
                'data' => $goal,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Goal not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the goal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mengupdate gol
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'goal_time' => 'required|date_format:Y-m-d H:i:s',
            'score' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $goal = Goal::findOrFail($id);
            $goal->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Goal updated successfully',
                'data' => $goal,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update goal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus gol
    public function destroy($id)
    {
        try {
            $goal = Goal::findOrFail($id);
            $goal->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Goal deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Goal not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete goal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
