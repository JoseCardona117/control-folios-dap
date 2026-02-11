<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcuerdoDap;
use App\Models\MinutaDap;

class AcuerdoController extends Controller
{
    public function store(Request $request, MinutaDap $minuta)
    {
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

    public function obtenerAcuerdos(MinutaDap $minuta)
    {
        return response()->json([
            'acuerdos' => $minuta->acuerdos()->orderBy('id')->get()
        ]);
    }

    public function actualizarAcuerdo(Request $request, AcuerdoDap $acuerdo)
    {
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

    public function borrarAcuerdo(AcuerdoDap $acuerdo)
    {
        $acuerdo->delete();

        return response()->json([
            'message' => 'Acuerdo eliminado'
        ]);
    }
}
