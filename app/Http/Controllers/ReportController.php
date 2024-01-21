<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\PreProfessionalPractice;
use App\Models\Student;
use App\Models\User;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filtros = $request->get('filters');

        $estudiantes = Student::where('id', '<>', null);
        $practicasPreprofesionales = PreProfessionalPractice::where('id', '<>', null);

        if (isset($filtros['nivel_id'])) {
            $estudiantes->where('nivel_id', $filtros['nivel_id']);
        }

        if (isset($filtros['carrera_id'])) {
            $estudiantes->where('carrera_id', $filtros['carrera_id']);
        }

        if (isset($filtros['estado_id'])) {
            $practicasPreprofesionales->where('estado_id', $filtros['estado_id']);
        }

        $estudiantes = $estudiantes->get();
        $estudiantesIds = $estudiantes->pluck('id')->toArray();
        $practicasPreprofesionales = $practicasPreprofesionales->whereIn('student_id', $estudiantesIds)->get();

        $json = [
            'mensaje' => 'SIN REGISTROS',
            'data' => 'No hay registros para generar el reporte'
        ];

        if (count($practicasPreprofesionales) > 0) {
            $pdf = Pdf::loadView('reportes.reporte-estudiantes', compact('practicasPreprofesionales'));
            $pdf->save('reporte_estudiantes.pdf');

            $json = [
                'mensaje' => 'OK',
                'data' => 'reporte_estudiantes.pdf'
            ];
        }

        return response()->json($json, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
