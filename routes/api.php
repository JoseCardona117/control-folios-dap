<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionDapController;
use App\Http\Controllers\Api\FolioController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MinutaController;
use App\Http\Controllers\Api\AcuerdoController;

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

//Rutas de folios de minutas DAP
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/creaminuta', [App\Http\Controllers\Api\MinutaController::class, 'store']);
    Route::post('/minutas/{minuta}/evidencia', [MinutaController::class, 'subirArchivoMinuta']);
    Route::put('/minutas/{minuta}/observaciones', [MinutaController::class, 'actualizarObservacionesMinuta']);
    // Route::get('/folios/{folio}/descargar', [FolioController::class, 'descargarArchivoFolio']);
});
Route::middleware('auth:sanctum')->get('obtenerminutas', [MinutaController::class, 'obtenerMinutas']);
Route::middleware('auth:sanctum')->get('obtenerminuta/{id}', [MinutaController::class, 'obtenerMinutaInd']);

//Rutas de acuerdos DAP
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/minutas/{minuta}/acuerdos', [AcuerdoController::class, 'obtenerAcuerdos']);
    Route::post('/minutas/{minuta}/creaAcuerdo', [AcuerdoController::class, 'store']);

    Route::get('/acuerdos/{acuerdo}', [AcuerdoController::class, 'obtenerAcuerdoInd']);
    Route::put('/acuerdos/{acuerdo}', [AcuerdoController::class, 'actualizarAcuerdo']);
    Route::delete('/acuerdos/{acuerdo}', [AcuerdoController::class, 'borrarAcuerdo']);
});

//Rutas de Usuarios
Route::middleware('auth:sanctum')->get('obtenerUsuarios', [UserController::class, 'obtenerUsuarios']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('creaUsuario', [UserController::class, 'creaUsuario']);
    Route::post('cambiaPassword', [UserController::class, 'cambiaPassword']);
});

//Rutas de Loging
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController:: class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});
