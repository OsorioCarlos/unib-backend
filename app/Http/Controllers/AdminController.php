<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function crearUsuario(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'email' => 'required|email|unique:users',
            'identificacion' => 'required|unique:users',
            'nombreCompleto' => 'required|string',
            'tipoUsuario' => 'required',
        ]);
        $recursoTiposUsuario = Resource::where('nombre', 'ESTADOS USUARIO')->first();
        $estadoUsuario = '';
        foreach ($recursoTiposUsuario->catalogues as $catalogo) {
            if ($catalogo->nombre == 'ACTIVO') {
                $estadoUsuario = $catalogo->id;
                break;
            }
        }


        $recursoTiposUsuario = Resource::where('nombre', 'TIPOS USUARIO')->first();
        $tipoUsuario = '';
        foreach ($recursoTiposUsuario->catalogues as $catalogo) {
            if ($catalogo->nombre == $request->input('tipoUsuario')) {
                $tipoUsuario = $catalogo->id;
                break;
            }
        }
        $authUser = Auth::user();
        if ($authUser->tipoCatalogo->nombre != 'ADMINISTRADOR') {
            return response()->json([
                'mensaje' => 'No tiene permisos para realizar esta acción'
            ], 401);
        }
        // Crear el usuario
        $usuario = new User();
        $usuario->email = $request->input('email');
        $usuario->identificacion = $request->input('identificacion');
        $usuario->nombre_completo = $request->input('nombreCompleto');
        $usuario->tipo_id = $tipoUsuario;
        $usuario->estado_id = $estadoUsuario;
        $usuario->password = $request->input('identificacion');
        $usuario->save();

        // Puedes devolver una respuesta o redirigir a otra página según tus necesidades
        return response()->json(['mensaje' => 'Usuario creado correctamente'], 201);
    }

    public function eliminarUsuarios(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'identificacion' => 'required',
        ]);
        $authUser = Auth::user();
        $usuario = User::where('identificacion', $authUser->identificacion)->first();
        if ($usuario == null) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }
        $usuario->delete();
        return response()->json(['mensaje' => 'Usuario eliminado correctamente'], 200);
    }


    public function consultarUsuarios()
    {
        // Obtener todos los usuarios
        $usuarios = User::with('tipoCatalogo')->get();

        // Puedes devolver la lista de usuarios como respuesta
        return response()->json(['data' => $usuarios], 200);
    }
}
