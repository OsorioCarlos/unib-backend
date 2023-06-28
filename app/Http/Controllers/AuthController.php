<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'token' => $token
            ]);
        }

        return response()->json([
            'estado' => 'error',
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
}
