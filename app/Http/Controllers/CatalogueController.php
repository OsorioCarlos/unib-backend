<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Catalogue;
use App\Models\Resource;

class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nombreRecurso = $request->get('nombre');

        $catalogos = Catalogue::where('id', '<>', null);

        if ($nombreRecurso !== '' && $nombreRecurso !== null) {
            $recurso = Resource::where('nombre', $nombreRecurso)->first();
            if ($recurso) {
                $catalogos->where('resource_id', $recurso->id);
            }
        }

        $catalogos = $catalogos->get();

        return response()->json([
            'data' => $catalogos,
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
        $catalogo->resource_id = $catalogoData['recurso_id'];
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
        $catalogo->resource_id = $catalogoData['recurso_id'];
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
