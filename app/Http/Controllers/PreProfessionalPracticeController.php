<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\PreProfessionalPractice;

class PreProfessionalPracticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $practicasPreprofesionales = PreProfessionalPractice::all();

        return response()->json([
            'practicas_preprofesionales' => $practicasPreprofesionales,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $practicaPreprofesionalData = $request->get('practica_preprofesional');

        $practicaPreprofesional = new PreProfessionalPractice();
        $practicaPreprofesional->numero_horas_practica = $practicaPreprofesionalData['numero_horas_practica'];
        $practicaPreprofesional->estudiante_compromiso = $practicaPreprofesionalData['estudiante_compromiso'];
        $practicaPreprofesional->estudiante_compromiso_fecha = Carbon::parse($practicaPreprofesionalData['estudiante_compromiso_fecha'])->format('Y-m-d');
        $practicaPreprofesional->objetivos_practica = $practicaPreprofesionalData['objetivos_practica'];
        $practicaPreprofesional->tareas = $practicaPreprofesionalData['tareas'];
        $practicaPreprofesional->horario = $practicaPreprofesionalData['horario'];
        $practicaPreprofesional->fecha_inicio = Carbon::parse($practicaPreprofesionalData['fecha_inicio'])->format('Y-m-d');
        $practicaPreprofesional->fecha_finalizacion = Carbon::parse($practicaPreprofesionalData['fecha_finalizacion'])->format('Y-m-d');
        $practicaPreprofesional->empresa_compromiso = $practicaPreprofesionalData['empresa_compromiso'];
        $practicaPreprofesional->empresa_compromiso_fecha = Carbon::parse($practicaPreprofesionalData['empresa_compromiso_fecha'])->format('Y-m-d');
        $practicaPreprofesional->area_practicas = $practicaPreprofesionalData['area_practicas'];
        $practicaPreprofesional->nota_final = $practicaPreprofesionalData['nota_final'];
        $practicaPreprofesional->estudiante_id = $practicaPreprofesionalData['estudiante_id'];
        $practicaPreprofesional->save();

        return response()->json([
            'practica_preprofesional' => $practicaPreprofesional,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $practicaPreprofesional = PreProfessionalPractice::find($id);

        return response()->json([
            'practica_preprofesional' => $practicaPreprofesional,
            'mensaje' => 'OK'
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $practicaPreprofesionalData = $request->get('practica_preprofesional');

        $practicaPreprofesional = PreProfessionalPractice::find($id);
        $practicaPreprofesional->numero_horas_practica = $practicaPreprofesionalData['numero_horas_practica'];
        $practicaPreprofesional->estudiante_compromiso = $practicaPreprofesionalData['estudiante_compromiso'];
        $practicaPreprofesional->estudiante_compromiso_fecha = Carbon::parse($practicaPreprofesionalData['estudiante_compromiso_fecha'])->format('Y-m-d');
        $practicaPreprofesional->objetivos_practica = $practicaPreprofesionalData['objetivos_practica'];
        $practicaPreprofesional->tareas = $practicaPreprofesionalData['tareas'];
        $practicaPreprofesional->horario = $practicaPreprofesionalData['horario'];
        $practicaPreprofesional->fecha_inicio = Carbon::parse($practicaPreprofesionalData['fecha_inicio'])->format('Y-m-d');
        $practicaPreprofesional->fecha_finalizacion = Carbon::parse($practicaPreprofesionalData['fecha_finalizacion'])->format('Y-m-d');
        $practicaPreprofesional->empresa_compromiso = $practicaPreprofesionalData['empresa_compromiso'];
        $practicaPreprofesional->empresa_compromiso_fecha = Carbon::parse($practicaPreprofesionalData['empresa_compromiso_fecha'])->format('Y-m-d');
        $practicaPreprofesional->area_practicas = $practicaPreprofesionalData['area_practicas'];
        $practicaPreprofesional->nota_final = $practicaPreprofesionalData['nota_final'];
        $practicaPreprofesional->estudiante_id = $practicaPreprofesionalData['estudiante_id'];
        $practicaPreprofesional->save();

        return response()->json([
            'practicas_preprofesional' => $practicaPreprofesional,
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
