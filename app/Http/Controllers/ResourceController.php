<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Catalogue;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recursos = Resource::paginate(10);

        return response()->json([
            'data' => $recursos,
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
        $recurso->nombre = strtoupper($recursoData['nombre']);
        $recurso->save();

        foreach ($recursoData['catalogos'] as $catalogoData) {
            $catalogo = new Catalogue();
            $catalogo->nombre = strtoupper($catalogoData['nombre']);
            $catalogo->resource_id = $recurso->id;
            $catalogo->save();
        }

        return response()->json([
            'data' => $recurso,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recurso = Resource::find($id);
        $recurso->catalogues;

        return response()->json([
            'data' => $recurso,
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
        $recurso->nombre = strtoupper($recursoData['nombre']);
        $recurso->save();


        $borrarCatalogoIds = [];
        foreach ($recurso->catalogues as $catalogue) {
            $existe = false;
            foreach ($recursoData['catalogos'] as $catalogoData) {
                if ($catalogue->id === $catalogoData['id']) {
                    $existe = true;
                }
            }
            if (!$existe) {
                $borrarCatalogoIds[] = $catalogue->id;
            }
        }
        $catalogosBorrar = Catalogue::whereIn('id', $borrarCatalogoIds)->get();
        foreach ($catalogosBorrar as $catalogoBorrar) {
            $catalogoBorrar->delete();
        }

        foreach ($recursoData['catalogos'] as $catalogoData) {
            $catalogo = Catalogue::find($catalogoData['id']);
            if ($catalogo) {
                $catalogo->nombre = strtoupper($catalogoData['nombre']);
                $catalogo->save();
            } else {
                $catalogo = new Catalogue();
                $catalogo->nombre = strtoupper($catalogoData['nombre']);
                $catalogo->resource_id = $recurso->id;
                $catalogo->save();
            }
        }

        return response()->json([
            'data' => $recurso,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recurso = Resource::find($id);
        $catalogos = Catalogue::where('resource_id', $id)->get();
        foreach ($catalogos as $catalogo) {
            $catalogo->delete();
        }
        $recurso->delete();

        return response()->json([
            'data' => null,
            'mensaje' => 'OK'
        ], 200);
    }
}
