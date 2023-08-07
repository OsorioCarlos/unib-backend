<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GradingCriteria;

class GradingCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteriosCalificacion = GradingCriteria::all();

        return response()->json([
            'criterios_calificacion' => $criteriosCalificacion,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $criterioCalificacionData = $request->get('criterio_calificacion');

        $criterioCalificacion = new GradingCriteria();
        $criterioCalificacion->calificacion = $criterioCalificacionData['calificacion'];
        $criterioCalificacion->calificacion_id = $criterioCalificacionData['calificacion_id'];
        $criterioCalificacion->criterio_id = $criterioCalificacionData['criterio_id'];
        $criterioCalificacion->save();

        return response()->json([
            'criterio_calificacion' => $criterioCalificacion,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $criterioCalificacion = GradingCriteria::find($id);

        return response()->json([
            'criterio_calificacion' => $criterioCalificacion,
            'mensaje' => 'OK'
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $criterioCalificacionData = $request->get('criterio_calificacion');

        $criterioCalificacion = GradingCriteria::find($id);
        $criterioCalificacion->calificacion = $criterioCalificacionData['calificacion'];
        $criterioCalificacion->calificacion_id = $criterioCalificacionData['calificacion_id'];
        $criterioCalificacion->criterio_id = $criterioCalificacionData['criterio_id'];
        $criterioCalificacion->save();

        return response()->json([
            'criterio_calificacion' => $criterioCalificacion,
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
