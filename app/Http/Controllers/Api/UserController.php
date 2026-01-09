<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function obtenerUsuarios(Request $request)
    {
        return response()->json([
            'users' => User::select(
                'id',
                'id_uaa',
                'name',
                'email',
                'seccion',
            )->orderBy('name')->get()
        ]);
    }
}
