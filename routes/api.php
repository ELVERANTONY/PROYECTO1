<?php

use App\Http\Controllers\Api\ClaseEstudianteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para la gestión de estudiantes en clases
Route::get('/clase/{clase}/estudiantes', [ClaseEstudianteController::class, 'index'])
    ->name('api.clase.estudiantes.index');
