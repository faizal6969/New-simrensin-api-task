<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\RecipeTrackerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->prefix('recipe')->group(function () {
    Route::get('getList', [RecipeTrackerController::class, 'recipeListTracker']);
    Route::post('getList', [RecipeTrackerController::class, 'recipeListTracker']);
    Route::post('saveData', [RecipeTrackerController::class, 'recipeTrackerStoreData']);
    Route::get('/show/{id}', [RecipeTrackerController::class, 'show']);
    Route::get('/edit/{id}', [RecipeTrackerController::class, 'edit']);
    Route::put('updateData', [RecipeTrackerController::class, 'recipeTrackerUpdateData']);
    Route::get('delete/{id}', [RecipeTrackerController::class, 'delete']);
    Route::get('/difficulty/{level}', [RecipeTrackerController::class, 'filterByDifficulty']);
    Route::post('logout', [AuthController::class, 'logout']);

});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



