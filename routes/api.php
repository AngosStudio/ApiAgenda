<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalendarsController;
use App\Http\Controllers\Api\CalendarTypesController;
use App\Http\Controllers\Api\UsersController;
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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::apiResource('/calendar-types', CalendarTypesController::class);
    // Caso prefira declarar todas as rotas
    // Route::get('/calendars-types', [CalendarTypesController::class, 'index']);
    // Route::get('/calendars-types/{id:[0-9]+}', [CalendarTypesController::class, 'show']);
    // Route::patch('/calendars-types/{id:[0-9]+}', [CalendarTypesController::class, 'update']);
    // Route::post('/calendars-types', [CalendarTypesController::class, 'store']);
    // Route::delete('/calendars-types/{id:[0-9]+}', [CalendarTypesController::class, 'delete']);

    Route::apiResource('/calendars', CalendarsController::class);
    // Caso prefira declarar todas as rotas
    // Route::get('/calendars', [CalendarsController::class, 'index']);
    // Route::get('/calendars/{id:[0-9]+}', [CalendarsController::class, 'show']);
    // Route::patch('/calendars/{id:[0-9]+}', [CalendarsController::class, 'update']);
    // Route::post('/calendars', [CalendarsController::class, 'store']);
    // Route::delete('/calendars/{id:[0-9]+}', [CalendarsController::class, 'delete']);

    Route::apiResource('/users', UsersController::class);
    // Caso prefira declarar todas as rotas
    // Route::get('/users', [UsersController::class, 'index']);
    // Route::get('/users/{id:[0-9]+}', [UsersController::class, 'show']);
    // Route::patch('/users/{id:[0-9]+}', [UsersController::class, 'update']);
    // Route::post('/users', [UsersController::class, 'store']);
    // Route::delete('/users/{id:[0-9]+}', [UsersController::class, 'delete']);
});

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Bem vindo(a) à API Farmarcas!'
    ]);
});
