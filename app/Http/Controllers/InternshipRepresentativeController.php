<?php

namespace App\Http\Controllers;

use App\Models\InternshipRepresentative;
use App\Models\Organization;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InternshipRepresentativeController extends Controller
{
    public function buscarRepresentatePracticas(string $cedula) {
        $recursoTiposUsuario = Resource::where('nombre', 'TIPOS USUARIO')->first();
        $tipoUsuarioId = '';
        foreach ($recursoTiposUsuario->catalogues as $catalogo) {
            if ($catalogo->nombre == 'REPRESENTANTE PRACTICAS') {
                $tipoUsuarioId = $catalogo->id;
                break;
            }
        }

        $usuario = User::where('tipo_id', $tipoUsuarioId)
            ->where('identificacion', $cedula)
            ->first();
        $usuario->internshipRepresentative->organization;

        return response()->json([
            'data' => $usuario,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function completarInformacionBasica(Request $request) {
        // Validar la solicitud
        $request->validate([
            'representante.funcionRepresentante' => 'required|string',
            'representante.telefono' => 'required|string'
        ]);
        $user = Auth::user();
        $representantePracticas = $user->internshipRepresentative;
        if($representantePracticas == null) {
            return response()->json([
                'mensaje' => 'No se encontro el representante de practicas'
            ], Response::HTTP_NOT_FOUND);
        }

        $representantePracticas->funcion_laboral = $request->input('representante.funcionRepresentante');
        $representantePracticas->telefono = $request->input('representante.telefono');
        $representantePracticas->save();

        return response()->json([
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerInformacionRepresentantePracticas() {
        $user = Auth::user();

        $representantePracticas = $user->internshipRepresentative;
        if($representantePracticas == null) {
            return response()->json([
                'mensaje' => 'No se encontro el representante de practicas'
            ], Response::HTTP_NOT_FOUND);
        }

       if($representantePracticas->funcion_laboral == null || $representantePracticas->telefono == null) {
           return response()->json([
               'mensaje' => 'Debes completar tu información de contacto'
           ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => true,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

}
