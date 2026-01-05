<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionDapController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

//Rutas de Secciones DAP
Route::get('/secciones', [SeccionDapController::class, 'index']);
Route::post('/secciones', [SeccionDapController::class, 'store']);
