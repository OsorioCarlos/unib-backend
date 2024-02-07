<?php

namespace App\Http\Controllers;

use App\Models\InternshipRepresentative;
use App\Models\Organization;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
}
