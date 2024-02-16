<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credenciales = $request->only('email', 'password');

        if (Auth::attempt($credenciales)) {
            $usuario = Auth::user();
            $token = $usuario->createToken('ApiToken')->plainTextToken;
            return response()->json([
                'estado' => 'ok',
                'mensaje' => 'Inicio de sesión exitoso',
                'token' => $token,
                'rol'=> $usuario->tipoCatalogo->nombre
            ]);
        }

        return response()->json([
            'estado' => $credenciales,
            'mensaje' => 'Credenciales inválidas'
        ], 401);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Cierre de sesión exitoso'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'mensaje' => 'Token actualizado',
            'token' => Auth::refresh()
        ]);
    }

    public function authUser()
    {
        $authUser = Auth::user();
        $usuario = [
            'cedula' => $authUser->identificacion,
            'tipo_usuario' => $authUser->tipoCatalogo->nombre
        ];

        switch ($usuario['tipo_usuario']) {
            case 'ESTUDIANTE':
                break;
            case 'DIRECTOR CARRERA':
                $usuario['carrera'] = $authUser->careerDirector->carreraCatalogo->nombre;
                break;
            case 'REPRESENTANTE PRACTICAS':
                $usuario['carrera'] = $authUser->internshipRepresentative->organization->nombre;
                break;
        }

        return response()->json([
            'data' => $usuario,
            'mensaje' => 'OK'
        ], 200);
    }

    public function obtenerRolUsuario()
    {
        $authUser = Auth::user();
        return response()->json([
            'data' => $authUser->tipoCatalogo->nombre,
            'mensaje' => 'OK'
        ], 200);
    }
}
