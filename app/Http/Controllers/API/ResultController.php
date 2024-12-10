<?php

// app/Http/Controllers/API/ResultController.php
namespace App\Http\Controllers\API;

use App\Models\Result;
use App\Models\Matches;
use App\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    // Menampilkan semua hasil pertandingan
    public function index()
    {
        try {
            $results = Result::with(['match', 'match.homeTeam', 'match.awayTeam', 'winnerTeam'])->get();

            return response()->json([
                'success' => true,
                'message' => 'All results retrieved successfully',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve results',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan semua match
    public function getMatch()
    {
        try {
            $matches = Matches::all(); // Mengambil semua data match
            return response()->json([
                'success' => true,
                'message' => 'Matches retrieved successfully',
                'data' => $matches,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve matches',
                'error' => $e->getMessage(),
            ], 500); // Status code 500 untuk internal server error
        }
    }

    // Menyimpan hasil pertandingan baru
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|exists:matches,id',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Cari match terkait berdasarkan match_id
            $match = Matches::findOrFail($request->match_id);

            // Tentukan pemenang berdasarkan skor
            $winnerTeamId = null;
            if ($request->home_score > $request->away_score) {
                $winnerTeamId = $match->home_team_id;
            } elseif ($request->away_score > $request->home_score) {
                $winnerTeamId = $match->away_team_id;
            }

            // Hitung total skor
            $totalScore = $request->home_score + $request->away_score;

            // Buat result baru
            $result = Result::create([
                'match_id' => $request->match_id,
                'home_score' => $request->home_score,
                'away_score' => $request->away_score,
                'total_score' => $totalScore,
                'winner_team_id' => $winnerTeamId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Result created successfully',
                'data' => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create result',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan hasil berdasarkan ID
    public function show($id)
    {
        try {
            $result = Result::with(['match', 'match.homeTeam', 'match.awayTeam', 'winnerTeam'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Result retrieved successfully',
                'data' => $result,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Result not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the result',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mengupdate hasil pertandingan
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = Result::findOrFail($id);

            // Cari match terkait berdasarkan match_id
            $match = Matches::findOrFail($result->match_id);

            // Tentukan pemenang berdasarkan skor baru
            $winnerTeamId = null;
            if ($request->home_score > $request->away_score) {
                $winnerTeamId = $match->home_team_id;
            } elseif ($request->away_score > $request->home_score) {
                $winnerTeamId = $match->away_team_id;
            }

            // Hitung total skor
            $totalScore = $request->home_score + $request->away_score;

            // Update data result
            $result->update([
                'home_score' => $request->home_score,
                'away_score' => $request->away_score,
                'total_score' => $totalScore,
                'winner_team_id' => $winnerTeamId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Result updated successfully',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update result',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus hasil pertandingan
    public function destroy($id)
    {
        try {
            $result = Result::findOrFail($id);
            $result->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Result deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Result not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete result',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
