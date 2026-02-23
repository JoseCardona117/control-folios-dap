<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MinutaExterna;
use Illuminate\Support\Facades\Auth;

class MinutaExternaController extends Controller
{
    public function store(Request $request) {

        $this->authorize('create', MinutaExterna::class);
        $validated = $request->validate([
            'folio' => 'nullable|string|max:255',
            'motivo' => 'required|string',
            'fecha_reunion' => 'required|date',
            'convoca' => 'required|string',
            'observaciones' => 'nullable|string'
        ]);

        $user = auth::user();

        $minuta = MinutaExterna::create($validated);
            
        return response()->json([
            'message' => 'Minuta externa creada exitosamente',
            'data' => $minuta
            ], 201);
    }

    public function obtenerMinutas() { //Traer todas las minutas
        $this->authorize('viewAny', MinutaExterna::class);

        $minutas = MinutaExterna::with('acuerdos:id,minuta_id,description,responsable')
            // ->orderBy('folio', 'desc')
            ->get();

        return response()->json([
            'data' => $minutas
        ]);
    }

    public function obtenerMinutaInd($id) 
    {
        $this->authorize('viewAny', MinutaExterna::class);
        $minuta = MinutaExterna::with('acuerdos')->findOrFail($id);

        return response()->json([
            'data' => $minuta
        ]);
    } 

    public function subirArchivoMinuta(Request $request, MinutaExterna $minuta)
    {
        $this->authorize('update', $minuta);
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
            'minutas/ext/'.$minuta->id,
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

    public function actualizarObservacionesMinuta(Request $request, MinutaExterna $minuta)
    {
        $this->authorize('update', $minuta);
        $request->validate([
            'observaciones' => 'required|string'
        ]);

        $minuta->update([
            'observaciones' => $request->observaciones
        ]);

        return response()->json([
            'message' => 'Observaciones actualizadas correctamente',
            'minuta' => $minuta
        ]);
    }

    public function update(Request $request, MinutaExterna $minuta)
    {
        $this->authorize('update',$minuta);

        $validated = $request->validate([
            'folio' => 'nullable|string|max:255',
            'motivo' => 'required|string',
            'fecha_reunion' => 'required|date',
            'convoca' => 'required|string',
            'observaciones' => 'nullable|string'
        ]);

        $minutaExterna->update($validated);

        return response()->json([
            'message' => 'Minuta externa actualizada correctamente',
            'data' => $minutaExterna
        ]);
    }

    public function destroy(MinutaExterna $minutaExterna)
    {
        $this->authorize('delete', $minutaExterna);

        $minutaExterna->delete();

        return response()->json([
            'message' => 'Minuta externa eliminada correctamente'
        ]);
    }
}
