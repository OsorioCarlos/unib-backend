<?php

namespace App\Http\Controllers;

use App\Models\PreProfessionalPractice;
use App\Models\Student;
use App\Models\User;
use App\Validations\StudentValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    public function requestPractice(Request $request){
        StudentValidator::validateRequestPractice($request);
        $userController = new UserController();
        $usuarioExiste = $userController->getById(strval($request->input('estudiante.id')));
        if(!$usuarioExiste->isOk()){
            return response()->json($usuarioExiste->original,Response::HTTP_BAD_REQUEST);
        }
        $estudianteExiste = Student::where('usuario_id',strval($request->input('estudiante.id')))->first();
        if(!$estudianteExiste){
            $estudiante = new Student();
            $estudiante->usuario_id = $request->input('estudiante.id');
            $estudiante->carrera_id = $request->input('estudiante.carrera_id');
            $estudiante->nivel_id = $request->input('estudiante.nivel_id');
            $estudiante->save();

            $practicaPreprofesional = new PreProfessionalPractice();
            $practicaPreprofesional->numero_horas_practica = $request->input('practicaPreprofesional.numeroHoras');
            $practicaPreprofesional->area_practicas = $request->input('practicaPreprofesional.area');
            $practicaPreprofesional->estudiante_id = $request->input('estudiante.id');
            $practicaPreprofesional->save();
        }
        $practica = PreProfessionalPractice::where('estudiante_id',strval($request->input('estudiante.id')))->first();

        $estudianteExiste->update([
            'usuario_id' => $request->input('estudiante.id'),
            'carrera_id' => $request->input('estudiante.carrera_id'),
            'nivel_id' => $request->input('estudiante.nivel_id')
        ]);
        $practica->update([
            'area_practicas' => $request->input('practicaPreprofesional.area'),
            'numero_horas_practica' => $request->input('practicaPreprofesional.numeroHoras')
        ]);

        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Solicitud Enviada',
            'detalle'=> $request->json()->all()
        ], Response::HTTP_OK);
    }

    public function acceptCompromise(Request $request){
        StudentValidator::validateAcceptCompromise($request);
        $userController = new UserController();
        $usuarioExiste = $userController->getById(strval($request->input('estudiante.id')));
        if(!$usuarioExiste->isOk()){
            return response()->json($usuarioExiste->original,Response::HTTP_BAD_REQUEST);
        }
        $estudianteExiste = Student::where('usuario_id',strval($request->input('estudiante.id')))->first();
        if(!$estudianteExiste){
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe estudiante',
            ], Response::HTTP_BAD_REQUEST);
        }
        $practica = PreProfessionalPractice::where('estudiante_id',strval($request->input('estudiante.id')))->first();
        $practica->update([
            'estudiante_compromiso' => 0,
            'estudiante_compromiso_fecha' => Carbon::now()
        ]);

        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Compromiso Aceptado',
            'detalle'=> $request->json()->all()
        ], Response::HTTP_OK);
    }
}
