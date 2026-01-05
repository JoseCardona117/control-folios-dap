<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeccionDap;

class SeccionDapController extends Controller
{
    public function index()
    {
        return response()->json(
            SeccionDap::all()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:191',
            'codigo' => 'required|string|max:191',
        ]);

        $seccion = SeccionDap::create($request->all());

        return response()->json($seccion, 201);
    }
}
