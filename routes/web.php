<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::get('/', function () {
    return "NOTHING HERE";
});

Route::get('/auth/login', [PageController::class, 'auth'])->name('login');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/atlet', [PageController::class, 'atlet'])->name('atlet');
Route::get('/coach', [PageController::class, 'coach'])->name('coach');
Route::get('/official', [PageController::class, 'official'])->name('official');
Route::get('/venue', [PageController::class, 'venue'])->name('venue');
Route::get('/penginapan', [PageController::class, 'penginapan'])->name('penginapan');
Route::get('/master/cabor', [PageController::class, 'master_cabor'])->name('master_cabor');
Route::get('/master/kelas-cabor', [PageController::class, 'master_kelas_cabor'])->name('master_kelas_cabor');
Route::middleware('checkApiToken')->group(function () {
});

