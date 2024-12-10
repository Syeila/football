<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\PlayerController;
use App\Http\Controllers\API\MatchController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\API\ReportController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    // Teams Route
    Route::get('teams', [TeamController::class, 'index']);
    Route::post('teams', [TeamController::class, 'store']);
    Route::get('teams/{id}', [TeamController::class, 'show']);
    Route::post('teams/{id}', [TeamController::class, 'update']);
    Route::delete('teams/{id}', [TeamController::class, 'destroy']);

    // Players Route
    Route::get('players', [PlayerController::class, 'index']);
    Route::get('players/getTeam', [PlayerController::class, 'getTeam']);
    Route::post('players', [PlayerController::class, 'store']);
    Route::get('players/{id}', [PlayerController::class, 'show']);
    Route::post('players/{id}', [PlayerController::class, 'update']);
    Route::delete('players/{id}', [PlayerController::class, 'destroy']);

     // Matches Route
     Route::get('matches', [MatchController::class, 'index']);
     Route::get('matches/getTeam', [MatchController::class, 'getTeam']);
     Route::post('matches', [MatchController::class, 'store']);
     Route::get('matches/{id}', [MatchController::class, 'show']);
     Route::post('matches/{id}', [MatchController::class, 'update']);
     Route::delete('matches/{id}', [MatchController::class, 'destroy']);

      // Results Route
      Route::get('results', [ResultController::class, 'index']);
      Route::get('results/getMatch', [ResultController::class, 'getMatch']);
      Route::post('results', [ResultController::class, 'store']);
      Route::get('results/{id}', [ResultController::class, 'show']);
      Route::post('results/{id}', [ResultController::class, 'update']);
      Route::delete('results/{id}', [ResultController::class, 'destroy']);

      // Goals Route
      Route::get('goals', [GoalController::class, 'index']);
      Route::get('goals/getMatch', [GoalController::class, 'getMatch']);
      Route::get('goals/{match_id}', [GoalController::class, 'getPlayers']);
      Route::post('goals', [GoalController::class, 'store']);
      Route::get('goals/{id}', [GoalController::class, 'show']);
      Route::post('goals/{id}', [GoalController::class, 'update']);
      Route::delete('goals/{id}', [GoalController::class, 'destroy']);

      // Match Report Route
      Route::get('match-report', [ReportController::class, 'matchReport']);

});

