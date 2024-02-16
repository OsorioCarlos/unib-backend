<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Resource;
use App\Models\User;

class FormulariosController extends Controller
{
    public function generarCartaCompriso(Request $request) {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();

        $estudianteData = [
            'carrera' => $usuario->student->carreraCatalogo->nombre,
            'nombre_estudiante' => $usuario->nombre_completo,
            'cedula_estudiante' => $usuario->identificacion,
            'semestre' => $usuario->student->nivelCatalogo->nombre,
            'empresa' => $practicaPreprofesional->organization->razon_social,
            'fecha_texto' => Carbon::parse($practicaPreprofesional->estudiante_carta_compromiso_fecha)->format('d/m/Y')
        ];

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
            'carrera' => $usuario->student->carreraCatalogo->nombre,
            'nivel' => $usuario->student->nivelCatalogo->nombre,
            'area_practicas_solicitadas' => $practicaPreprofesional->area_practicas_solicitadas,
            'horas_practicas_solicitadas' => $practicaPreprofesional->horas_practicas_solicitadas,
            'razon_social' => $practicaPreprofesional->organization->razon_social,
            'representante_legal' => $practicaPreprofesional->organization->representante_legal,
            'area_dedicacion' => $practicaPreprofesional->organization->area_dedicacion,
            'representante_practica' => $practicaPreprofesional->internshipRepresentative->user->nombre_completo,
            'direccion' => $practicaPreprofesional->organization->direccion,
            'telefono' => $practicaPreprofesional->organization->telefono,
            'email' => $practicaPreprofesional->organization->email,
            'identificacion_estudiante' => $usuario->identificacion,
            'fecha_texto' => Carbon::parse($practicaPreprofesional->estudiante_compromiso_fecha)->format('d/m/Y'),
            'nombre_director' => 'PENDIENTE LLENAR',
            'identificacion_director' => 'PENDIENTE LLENAR',
        ];
        $pdf = Pdf::loadView('documentos.vso-001-solicitud-estudiante', compact('solicitudData'));
        $pdf->save('solicitud.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'solicitud.pdf'
        ], 200);
    }

    public function generarVso005(Request $request) {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();

        $solicitudData = [
            'nombre_estudiante' => $usuario->nombre_completo,
            'carrera' => $usuario->student->carreraCatalogo->nombre,
            'nivel' => $usuario->student->nivelCatalogo->nombre,
            'razon_social' => $practicaPreprofesional->organization->razon_social,
            'horas_practicas_realizadas' => $practicaPreprofesional->horas_practicas_realizadas,
            'fecha_inicio' => $practicaPreprofesional->fecha_inicio,
            'fecha_fin' => $practicaPreprofesional->fecha_fin,
            'objetivos_practica' => $practicaPreprofesional->cumplimiento_objetivos,
            'beneficios_practica' => $practicaPreprofesional->beneficios,
            'apredizajes_practica' => $practicaPreprofesional->aprendizajes,
            'incidencia_practica' => $practicaPreprofesional->desarrollo_personal,
            'comentarios_practica' => $practicaPreprofesional->comentarios,
            'recomendaciones_practica' => $practicaPreprofesional->recomendaciones,
            'fecha_texto' => Carbon::parse($practicaPreprofesional->fecha_informe_enviado)->format('d/m/Y'),
            'cedula_estudiante' => $usuario->identificacion
        ];
        $pdf = Pdf::loadView('documentos.vso-005-informe-estudiante', compact('solicitudData'));
        $pdf->save('informe.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'informe.pdf'
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
