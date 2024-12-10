<?php

namespace App\Http\Controllers\API;

use App\Models\Player;
use App\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    // Menampilkan semua pemain
    public function index()
    {
        try {
            $players = Player::with('team')->get(); // Mengambil semua data pemain
            return response()->json([
                'success' => true,
                'message' => 'Players retrieved successfully',
                'data' => $players,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve players',
                'error' => $e->getMessage(),
            ], 500); // Status code 500 untuk internal server error
        }
    }

    // Menampilkan semua Team
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

    // Menambah pemain baru
    public function store(Request $request)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'position' => 'required|in:penyerang,gelandang,bertahan,penjaga gawang',
            'shirt_number' => 'required|integer|unique:players,shirt_number,NULL,id,team_id,' . $request->team_id,
            'team_id' => 'required|exists:teams,id',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422); // Status code 422 untuk validasi error
        }

        try {
            $player = Player::create($request->all());

            return response()->json([
                'message' => 'Player created successfully',
                'player' => $player,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create player',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan pemain berdasarkan ID
    public function show($id)
    {
        try {
            $player = Player::findOrFail($id);
            return response()->json([
                'message' => 'Player retrieved successfully',
                'player' => $player,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Player not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the player',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mengubah data pemain
    public function update(Request $request, $id)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'position' => 'required|in:penyerang,gelandang,bertahan,penjaga gawang',
            'shirt_number' => 'required|integer|unique:players,shirt_number,' . $id . ',id,team_id,' . $request->team_id,
            'team_id' => 'required|exists:teams,id',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422); // Status code 422 untuk validasi error
        }

        try {
            $player = Player::findOrFail($id);
            $player->update($request->all());

            return response()->json([
                'message' => 'Player updated successfully',
                'player' => $player,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update player',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus pemain secara soft delete
    public function destroy($id)
    {
        try {
            $player = Player::findOrFail($id);
            $player->delete(); // Soft delete

            return response()->json([
                'message' => 'Player soft-deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete player',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}


