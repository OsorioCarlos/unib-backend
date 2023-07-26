<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Validations\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function getAll()
    {
        $usuarios = User::all();
        $usuariosDto = [];
        foreach ($usuarios as $usuario) {
            $usuarioDto = new User();
            $usuarioDto->id = $usuario->id;
            $usuarioDto->identificacion = $usuario->identificacion;
            $usuarioDto->nombre = $usuario->nombre;
            $usuarioDto->estado = $usuario->estado;
            $usuarioDto->email = $usuario->email;
            $usuarioDto->rol = $usuario->rol->nombre;
            array_push($usuariosDto, $usuarioDto);
         }
        return response()->json($usuariosDto, Response::HTTP_OK);
    }

    public function getById(string $id)
    {
        $usuario = User::find($id);
        if(!$usuario){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe usuario',
            ], Response::HTTP_BAD_REQUEST);
        }
        $usuarioDto = new User();
        $usuarioDto->id = $usuario->id;
        $usuarioDto->identificacion = $usuario->identificacion;
        $usuarioDto->nombre = $usuario->name;
        $usuarioDto->estado = $usuario->estado;
        $usuarioDto->email = $usuario->email;
        $usuarioDto->rol = $usuario->rol->nombre;
        
        return response()->json($usuarioDto, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        UserValidator::validate($request);
        $rol = Role::find($request->rol_id);
        if(!$rol){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe el rol',
            ], Response::HTTP_BAD_REQUEST);
        }
        $existeEmail = User::where('email', $request->email)->where('id',  '!=', $request->id)->exists();
        if($existeEmail){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Ya existe el email',
            ], Response::HTTP_BAD_REQUEST);
        }
        $usuario = new User();
        $usuario->identificacion = $request->identificacion;
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->identificacion);
        $usuario->rol_id = $request->rol_id;
        $usuario->estado = 'activo';

        $usuario->save();

        return response()->json($usuario, Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {
        $usuario = User::find($request->id);
        UserValidator::validate($request);

        if(!$usuario){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe usuario',
            ], Response::HTTP_BAD_REQUEST);
        }

        $existeEmail = User::where('email', $request->email)->where('id',  '!=', $request->id)->exists();
        $existeIdentificacion = User::where('identificacion', $request->identificacion)->where('id',  '!=', $request->id)->exists();
        if($existeEmail){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Ya existe el email',
            ], Response::HTTP_BAD_REQUEST);
        }
        if($existeIdentificacion){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Ya existe la identificacion',
            ], Response::HTTP_BAD_REQUEST);
        }
        $usuario->update($request->all());

        return response()->json($usuario, Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $usuario = User::find($id);
        if(!$usuario){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe usuario',
            ], Response::HTTP_BAD_REQUEST);
        }
        $usuario->delete();
        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Usuario eliminado',
        ], Response::HTTP_OK);
    }
}
