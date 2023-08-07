<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Grade;

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
        $calificacionData = $request->get('calificacion');

        $calificacion = new Grade();
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
