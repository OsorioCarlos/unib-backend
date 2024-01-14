<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\PreProfessionalPractice;
use App\Models\Resource;
use App\Models\Student;
use App\Models\User;
use App\Validations\StudentValidator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;

class StudentController extends Controller
{
    public function requestPractice(Request $request)
    {
        // StudentValidator::validateRequestPractice($request);

        //valido si usuarioId existe y si tiene rol de estudiante
        $usuario = User::where('identificacion', $request->input('usuarioId'))->first();
        if ($usuario) {
            $rol = $usuario->role->nombre;
            if ($rol != 'Estudiante') {
                return response()->json([
                    'estado' => 'error',
                    'mensaje' => 'Usuario no tiene rol de estudiante',
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe usuario',
            ], Response::HTTP_BAD_REQUEST);
        }

        //valido si existe el registro en la tabla estudiante
        $estudiante = Student::where('usuario_id', $usuario->id)->first();
        //si no existe creo el registro
        if (!$estudiante) {
            $estudiante = new Student();
            $estudiante->usuario_id = $usuario->id;
            $estudiante->nivel_id = $request->input('nivelId');
            $estudiante->save();
        } else {
            $estudiante->usuario_id = $usuario->id;
            $estudiante->nivel_id = $request->input('nivelId');
            $estudiante->save();

        }
        //valido si existe el registro en la tabla practica pref
        $practicaPreprofesional = PreProfessionalPractice::where('estudiante_id', $estudiante->id)->first();
        //si no existe creo el registro
        if (!$practicaPreprofesional) {
            $practicaPreprofesional = new PreProfessionalPractice();
            $practicaPreprofesional->area_practicas = $request->input('practicaPreprofesional.area');
            $practicaPreprofesional->numero_horas_practica = $request->input('practicaPreprofesional.numeroHoras');
            $practicaPreprofesional->estudiante_compromiso = $request->input('practicaPreprofesional.estudianteCompromiso');
            $practicaPreprofesional->estudiante_compromiso_fecha = Carbon::now();
            $practicaPreprofesional->estudiante_id = $estudiante->id;
            $practicaPreprofesional->evaluador_type = "";
            $practicaPreprofesional->evaluador_id = 0;
            $practicaPreprofesional->save();
        } else {
            $practicaPreprofesional->area_practicas = $request->input('practicaPreprofesional.area');
            $practicaPreprofesional->numero_horas_practica = $request->input('practicaPreprofesional.numeroHoras');
            $practicaPreprofesional->estudiante_compromiso = $request->input('practicaPreprofesional.estudianteCompromiso');
            $practicaPreprofesional->estudiante_compromiso_fecha = Carbon::now();
            $practicaPreprofesional->save();

        }

        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Solicitud Enviada',
            'detalle' => $request->json()->all()
        ], Response::HTTP_OK);
    }

    public function buscarEstudiante(string $cedula) {
        $recursoTiposUsuario = Resource::where('nombre', 'TIPOS USUARIO')->first();
        $tipoUsuarioId = '';
        foreach ($recursoTiposUsuario->catalogues as $catalogo) {
            if ($catalogo->nombre == 'ESTUDIANTE') {
                $tipoUsuarioId = $catalogo->id;
                break;
            }
        }

        $usuario = User::where('tipo_id', $tipoUsuarioId)
            ->where('identificacion', $cedula)
            ->first();
        $usuario->student;

        return response()->json([
            'data' => $usuario,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }
}
