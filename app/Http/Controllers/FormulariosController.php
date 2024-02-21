<?php

namespace App\Http\Controllers;

use App\Models\CareerDirector;
use App\Models\Grade;
use App\Models\GradingCriteria;
use App\Models\Organization;
use App\Models\Resource;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormulariosController extends Controller
{
    public function generarCartaCompriso(Request $request)
    {
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
        $pdf->save('CARTA-001.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'CARTA-001.pdf'
        ], 200);
    }

    public function generarVso001(Request $request)
    {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();
        $director = CareerDirector::where('carrera_id', $usuario->student->carrera_id)->first();
        if ($director == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado el director de carrera'
            ], 404);
        }
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
            'nombre_director' => $director->user->nombre_completo,
            'identificacion_director' => $director->user->identificacion,
        ];
        $pdf = Pdf::loadView('documentos.vso-001-solicitud-estudiante', compact('solicitudData'));
        $pdf->save('VSO-001.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'VSO-001.pdf'
        ], 200);
    }

    public function generarVso002(Request $request)
    {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();
        $organizacion = $practicaPreprofesional->organization;
        $director = CareerDirector::where('carrera_id', $usuario->student->carrera_id)->first();
        if ($director == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado el director de carrera'
            ], 404);
        }
        $solicitudData = [
            'razon_social' => $organizacion->razon_social,
            'representante_legal' => $organizacion->representante_legal,
            'area_dedicacion' => $organizacion->area_dedicacion,
            'telefono' => $organizacion->telefono,
            'direccion' => $organizacion->direccion,
            'dias_habiles' => $organizacion->dias_laborables,
            'horario' => $organizacion->horario,
            'nombre_representante' => $practicaPreprofesional->internshipRepresentative->user->nombre_completo,
            'funcion_representante' => $practicaPreprofesional->internshipRepresentative->funcion_laboral,
            'telefono_representante' => $practicaPreprofesional->internshipRepresentative->telefono,
            'email_representante' => $practicaPreprofesional->internshipRepresentative->user->email,
            'nombre_estudiante' => $usuario->nombre_completo,
            'area_practica' => $practicaPreprofesional->area_practicas_solicitadas,
            'objetivos' => $practicaPreprofesional->objetivos_practicas,
            'tareas' => $practicaPreprofesional->tareas,
            'fecha_inicio' => $practicaPreprofesional->fecha_inicio,
            'fecha_fin' => $practicaPreprofesional->fecha_fin,
            'dias_laborables' => $practicaPreprofesional->dias_laborables,
            'horario_practicas' => $practicaPreprofesional->horario,
            'identificacion_representante' => $practicaPreprofesional->internshipRepresentative->user->identificacion,
            'fecha_compromiso_organizacion_texto' => Carbon::parse($practicaPreprofesional->empresa_compromiso_fecha)->format('d/m/Y'),
            'nombre_director' => $director->user->nombre_completo,
            'carrera' => $usuario->student->carreraCatalogo->nombre,
            'identificacion_director' => $director->user->identificacion,
            'fecha_director_texto' => Carbon::parse($practicaPreprofesional->empresa_compromiso_fecha)->format('d/m/Y')
        ];
        $pdf = Pdf::loadView('documentos.vso-002-compromiso-recepcion', compact('solicitudData'));
        $pdf->save('VSO-002.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'VSO-002.pdf'
        ], 200);
    }

    public function generarVso003(Request $request)
    {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();
        $director = CareerDirector::where('carrera_id', $usuario->student->carrera_id)->first();
        if ($director == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado el director de carrera'
            ], 404);
        }
        $grade = $practicaPreprofesional->grades->where('user_id', $director->user_id)->first();
        if ($grade == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado la evaluación'
            ], 404);
        }

        $gradeOrganizacion = $practicaPreprofesional->grades->where('user_id', $practicaPreprofesional->internshipRepresentative->user_id)->first();
        if ($gradeOrganizacion == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado la evaluación organizacion'
            ], 404);
        }

        $criterios = $grade->gradingCriterias;
        if ($criterios == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se han encontrado los criterios de evaluación'
            ], 404);
        }
        $calificaciones = [];
        foreach ($criterios as $criterio) {
            array_push($calificaciones, [
                'criterio' => $criterio->criterioCatalogo->nombre,
                'calificacion' => $criterio->calificacion
            ]);
        }
        $solicitudData = [
            'razon_social' => $practicaPreprofesional->organization->razon_social,
            'representante_legal' => $practicaPreprofesional->organization->representante_legal,
            'area_dedicacion' => $practicaPreprofesional->organization->area_dedicacion,
            'nombre_representante' => $practicaPreprofesional->internshipRepresentative->user->nombre_completo,
            'area_practica' => $practicaPreprofesional->area_practicas_solicitadas,
            'nombre_estudiante' => $usuario->nombre_completo,
            'carrera' => $usuario->student->carreraCatalogo->nombre,
            'nivel' => $usuario->student->nivelCatalogo->nombre,
            'area_practicas_solicitadas' => $practicaPreprofesional->area_practicas_solicitadas,
            'horas_practicas_solicitadas' => $practicaPreprofesional->horas_practicas_solicitadas,
            'fecha_inicio' => $practicaPreprofesional->fecha_inicio,
            'fecha_finalizacion' => $practicaPreprofesional->fecha_fin,
            'asistencia' => $grade->porcentaje_asistencia,
            'observaciones' => $grade->observaciones,
            'recomendaciones' => $grade->recomendaciones,
            'identificacion_representante' => $practicaPreprofesional->internshipRepresentative->user->identificacion,
            'fecha_evaluacion_director_texto' => Carbon::parse($grade->created_at)->format('d/m/Y'),
            'nota_promedio' => $grade->nota_promedio,
            'nota_organizacion' => $gradeOrganizacion->nota_promedio,
            'promedio_final' => $practicaPreprofesional->nota_final,
            'horas_aprobadas' => 'PENDIENTE LLENAR',
            'nombre_director' => $director->user->nombre_completo,
            'identificacion_director' => $director->user->identificacion,
            'calificaciones' => $calificaciones
        ];
        $pdf = Pdf::loadView('documentos.vso-003-seguimiento-director', compact('solicitudData'));
        $pdf->save('VSO-003.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'VSO-003.pdf'
        ], 200);
    }

    public function generarVso004(Request $request)
    {
        $identificacionEstudiante = $request->input('identificacionEstudiante');
        $usuario = User::where('identificacion', $identificacionEstudiante)->first();
        $practicaPreprofesional = $usuario->student->preprofessionalPractices->first();
        $grade = $practicaPreprofesional->grades->where('user_id', $practicaPreprofesional->internshipRepresentative->user_id)->first();
        if ($grade == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado la evaluación'
            ], 404);
        }
        $criterios = $grade->gradingCriterias;
        if ($criterios == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se han encontrado los criterios de evaluación'
            ], 404);
        }
        $calificaciones = [];
        foreach ($criterios as $criterio) {
            array_push($calificaciones, [
                'criterio' => $criterio->criterioCatalogo->nombre,
                'calificacion' => $criterio->calificacion
            ]);
        }
        $solicitudData = [
            'razon_social' => $practicaPreprofesional->organization->razon_social,
            'representante_legal' => $practicaPreprofesional->organization->representante_legal,
            'area_dedicacion' => $practicaPreprofesional->organization->area_dedicacion,
            'nombre_representante' => $practicaPreprofesional->internshipRepresentative->user->nombre_completo,
            'area_practica' => $practicaPreprofesional->area_practicas_solicitadas,
            'nombre_estudiante' => $usuario->nombre_completo,
            'carrera' => $usuario->student->carreraCatalogo->nombre,
            'nivel' => $usuario->student->nivelCatalogo->nombre,
            'area_practicas_solicitadas' => $practicaPreprofesional->area_practicas_solicitadas,
            'horas_practicas_solicitadas' => $practicaPreprofesional->horas_practicas_solicitadas,
            'fecha_inicio' => $practicaPreprofesional->fecha_inicio,
            'fecha_finalizacion' => $practicaPreprofesional->fecha_fin,
            'asistencia' => $grade->porcentaje_asistencia,
            'observaciones' => $grade->observaciones,
            'recomendaciones' => $grade->recomendaciones,
            'identificacion_representante' => $practicaPreprofesional->internshipRepresentative->user->identificacion,
            'fecha_evaluacion_representante_texto' => Carbon::parse($grade->created_at)->format('d/m/Y'),
            'nota_promedio' => $grade->nota_promedio,
            'calificaciones' => $calificaciones
        ];
        $pdf = Pdf::loadView('documentos.vso-004-seguimiento-representante', compact('solicitudData'));
        $pdf->save('VSO-004.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'VSO-004.pdf'
        ], 200);
    }

    public function generarVso005(Request $request)
    {
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
        $pdf->save('VSO-005.pdf');

        return response()->json([
            'mensaje' => 'OK',
            'data' => 'VSO-005.pdf'
        ], 200);
    }

    public function obtenerInformacionFormularioVSO003(string $cedula)
    {
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

    public function obtenerInformacionFormularioVSO005(string $cedula)
    {
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
