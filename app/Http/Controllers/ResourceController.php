<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Resource;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recursos = Resource::all();

        return response()->json([
            'recursos' => $recursos,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $recursoData = $request->get('recurso');

        $recurso = new Resource();
        $recurso->nombre = $recursoData['nombre'];
        $recurso->save();

        return response()->json([
            'recurso' => $recurso,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recurso = Resource::find($id);

        return response()->json([
            'recurso' => $recurso,
            'catalogos' => $recurso->catalogues,
            'mensaje' => 'OK'
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $recursoData = $request->get('recurso');

        $recurso = Resource::find($id);
        $recurso->nombre = $recursoData['nombre'];
        $recurso->save();

        return response()->json([
            'recurso' => $recurso,
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
