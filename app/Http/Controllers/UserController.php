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
        $usuarios = User::with(['tipoCatalogo', 'estadoCatalogo'])->paginate(10);

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
        $usuario->estadoCatalogo;

        switch ($usuario->tipoCatalogo->nombre) {
            case 'ESTUDIANTE':
                $usuario->student->carreraCatalogo;
                $usuario->student->nivelCatalogo;
                break;
            case 'DIRECTOR DE CARRERA':
                $usuario->careerDirector->carreraCatalogo;
                break;
            case 'REPRESENTANTE PRÁCTICAS':
                $usuario->internshipRepresentative->organization;
                break;
            default:
                break;
        }

        return response()->json([
            'data' => $usuario,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuarioData = $request->get('usuario');

        $usuario = new User();
        $usuario->identificacion = $usuarioData['identificacion'];
        $usuario->nombre_completo = strtoupper($usuarioData['nombre_completo']);
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

            case 'DIRECTOR DE CARRERA':
                $directorCarrera = new CareerDirector();
                $directorCarrera->user_id = $usuario->id;
                $directorCarrera->carrera_id = $usuarioData['carrera_id'];
                $directorCarrera->save();
                break;

            case 'REPRESENTANTE PRÁCTICAS':
                $internshipRepresentative = new InternshipRepresentative();
                $internshipRepresentative->user_id = $usuario->id;
                $internshipRepresentative->organization_id = $usuarioData['organizacion_id'];
                $internshipRepresentative->funcion_laboral = strtoupper($usuarioData['funcion_laboral']);
                $internshipRepresentative->telefono = $usuarioData['telefono'];
                $internshipRepresentative->save();
                break;
        }

        return response()->json([
            'data' => $usuario,
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
        $usuario->nombre_completo = strtoupper($usuarioData['nombre_completo']);
        $usuario->email = $usuarioData['email'];
        $usuario->estado_id = $usuarioData['estado_id'];
        if (!empty($usuarioData['password'])) {
            $usuario->password = $usuarioData['password'];
        }
        $usuario->save();

        switch ($usuario->tipoCatalogo->nombre) {
            case 'ESTUDIANTE':
                $estudiante = Student::where('user_id', $id)->first();
                $estudiante->carrera_id = $usuarioData['carrera_id'];
                $estudiante->nivel_id = $usuarioData['nivel_id'];
                $estudiante->save();
                break;

            case 'DIRECTOR DE CARRERA':
                $directorCarrera = CareerDirector::where('user_id', $id)->first();
                $directorCarrera->carrera_id = $usuarioData['carrera_id'];
                $directorCarrera->save();
                break;

            case 'REPRESENTANTE PRÁCTICAS':
                $internshipRepresentative = InternshipRepresentative::where('user_id', $id)->first();
                $internshipRepresentative->organization_id = $usuarioData['organizacion_id'];
                $internshipRepresentative->funcion_laboral = strtoupper($usuarioData['funcion_laboral']);
                $internshipRepresentative->telefono = $usuarioData['telefono'];
                $internshipRepresentative->save();
                break;
        }

        return response()->json([
            'data' => $usuario,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json([
                'data' => null,
                'mensaje' => 'No existe usuario',
            ], Response::HTTP_BAD_REQUEST);
        }
        $usuario->delete();
        return response()->json([
            'data' => $usuario,
            'mensaje' => 'Usuario eliminado',
        ], Response::HTTP_OK);
    }

    public function validarUsuarioDuplicado(string $cedula)
    {
        $usuarios = User::where('identificacion', $cedula)->count();
        $valido = true;
        if ($usuarios > 0) {
            $valido = false;
        }

        return response()->json([
            'data' => $valido,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }
}
