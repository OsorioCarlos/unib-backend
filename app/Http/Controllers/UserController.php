<?php

namespace App\Http\Controllers;

use App\Models\CareerDirector;
use App\Models\InternshipRepresentative;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();

        return response()->json([
            'data' => $usuarios,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::find($id);
        $json = [];

        switch ($usuario->tipoCatalogo->nombre) {
            case 'ESTUDIANTE':
                $json = [
                    'usuario' => $usuario,
                    'estudiante' => $usuario->student,
                    'mensaje' => 'OK'
                ];
                break;
            case 'DIRECTOR CARRERA':
                $json = [
                    'usuario' => $usuario,
                    'director_carrera' => $usuario->careerDirector,
                    'mensaje' => 'OK'
                ];
                break;
            case 'REPRESENTANTE PRACTICAS':
                $json = [
                    'usuario' => $usuario,
                    'representante_practicas' => $usuario->internshipRepresentative,
                    'mensaje' => 'OK'
                ];
                break;
            default:
                $json = [
                    'usuario' => $usuario,
                    'mensaje' => 'OK'
                ];
                break;
        }

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuarioData = $request->get('usuario');

        $usuario = new User();
        $usuario->identificacion = $usuarioData['identificacion'];
        $usuario->nombre_completo = $usuarioData['nombre_completo'];
        $usuario->email = $usuarioData['email'];
        $usuario->password = $usuarioData['password'];
        $usuario->tipo_id = $usuarioData['tipo_id'];
        $usuario->save();

        switch ($usuario->tipoCatalogo->nombre) {
            case 'ESTUDIANTE':
                $estudiante = new Student();
                $estudiante->user_id = $usuario->id;
                $estudiante->carrera_id = $usuarioData['carrera_id'];
                $estudiante->nivel_id = $usuarioData['nivel_id'];
                $estudiante->save();
                break;

            case 'DIRECTOR CARRERA':
                $directorCarrera = new CareerDirector();
                $directorCarrera->user_id = $usuario->id;
                $directorCarrera->carrera_id = $usuarioData['carrera_id'];
                $directorCarrera->save();
                break;

            case 'REPRESENTANTE PRACTICAS':
                $internshipRepresentative = new InternshipRepresentative();
                $internshipRepresentative->user_id = $usuario->id;
                $internshipRepresentative->organization_id = $usuarioData['organizacion_id'];
                $internshipRepresentative->save();
                break;
        }

        return response()->json([
            'usuario' => $usuario,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuarioData = $request->get('usuario');

        $usuario = User::find($id);
        $usuario->identificacion = $usuarioData['identificacion'];
        $usuario->nombre_completo = $usuarioData['nombre_completo'];
        $usuario->email = $usuarioData['email'];
        $usuario->password = $usuarioData['password'];
        $usuario->save();

        switch ($usuario->tipoCatalogo->nombre) {
            case 'ESTUDIANTE':
                $estudiante = Student::where('user_id', $id)->first();
                $estudiante->carrera_id = $usuarioData['carrera_id'];
                $estudiante->nivel_id = $usuarioData['nivel_id'];
                $estudiante->save();
                break;

            case 'DIRECTOR CARRERA':
                $directorCarrera = CareerDirector::where('user_id', $id)->first();
                $directorCarrera->carrera_id = $usuarioData['carrera_id'];
                $directorCarrera->save();
                break;

            case 'REPRESENTANTE PRACTICAS':
                $internshipRepresentative = InternshipRepresentative::where('user_id', $id)->first();
                $internshipRepresentative->organization_id = $usuarioData['organizacion_id'];
                $internshipRepresentative->save();
                break;
        }

        return response()->json([
            'usuario' => $usuario,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
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
