<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function creaUsuario(Request $request)
    {
        $request->validate([
            'name' =>   'required|string|max:255',
            'email' =>  'required|email|unique:users,email',
            'id_uaa' => 'required|integer|unique:users,id_uaa',
            'seccion' => 'required|string',
            'password' =>'required|string|min:8'
        ]);

        $passwordTrim = trim($request->password);
        $user = User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'id_uaa' => $request->id_uaa,
            'seccion' => $request->seccion,
            'password' => Hash::make($passwordTrim),
        ]); 

        return response()->json([
            'message' => 'Usuario creado correcto',
            'user'    => $user
        ], 201);
    }
}
