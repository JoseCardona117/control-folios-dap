<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MinutaDap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MinutaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'motivo' => 'required|string',
            'fecha_reunion' => 'required|date',
            'convoca' => 'required|string',
        ]);

        $user = auth::user();

        $minuta = DB::transaction(function () use ($request) {

            $anio = now()->year;
            $anioCorto = Carbon::now()->format('y');

            $registro = DB::table('folio_minutas')
                ->where('anio', $anio)
                ->lockForUpdate()
                ->first();

            if(!$registro) {
                DB::table('folio_minutas')->insert([
                    'anio' => $anio,
                    'ultimo_consecutivo' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $consecutivo = 1;
            } else {
                $consecutivo = $registro->ultimo_consecutivo + 1;

                DB::table('folio_minutas')
                    ->where('anio',  $anio)
                    ->update([
                        'ultimo_consecutivo' => $consecutivo,
                        'updated_at' => now(),
                    ]);
            }

            //Formato para folio
            $consecutivoFormateado = str_pad($consecutivo, 3, '0', STR_PAD_LEFT);

            $folio = "DAP-M{$consecutivoFormateado}-{$anioCorto}";
            // $fechaReunion = Carbon::createFromFormat(
            //     'd/m/Y',
            //     $request->fecha_reunion
            // )->format('Y-m-d');
            return MinutaDap::create([
                'folio' => $folio,
                'motivo' => $request->motivo,
                'fecha_reunion' => $request->fecha_reunion,//$fechaReunion,
                'convoca' => $request->convoca,
            ]);
            }); 
            
            // $folio = $this->generateFolio();
        return response()->json([
            'message' => 'Minuta creada exitosamente',
            'folio' => [
                 'id' => $minuta->id,
                 'folio' => $minuta->folio,
                 'fecha_reunion' => $minuta->fecha_reunion,
                 'convoca' => $minuta->convoca
                ]
            ], 201);


    }

    public function obtenerMinutas() { //Traer todas las minutas
        $minutas = MinutaDap::orderBy('folio', 'desc')
            ->get();

        return response()->json([
            'data' => $minutas
        ]);
    }

    public function obtenerMinutaInd($id) 
    {
        $minuta = MinutaDap::with('acuerdos')->findOrFail($id);

        return response()->json([
            'data' => $minuta
        ]);
    } 

    public function subirArchivoMinuta(Request $request, MinutaDap $minuta)
    {
        $request->validate([
            'evidencia' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        //Eliminar archivo anterior si existe
        if($minuta->evidencia && Storage::disk('public')->exists($minuta->evidencia)) {
            Storage::disk('public')->delete($minuta->evidencia);
        }

        //Guardar archivo
        $extension = $request->file('evidencia')->getClientOriginalExtension();
        $fileName = $minuta->folio .'_'.now()->format('Ymd_his').'.'.$extension;
        $path = $request->file('evidencia')->storeAs(
            'minutas/dap/'.$minuta->id,
            $fileName,
            'public'
        );

        $minuta->evidencia = $path;
        $minuta->save();

        return response()->json([
            'message' => 'Archivo subido correctamente',
            'archivo' => $path,
            'url' => asset('storage/' . $path)
        ]);
    }
}
