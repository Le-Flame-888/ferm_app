<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EquipementController;
use App\Http\Controllers\Api\TechnicienController;
use App\Http\Controllers\Api\FournisseurController;
use App\Http\Controllers\Api\InterventionController;
use App\Http\Controllers\Api\PaiementController;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Handle preflight OPTIONS requests
Route::options('{any}', function () {
    return response('', Response::HTTP_NO_CONTENT)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
})->where('any', '.*');

// Public routes (if any)
Route::group([], function () {
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json(['status' => 'ok']);
    });
});

// Protected API routes with rate limiting and Sanctum authentication
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Equipement Routes
    Route::apiResource('equipements', EquipementController::class);

    // Technicien Routes
    Route::apiResource('techniciens', TechnicienController::class);

    // Fournisseur Routes
    Route::apiResource('fournisseurs', FournisseurController::class);

    // Intervention Routes
    Route::apiResource('interventions', InterventionController::class);
    Route::post('interventions/{intervention}/payments', [InterventionController::class, 'addPayment']);

    // Paiement Routes
    Route::apiResource('paiements', PaiementController::class);
});
