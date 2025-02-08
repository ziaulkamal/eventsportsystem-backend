<?php

use App\Http\Controllers\API\AthleteController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\HousingController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\KemendagriController;
use App\Http\Controllers\API\PeopleHousingController;
use App\Http\Controllers\API\PersonController;
use App\Http\Controllers\API\PlayerMatchController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\SportClassController;
use App\Http\Controllers\API\SportController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VenueController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CoachController;



// Routes tanpa autentikasi (akses umum)
Route::get('/auth/token', [Controller::class, 'getAccessToken']);
Route::get('/fetch-people/{nik}', [Controller::class, 'getIdentityPeople']);
Route::get('/fetch-people-with-attribute', [Controller::class, 'getIdentityPeopleByAttributes']);

Route::middleware('auth:sanctum')->get('/protected-endpoint', function (Request $request) {
    return response()->json(['message' => 'Authenticated']);
});

Route::get('athlete-complete', [PersonController::class, 'athlete']);
Route::get('coach-complete', [PersonController::class, 'coach']);
Route::get('athelete-people/{id}', [AthleteController::class, 'getpeople']);
Route::get('coach-people/{id}', [CoachController::class, 'getpeople']);
// Rute login (tidak perlu autentikasi untuk login)
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('people', PersonController::class);
Route::apiResource('documents', DocumentController::class);
Route::apiResource('coaches', CoachController::class);
Route::patch('documents-patch/{id}', [DocumentController::class, 'doPatch']);
// Routes yang dilindungi autentikasi (auth:sanctum middleware)
Route::middleware(['auth:sanctum'])->group(function () {
    // Logout route (membutuhkan autentikasi)
    Route::post('/logout', [AuthController::class, 'logout']);

    // API protected routes that require authentication
    Route::apiResource('sport-classes', SportClassController::class);
    Route::apiResource('sports', SportController::class);

    // Rute-rute khusus
    Route::get('people/find-by-nik/{nik}', [PersonController::class, 'findByNIK']);
    Route::apiResource('athletes', AthleteController::class);
    Route::apiResource('images', ImageController::class);
    Route::apiResource('venues', VenueController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('player-matches', PlayerMatchController::class);
    Route::apiResource('housing', HousingController::class);
    Route::apiResource('people-housing', PeopleHousingController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('attendance', AttendanceController::class);
});

// Routes lainnya (akses umum)
Route::get('/provinces', [KemendagriController::class, 'getProvinces']);
Route::get('/regencies/{provinceId}', [KemendagriController::class, 'getRegencies']);
Route::get('/districts/{regencyId}', [KemendagriController::class, 'getDistricts']);
Route::get('/villages/{districtId}', [KemendagriController::class, 'getVillages']);
Route::get('/kontingen', [KemendagriController::class, 'getKontingen']);
