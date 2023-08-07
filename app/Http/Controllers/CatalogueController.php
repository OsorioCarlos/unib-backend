<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Catalogue;

class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $catalogos = Catalogue::all();

        return response()->json([
            'catalogos' => $catalogos,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $catalogoData = $request->get('catalogo');

        $catalogo = new Catalogue();
        $catalogo->nombre = $catalogoData['nombre'];
        $catalogo->recurso_id = $catalogoData['recurso_id'];
        $catalogo->save();

        return response()->json([
            'catalogo' => $catalogo,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $catalogo = Catalogue::find($id);

        return response()->json([
            'catalogo' => $catalogo,
            'mensaje' => 'OK'
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $catalogoData = $request->get('catalogo');

        $catalogo = Catalogue::find($id);
        $catalogo->nombre = $catalogoData['nombre'];
        $catalogo->recurso_id = $catalogoData['recurso_id'];
        $catalogo->save();

        return response()->json([
            'catalogo' => $catalogo,
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
