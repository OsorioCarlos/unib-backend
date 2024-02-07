<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Organization;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizaciones = Organization::all();

        return response()->json([
            'organizaciones' => $organizaciones,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizacionData = $request->get('organizacion');

        $organizacion = new Organization();
        $organizacion->ruc = $organizacionData['ruc'];
        $organizacion->razon_social = $organizacionData['razon_social'];
        $organizacion->representante_legal = $organizacionData['representante_legal'];
        $organizacion->direccion = $organizacionData['direccion'];
        $organizacion->area_dedicacion = $organizacionData['area_dedicacion'];
        $organizacion->telefono = $organizacionData['telefono'];
        $organizacion->horario = $organizacionData['horario'];
        $organizacion->dias_laborables = $organizacionData['dias_laborables'];
        $organizacion->save();

        return response()->json([
            'organizacion' => $organizacion,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organizacion = Organization::find($id);

        return response()->json([
            'organizacion' => $organizacion,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $organizacionData = $request->get('organizacion');

        $organizacion = Organization::find($id);
        $organizacion->ruc = $organizacionData['ruc'];
        $organizacion->razon_social = $organizacionData['razon_social'];
        $organizacion->direccion = $organizacionData['direccion'];
        $organizacion->area_dedicacion = $organizacionData['area_dedicacion'];
        $organizacion->telefono = $organizacionData['telefono'];
        $organizacion->horario = $organizacionData['horario'];
        $organizacion->dias_laborables = $organizacionData['dias_laborables'];
        $organizacion->save();

        return response()->json([
            'organizacion' => $organizacion,
            'mensaje' => 'OK'
        ], 200);
    }
}
