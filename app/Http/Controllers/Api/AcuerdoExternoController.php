<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcuerdoExterno;
use App\Models\MinutaExterna;

class AcuerdoExternoController extends Controller
{
    public function store(Request $request, MinutaExterna $minuta)
    {
        $this->authorize('create', AcuerdoExterno::class);
        $request->validate([
            'description' => 'required|string',
            'responsable' => 'nullable|string',
            'estado' => 'nullable|in:pendiente,en_proceso,cumplido,no_cumplido',
            'fecha_compromiso' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $acuerdo = $minuta->acuerdos()->create([
            'description' => $request->description,
            'responsable' => $request->responsable,
            'estado' => $request->estado ?? 'pendiente',
            'fecha_compromiso' => $request->fecha_compromiso,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json([
            'message' => 'Acuerdo creado correctamente',
            'acuerdo' => $acuerdo
        ], 201);
    }

    public function obtenerAcuerdos(MinutaExterna $minuta)
    {
        $this->authorize('viewAny', AcuerdoExterno::class);
        return response()->json([
            'acuerdos' => $minuta->acuerdos()->orderBy('id')->get()
        ]);
    }

    public function actualizarAcuerdo(Request $request, AcuerdoExterno $acuerdo)
    {
        $this->authorize('update', $acuerdo);
        $request->validate([
            'description' => 'sometimes|string',
            'responsable' => 'sometimes|nullable|string',
            'estado' => 'sometimes|in:pendiente,en_proceso,cumplido,no_cumplido',
            'fecha_compromiso' => 'sometimes|nullable|date',
            'fecha_cumplimiento' => 'sometimes|nullable|date',
            'observaciones' => 'sometimes|nullable|string',
        ]);

        $acuerdo->update($request->only([
            'description',
            'responsable',
            'estado',
            'fecha_compromiso',
            'fecha_cumplimiento',
            'observaciones'
        ]));

        return response()->json([
            'message' => 'Acuerdo actualizado',
            'acuerdo' => $acuerdo
        ]);
    }

    public function borrarAcuerdo(AcuerdoExterno $acuerdo)
    {
        $this->authorize('delete', $acuerdo);
        $acuerdo->delete();

        return response()->json([
            'message' => 'Acuerdo eliminado'
        ]);
    }
}
