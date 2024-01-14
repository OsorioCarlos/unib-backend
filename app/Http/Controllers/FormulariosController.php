<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Resource;
use App\Models\User;

class FormulariosController extends Controller
{
    public function obtenerInformacionFormularioVSO003(string $cedula) {
        $recursoTiposUsuario = Resource::where('nombre', 'TIPOS USUARIO')->first();
        $tipoUsuarioId = '';
        foreach ($recursoTiposUsuario->catalogues as $catalogo) {
            if ($catalogo->nombre == 'ESTUDIANTE') {
                $tipoUsuarioId = $catalogo->id;
                break;
            }
        }

        $usuario = User::where('tipo_id', $tipoUsuarioId)
            ->where('identificacion', $cedula)
            ->first();
        $practicasPreprofesionales = $usuario->student->preprofessionalPractices;
        $practicaPreprofesional = collect($practicasPreprofesionales)
            ->where('estado_id', 3)
            ->sortByDesc('created_at')
            ->first();
        $practicaPreprofesional->student->user;
        $practicaPreprofesional->organization;
        $practicaPreprofesional->internshipRepresentative->user;
        $practicaPreprofesional->grades;

        return response()->json([
            'mensaje' => 'OK',
            'data' => $practicaPreprofesional
        ], 200);
    }

    public function obtenerInformacionFormularioVSO004(string $ruc) {
        $organizacion = Organization::where('ruc', $ruc)
            ->first();
        $practicasPreprofesionales = $organizacion->preprofessionalPractices;
        $practicaPreprofesional = collect($practicasPreprofesionales)
            ->where('estado_id', 3)
            ->sortByDesc('created_at')
            ->first();
        $practicaPreprofesional->organization;
        $practicaPreprofesional->internshipRepresentative->user;
        $practicaPreprofesional->student->user;

        return response()->json([
            'mensaje' => 'OK',
            'data' => $practicaPreprofesional
        ], 200);
    }

    public function obtenerInformacionFormularioVSO005(string $cedula) {
        $recursoTiposUsuario = Resource::where('nombre', 'TIPOS USUARIO')->first();
        $tipoUsuarioId = '';
        foreach ($recursoTiposUsuario->catalogues as $catalogo) {
            if ($catalogo->nombre == 'ESTUDIANTE') {
                $tipoUsuarioId = $catalogo->id;
                break;
            }
        }

        $usuario = User::where('tipo_id', $tipoUsuarioId)
            ->where('identificacion', $cedula)
            ->first();
        $practicasPreprofesionales = $usuario->student->preprofessionalPractices;
        $practicaPreprofesional = collect($practicasPreprofesionales)
            ->where('estado_id', 3)
            ->sortByDesc('created_at')
            ->first();
        $practicaPreprofesional->student->user;

        return response()->json([
            'mensaje' => 'OK',
            'data' => $practicaPreprofesional
        ], 200);
    }
}
