<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\GradingCriteria;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $calificaciones = Grade::all();

        return response()->json([
            'calificaciones' => $calificaciones,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuario = User::where('identificacion', $request->input('id'))->first();
        if ($usuario === null) {
            return response()->json([
                'mensaje' => 'No se encontró el estudiante',
                'data' => ''
            ], 404);
        }
        $requestData = $request->all();
        $practicaPreProfesional = $usuario->student->preprofessionalPractices->first();
        if ($practicaPreProfesional === null) {
            return response()->json([
                'mensaje' => 'No se encontró la práctica preprofesional',
                'data' => ''
            ], 404);
        }
        $usuario = Auth::user();

        $calificacion = new Grade();
        $calificacion->pre_professional_practice_id = $practicaPreProfesional->id;
        $calificacion->user_id = $usuario->id;
        $calificacion->nota_promedio = $requestData['calificacion']['nota_promedio'];
        $calificacion->porcentaje_asistencia = $requestData['calificacion']['porcentaje_asistencia'];
        $calificacion->observaciones = isset($requestData['calificacion']['observaciones']) ? (strtoupper($requestData['calificacion']['observaciones'])) : null;
        $calificacion->recomendaciones = isset($requestData['calificacion']['recomendaciones']) ? (strtoupper($requestData['calificacion']['recomendaciones'])) : null;
        $calificacion->save();

        foreach ($requestData['calificacion']['criterios'] as $criterioData) {
            $criterioCalificacion = new GradingCriteria();
            $criterioCalificacion->grade_id = $calificacion->id;
            $criterioCalificacion->criterio_id = $criterioData['id'];
            $criterioCalificacion->calificacion = $criterioData['calificacion'];
            $criterioCalificacion->save();
        }

        if (count($practicaPreProfesional->grades) === 2) {
            $calificaciones = $practicaPreProfesional->grades;
            $notaFinal = 0;
            $asistencia = 0;

            foreach ($calificaciones as $calificacion) {
                $notaFinal += $calificacion['nota_promedio'];
                $asistencia += $calificacion['porcentaje_asistencia'];
            }

            $practicaPreProfesional->nota_final = $notaFinal / 2;
            $practicaPreProfesional->asistencia = $asistencia / 2;
            $practicaPreProfesional->horas_practicas_realizadas = ($practicaPreProfesional->horas_practicas_solicitadas * $practicaPreProfesional->asistencia) / 100;
            $practicaPreProfesional->save();
        }

        return response()->json([
            'mensaje' => 'OK',
            'data' => ''
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $calificacion = Grade::find($id);

        return response()->json([
            'calificacion' => $calificacion,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $calificacionData = $request->get('calificacion');

        $calificacion = Grade::find($id);
        $calificacion->promedio = $calificacionData['promedio'];
        $calificacion->practica_preprofesional_id = $calificacionData['practica_preprofesional_id'];
        $calificacion->evaluador_id = $calificacionData['evaluador_id'];
        $calificacion->save();

        return response()->json([
            'calificacion' => $calificacion,
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
}
