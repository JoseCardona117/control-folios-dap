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
use Symfony\Component\HttpFoundation\Response;

class FolioController extends Controller
{
    public function store(Request $request) //Crear Folios Nuevos
    {
        $request->validate([
            'id_seccion' => 'required|integer',
            'asunto' => 'required|string',
            'dirigido' => 'required|string',
            'fecha' => 'required|date_format:d-m-Y',
        ]);

        $user = auth::user();

        $year = Carbon::now()->format('y');

        $lastFolio = FolioDap::where('id_seccion', $request->id_seccion)
            ->orderBy('id','desc')
            ->first();

        $nextFolio = 1;
        if($lastFolio) {
            $parts = explode('-', $lastFolio->folio);
            $nextFolio = intval(end($parts)) + 1;
        }

        // Mapeo de secciones
        $seccionCodes = match (intval($request->id_seccion)){
            1 => '',
            2 => 'SDC',
            3 => 'SIC',
            4 => 'SEC',
        };
        $codigoSeccion = ($seccionCodes == '') ? '' : '-'.$seccionCodes;
        $codigoFolio = "DGIP-DAP{$codigoSeccion}-{$year}-{$nextFolio}";

        $folio = FolioDap::create([
            'folio' => $codigoFolio,
            'id_seccion' => $request->id_seccion,
            'responsable' => $user->id_uaa,
            'asunto' => $request->asunto,
            'dirigido' => $request->dirigido,
            'fecha' => Carbon::createFromFormat('d-m-Y', $request->fecha),//->format('Y-m-d'),
            'archivo' => null,
        ]);

        return response()->json([
            'message' => 'Folio creado exitosamente',
            'folio' => $folio
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
                'responsable' => $folio->responsableUsuario?->name,
                'asunto' => $folio->asunto,
                'dirigido' => $folio->dirigido,
                'fecha' => Carbon::parse($folio->fecha)->format('d-m-y'),
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
