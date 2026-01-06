<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionDapController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

//Rutas de Secciones DAP
Route::get('/secciones', [SeccionDapController::class, 'index']);
Route::post('/secciones', [SeccionDapController::class, 'store']);

//Rutas de Loging
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController:: class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});
