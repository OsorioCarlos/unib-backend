<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Resource;
use App\Models\User;

class FormulariosController extends Controller
{
    public function generarCartaCompriso(Request $request) {
        $estudianteData = $request->all();

        $pdf = Pdf::loadView('documentos.carta-compromiso', compact('estudianteData'));
        $pdf->save('carta_compromiso.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'carta_compromiso.pdf'
        ], 200);
    }

    public function generarVso001(Request $request) {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();

        $solicitudData = [
            'nombre_estudiante' => $usuario->nombre_completo,
            'carrera' => $usuario->student->carrera_id,
            'nivel' => $usuario->student->nivel_id,
            'area_practicas_solicitadas' => $practicaPreprofesional->area_practicas_solicitadas,
            'horas_practicas_solicitadas' => $practicaPreprofesional->horas_practicas_solicitadas,
            'razon_social' => $practicaPreprofesional->organization->razon_social,
            'representante_legal' => $practicaPreprofesional->organization->representante_legal,
            'area_dedicacion' => $practicaPreprofesional->organization->area_dedicacion,
            'representante_practica' => $practicaPreprofesional->internshipRepresentative->user->nombre,
            'direccion' => $practicaPreprofesional->organization->direccion,
            'telefono' => $practicaPreprofesional->organization->telefono,
            'email' => $practicaPreprofesional->organization->email,
            'identificacion_estudiante' => $usuario->identificacion,
            'fecha_texto' => 'fecha nueva',
            'nombre_director' => 'nombre director',
            'identificacion_director' => 'identificacion director',
        ];
        $pdf = Pdf::loadView('documentos.vso-001-solicitud-estudiante', compact('solicitudData'));
        $pdf->save('solicitud.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'solicitud.pdf'
        ], 200);
    }


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
