<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionDapController;
use App\Http\Controllers\Api\FolioController;
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

//Rutas de Folios Oficios DAP
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/creafolios', [App\Http\Controllers\Api\FolioController::class, 'store']);
    Route::post('/folios/{folio}/archivo', [FolioController::class, 'subirArchivoFolio']);
    Route::get('/folios/{folio}/descargar', [FolioController::class, 'descargarArchivoFolio']);
});
Route::middleware('auth:sanctum')->get('obtenerfolios', [FolioController::class, 'obtenerFolios']);

//Rutas de Loging
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController:: class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});
