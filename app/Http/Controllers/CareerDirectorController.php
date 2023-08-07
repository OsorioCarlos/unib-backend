<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CareerDirector;

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
}
