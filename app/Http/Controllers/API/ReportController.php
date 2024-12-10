<?php

namespace App\Http\Controllers\API;

use App\Models\Matches;
use App\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function matchReport()
    {
        try {
            // Ambil semua pertandingan beserta relasi tim home, away, hasil, dan gol
            $matches = Matches::with(['homeTeam', 'awayTeam', 'result', 'goals.player'])->get();

            // Proses data pertandingan untuk laporan
            $report = $matches->map(function ($match) {
                // Ambil skor home dan away dari hasil pertandingan
                $homeScore = $match->result->home_score ?? 0;
                $awayScore = $match->result->away_score ?? 0;

                // Tentukan status akhir pertandingan berdasarkan winner_team_id
                $status = 'Draw'; // Default adalah Draw
                if ($match->result->winner_team_id == $match->homeTeam->id) {
                    $status = 'Tim Home Menang';
                } elseif ($match->result->winner_team_id == $match->awayTeam->id) {
                    $status = 'Tim Away Menang';
                }

                // Kelompokkan pemain berdasarkan jumlah gol mereka dan jumlahkan skor
                $playersWithGoals = $match->goals->groupBy('player_id')->map(function ($goals) {
                    // Hitung total gol dengan menjumlahkan nilai score
                    $totalGoals = $goals->sum('score'); // Jumlahkan score untuk setiap gol pemain
                    return [
                        'player_id' => $goals->first()->player_id,
                        'player_name' => $goals->first()->player->name,
                        'goals' => $totalGoals, // Total gol berdasarkan score
                    ];
                });

                // Ambil pencetak gol terbanyak
                $topScorer = $playersWithGoals->sortByDesc('goals')->first();

                return [
                    'tanggal_pertandingan' => $match->match_date,
                    'waktu_pertandingan' => $match->match_time,
                    'tim_home' => $match->homeTeam->name,
                    'tim_away' => $match->awayTeam->name,
                    'akhir' => "{$homeScore} - {$awayScore}",
                    'status_akhir_pertandingan' => $status,
                    'pencetak_gol_terbanyak' => $topScorer ? $topScorer['player_name'] . " ({$topScorer['goals']} gol)" : 'Tidak ada gol',
                ];
            });

            // Kembalikan hasil dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'Match report retrieved successfully',
                'data' => $report,
            ], 200);
        } catch (\Exception $e) {
            // Tangani error dan kembalikan respons gagal
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve match report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
