<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FolioDap;
//use App\Models\SeccionDap;
//use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FolioController extends Controller
{
    public function store(Request $request) //Crear Folios Nuevos
    {
        $request->validate([
            'responsable' => 'required|integer',
            'id_seccion' => 'required|integer',
            'asunto' => 'required|string',
            'tipo_asunto' => 'nullable|in:Solicitar,Informar',
            'dirigido' => 'required|string',
            //'fecha' => 'required|date_format:d-m-Y',
        ]);

        $user = auth::user();

        $year = Carbon::now()->format('y');

        $folio = DB::transaction(function () use ($request, $year) {
            
            $sequence = DB::table('folio_sequences')
                ->where('year', $year)
                //->where('id_seccion', $request->id_seccion)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                DB::table('folio_sequences')->insert([
                    'year' => $year,
                    //'id_seccion' => $request->id_seccion,
                    'last_number' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $nextNumber = 1;
            } else {
                $nextNumber = $sequence->last_number + 1;

                DB::table('folio_sequences')
                    ->where('id', $sequence->id)
                    ->update([
                        'last_number' => $nextNumber,
                        'updated_at' => now(),
                    ]);
            }

            $codigoFolio = "DGIP-DAP-{$year}-{$nextNumber}";
    
            //Fecha generada auto con hoy
            $fecha = Carbon::now()->format('Y-m-d');
    
            return FolioDap::create([
                'folio' => $codigoFolio,
                'id_seccion' => $request->id_seccion,
                'responsable' => $request->responsable,
                'asunto' => $request->asunto,
                'tipo_asunto' => $request->tipo_asunto,
                'dirigido' => $request->dirigido,
                //'fecha' => Carbon::createFromFormat('d-m-Y', $request->fecha),//->format('Y-m-d'),
                'fecha' => $fecha,//->format('Y-m-d'),
                'archivo' => null,
            ]);
        });


        $folio->load(['responsableUsuario', 'seccion']);

        return response()->json([
            'message' => 'Folio creado exitosamente',
            'folio' => [
                'id' => $folio->id,
                'folio' => $folio->folio,
                'seccion' => $folio->seccion->nombre,
                'responsable' => $folio->responsable,
                'nombre_responsable' => $folio->responsableUsuario->name,
                'asunto' => $folio->asunto,
                'dirigido' => $folio->dirigido,
                'fecha' => Carbon::parse($folio->fecha)->format('Y-m-d'),
                'archivo' => $folio->archivo ? $folio->archivo : null
            ]
        ], 201);
    }

    public function obtenerFolios() {
        $folios = FolioDap::with([
            'seccion',
            'responsableUsuario'
        ])->get();

        $response = $folios->map(function ($folio) {
            return [
                'id' => $folio->id,
                'folio' => $folio->folio,
                'seccion' => $folio->seccion?->nombre,
                'responsable' => $folio->responsable,
                'nombre_responsable' => $folio->responsableUsuario?->name,
                'asunto' => $folio->asunto,
                'dirigido' => $folio->dirigido,
                'fecha' => Carbon::parse($folio->fecha)->format('Y-m-d'),//('d-m-y'),
                'archivo' => $folio->archivo
            ];
        });

        return response()->json($response);
    }

    public function subirArchivoFolio(Request $request, FolioDap $folio)
    {
        //Validar que el usuario autenticado sea el responsable para subir archivo
        /*if($folio->responsable !== auth()->user()->id_uaa){
            return response()->json([
                'message' => 'No tienes permiso para subir archivos a este folio'
            ], 403);
        }*/ //Esperar validación para esta función por parte de la jefa


        $request->validate([
            'archivo' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        //Eliminar archivo anterior si existe
        if($folio->archivo && Storage::disk('public')->exists($folio->archivo)) {
            Storage::disk('public')->delete($folio->archivo);
        }

        //Guardar archivo
        $extension = $request->file('archivo')->getClientOriginalExtension();
        $fileName = $folio->folio .'_'.now()->format('Ymd_his').'.'.$extension;
        $path = $request->file('archivo')->storeAs(
            'folios/'.$folio->id,
            $fileName,
            'public'
        );

        //Actualizar folio
        $folio->archivo = $path;
        $folio->save();

        return response()->json([
            'message' => 'Archivo subido correctamente',
            'archivo' => $path,
            'url' => asset('storage/' . $path)
        ]);
    }

    public function descargarArchivoFolio(FolioDap $folio) 
    {
        $user = auth()->user();

        //Validar que exista el archivo
        if(!$folio->archivo || Storage::disk('public')->exists($folio->archivo)) {
            return response()->json([
                'message' => 'El folio no tiene archivo adjunto'
            ], Response::HTTP_NOT_FOUND);
        }

        //Validar usuario como responsable
        /*if ($folio->responsable !== $user->id_uaa) {
            return response()->json([
                'message' => 'No tienes permiso para descargar este archivo'
            ], Response::HTTP_FORBIDDEN);
        }*/

        //Ruta fisica del archivo
        $filePath = storage_path('app/public' . $folio->archivo);

        //Nombre del archivo
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $downloadName = $folio->folio. '.' . $extension;

        //Descarga 
        return response()->download($filePath, $downloadName, [
            'Content-Type' => mime_content_type($filePath),
        ]);
    }
}
