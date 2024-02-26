<?php

namespace App\Http\Controllers;

use App\Models\CareerDirector;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CareerDirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $directoresCarrera = CareerDirector::all();

        return response()->json([
            'directores_carrera' => $directoresCarrera,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $directorCarreraData = $request->get('director_carrera');

        $directorCarrera = new CareerDirector();
        $directorCarrera->usuario_id = $directorCarreraData['usuario_id'];
        $directorCarrera->carrera_id = $directorCarreraData['carrera_id'];
        $directorCarrera->save();

        return response()->json([
            'director_carrera' => $directorCarrera,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $directorCarrera = CareerDirector::find($id);

        return response()->json([
            'director_carrera' => $directorCarrera,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $directorCarreraData = $request->get('director_carrera');

        $directorCarrera = CareerDirector::find($id);
        $directorCarrera->usuario_id = $directorCarreraData['usuario_id'];
        $directorCarrera->carrera_id = $directorCarreraData['carrera_id'];
        $directorCarrera->save();

        return response()->json([
            'director_carrera' => $directorCarrera,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function obtenerEvaluacionesPendientes()
    {
        $user = Auth::user();
        $director = $user->careerDirector;
        $estudiantes = Student::where('carrera_id', $director->carrera_id)->get();
        $estudiantesRespuesta = [];
        foreach ($estudiantes as $estudiante) {
           if($estudiante->preprofessionalPractices->first() !== null){
               $gradeInternship = $estudiante->preprofessionalPractices->first()->grades->where('user_id', $estudiante->preprofessionalPractices->first()->internshipRepresentative->user_id)->first();
               $grade = $estudiante->preprofessionalPractices->first()->grades->where('user_id', $estudiante->preprofessionalPractices->first()->careerDirector->user_id)->first();
               if($gradeInternship!= null && $grade == null ){
                     array_push($estudiantesRespuesta, [
                          'identificacion' => $estudiante->user->identificacion,
                          'nombre' => $estudiante->user->nombre_completo,
                          'nivel' => $estudiante->nivelCatalogo->nombre,
                     ]);
               }
           }
        }
        return response()->json([
            'data' => $estudiantesRespuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerInformacionEvaluacion(string $id)
    {
        $user = User::where('identificacion', $id)->first();
        if ($user == null) {
            return response()->json([
                'mensaje' => 'No se encontro el estudiante'
            ], Response::HTTP_NOT_FOUND);
        }
        $practica = $user->student->preprofessionalPractices->first();
        if ($practica == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene una practica asignada'
            ], Response::HTTP_NOT_FOUND);
        }
        $grade = $practica->grades->where('user_id', $practica->internshipRepresentative->user_id)->first();

        if ($grade == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene una evaluacion por el representante asignado'
            ], Response::HTTP_NOT_FOUND);
        }

        $respuesta = [
            'nombre_estudiante' => $user->nombre_completo,
            'escuela' => $user->student->carreraCatalogo->nombre,
            'nivel' => $user->student->nivelCatalogo->nombre,
            'area_practicas' => $practica->area_practicas_solicitadas,
            'horas_practicas' => $practica->horas_practicas_solicitadas,
            'fecha_inicio' => $practica->fecha_inicio,
            'fecha_fin' => $practica->fecha_fin,
            'razon_social' => $practica->organization->razon_social,
            'representante_legal' => $practica->organization->representante_legal,
            'area_dedicacion' => $practica->organization->area_dedicacion,
            'representante' => $practica->internshipRepresentative->user->nombre_completo,
            'nota_organizacion' => $grade->nota_promedio
        ];

        return response()->json([
            'data' => $respuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);

    }

    public function obtenerEstudiantes(){
        $user = Auth::user();
        $director = $user->careerDirector;
        if($director == null){
            return response()->json([
                'mensaje' => 'No se encontro el director'
            ], Response::HTTP_NOT_FOUND);
        }

        $estudiantes = Student::where('carrera_id', $director->carrera_id)->get();
        $estudiantesRespuesta = [];
        foreach ($estudiantes as $estudiante) {
            if($estudiante->preprofessionalPractices->first() !== null){
                array_push($estudiantesRespuesta, [
                    'identificacion' => $estudiante->user->identificacion,
                    'nombre' => $estudiante->user->nombre_completo,
                    'carrera' => $estudiante->carreraCatalogo->nombre,
                    'nivel' => $estudiante->nivelCatalogo->nombre,
                    'estadoPractica'=> $estudiante->preprofessionalPractices->first()->estadoCatalogo->nombre
                ]);
            }
        }
        return response()->json([
            'data' => $estudiantesRespuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }
}
