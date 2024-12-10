<?php

namespace App\Http\Controllers\API;

use App\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    // Menampilkan semua tim
    public function index()
    {
        try {
            $teams = Team::all(); // Mengambil semua data tim
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

    // Menambah tim baru
    public function store(Request $request)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'founded_year' => 'required|integer|digits:4',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422); // Status code 422 untuk validasi error
        }

        try {
            $data = $request->all();

            // Upload logo jika ada
            if ($request->hasFile('logo')) {
                $originalName = $request->file('logo')->getClientOriginalName();
                $path = $request->file('logo')->storeAs('logos', $originalName, 'public');
                $data['logo'] = $path;
            }

            $team = Team::create($data);

            return response()->json([
                'message' => 'Team created successfully',
                'team' => $team,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create team',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan tim berdasarkan ID
    public function show($id)
    {
        try {
            $team = Team::findOrFail($id);

            return response()->json([
                'message' => 'Team retrieved successfully',
                'team' => $team,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Team not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the team',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mengubah data tim
    public function update(Request $request, $id)
    {
        // Aturan validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'founded_year' => 'required|integer|digits:4',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422); // Status code 422 untuk validasi error
        }

        try {
            $team = Team::findOrFail($id);
            $data = $request->all();

            // Upload logo baru jika ada
            if ($request->hasFile('logo')) {
                // Hapus logo lama jika ada
                if ($team->logo && \Storage::exists('public/' . $team->logo)) {
                    \Storage::delete('public/' . $team->logo);
                }

                $originalName = $request->file('logo')->getClientOriginalName();
                $path = $request->file('logo')->storeAs('logos', $originalName, 'public');
                $data['logo'] = $path;
            }

            // Update tim dengan data baru
            $team->update($data);

            return response()->json([
                'message' => 'Team updated successfully',
                'team' => $team,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update team',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus tim
    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Team soft-deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete team',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}


