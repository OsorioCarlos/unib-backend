<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InternshipRepresentativeController extends Controller
{
    public function buscarRepresentatePracticas(string $cedula)
    {
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

    public function completarInformacionBasica(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'representante.funcionRepresentante' => 'required|string',
            'representante.telefono' => 'required|string'
        ]);
        $user = Auth::user();
        $representantePracticas = $user->internshipRepresentative;
        if ($representantePracticas == null) {
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

    public function obtenerInformacionRepresentantePracticas()
    {
        $user = Auth::user();

        $representantePracticas = $user->internshipRepresentative;
        if ($representantePracticas == null) {
            return response()->json([
                'mensaje' => 'No se encontro el representante de practicas'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($representantePracticas->funcion_laboral == null || $representantePracticas->telefono == null) {
            return response()->json([
                'mensaje' => 'Debes completar tu información de contacto'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => true,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerEstudiantes()
    {
        $user = Auth::user();
        $representantePracticas = $user->internshipRepresentative;
        $practicasPreprofesionales = $representantePracticas->preprofessionalPractices;
        $estudiantes = [];
        foreach ($practicasPreprofesionales as $practica) {
            if ($practica->empresa_compromiso == null || $practica->empresa_compromiso_fecha == null) {
                $estudiante = $practica->student->user;
                array_push($estudiantes, $estudiante);
            }
        }
        return response()->json([
            'data' => $estudiantes,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerCompromisoRecepcion(string $identificacionEstudiante)
    {
        $user = User::where('identificacion', $identificacionEstudiante)->first();
        if ($user == null) {
            return response()->json([
                'mensaje' => 'No se encontro el estudiante'
            ], Response::HTTP_NOT_FOUND);
        }
        $practica = $user->student->preprofessionalPractices->first();
        if ($practica == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene una practica asignada'
            ], Response::HTTP_NOT_FOUND);
        }
        $representante = $practica->internshipRepresentative;
        $organizacion = $representante->organization;

        $respuesta = [
            'razon_social' => $organizacion->razon_social,
            'representante_legal' => $organizacion->representante_legal,
            'area_dedicacion' => $organizacion->area_dedicacion,
            'telefono' => $organizacion->telefono,
            'direccion' => $organizacion->direccion,
            'dias_habiles' => $organizacion->dias_laborables,
            'horario' => $organizacion->horario,
            'nombre_representante' => $representante->user->nombre_completo,
            'funcion' => $representante->funcion_laboral,
            'telefono_representante' => $representante->telefono,
            'email_representante' => $representante->user->email,
            'nombre_estudiante' => $user->nombre_completo,
            'area_estudiante' => $practica->area_practicas_solicitadas,
        ];

        return response()->json([
            'data' => $respuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function recibirEstudiante(Request $request)
    {
        $request->validate([
            'compromisoRecepcion.identificacionEstudiante' => 'required|string',
            'compromisoRecepcion.objetivos' => 'required|string',
            'compromisoRecepcion.tareas' => 'required|string',
            'compromisoRecepcion.fechaInicio' => 'required|string',
            'compromisoRecepcion.fechaFin' => 'required|string',
            'compromisoRecepcion.horario' => 'required|string',
            'compromisoRecepcion.diasLaborables' => 'required|string',
            'compromisoRecepcion.aceptarCompromiso' => 'required',
        ]);
        $user = User::where('identificacion', $request->input('compromisoRecepcion.identificacionEstudiante'))->first();
        if ($user == null) {
            return response()->json([
                'mensaje' => 'No se encontro el estudiante'
            ], Response::HTTP_NOT_FOUND);
        }
        $practica = $user->student->preprofessionalPractices->first();
        if ($practica == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene una practica asignada'
            ], Response::HTTP_NOT_FOUND);
        }
        $practica->objetivos_practicas = $request->input('compromisoRecepcion.objetivos');
        $practica->tareas = $request->input('compromisoRecepcion.tareas');
        $practica->fecha_inicio = $request->input('compromisoRecepcion.fechaInicio');
        $practica->fecha_fin = $request->input('compromisoRecepcion.fechaFin');
        $practica->dias_laborables = $request->input('compromisoRecepcion.diasLaborables');
        $practica->horario = $request->input('compromisoRecepcion.horario');
        $practica->empresa_compromiso = $request->input('compromisoRecepcion.aceptarCompromiso');
        $practica->empresa_compromiso_fecha = Carbon::now()->format('Y-m-d');
        $practica->save();

        return response()->json([
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerEvaluacionesPendientes()
    {
        $user = Auth::user();
        $representantePracticas = $user->internshipRepresentative;
        $practicasPreprofesionales = $representantePracticas->preprofessionalPractices;
        $estudiantes = [];


        foreach ($practicasPreprofesionales as $practica) {
            if ($practica->grades->count() == 0) {
                $estudiante = $practica->student->user;
                array_push($estudiantes, $estudiante);
            } else {
                $practica->grades->map(function ($grade) use (&$practica, &$estudiantes) {
                    if ($grade->user->tipoCatalogo->nombre != 'REPRESENTANTE PRÁCTICAS') {
                        $estudiante = $practica->student->user;
                        array_push($estudiantes, $estudiante);
                    }
                });
            }

        }
        return response()->json([
            'data' => $estudiantes,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerInformacionEvaluacion(string $id)
    {
        $user = User::where('identificacion', $id)->first();
        if ($user == null) {
            return response()->json([
                'mensaje' => 'No se encontro el estudiante'
            ], Response::HTTP_NOT_FOUND);
        }
        $practica = $user->student->preprofessionalPractices->first();
        if ($practica == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene una practica asignada'
            ], Response::HTTP_NOT_FOUND);
        }
        $respuesta = [
            'razon_social' => $practica->organization->razon_social,
            'representante_legal' => $practica->organization->representante_legal,
            'area_dedicacion' => $practica->organization->area_dedicacion,
            'representante' => $practica->internshipRepresentative->user->nombre_completo,
            'nombre_estudiante' => $user->nombre_completo,
            'escuela' => $user->student->carreraCatalogo->nombre,
            'nivel' => $user->student->nivelCatalogo->nombre,
            'area_practicas' => $practica->area_practicas_solicitadas,
            'horas_practicas' => $practica->horas_practicas_solicitadas,
            'fecha_inicio' => $practica->fecha_inicio,
            'fecha_fin' => $practica->fecha_fin
        ];

        return response()->json([
            'data' => $respuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);

    }
}
