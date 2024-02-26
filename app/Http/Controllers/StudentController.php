<?php

namespace App\Http\Controllers;

use App\Models\CareerDirector;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class StudentController extends Controller
{
    public function generarCartaCompromiso(Request $request)
    {
        $request->validate([
            'organizacion.nombreRazonSocial' => 'required|string'
        ]);

        $authUser = Auth::user();
        $director = CareerDirector::where('carrera_id', $authUser->student->carrera_id)->first();
        if ($director == null) {
            return response()->json([
                'mensaje' => 'No se ha encontrado un director para la carrera del estudiante'
            ], 404);
        }
        $practicaPreprofesional = $authUser->student->preprofessionalPractices()->firstOrNew();
        $practicaPreprofesional->organization_id = $request->input('organizacion.nombreRazonSocial');
        $practicaPreprofesional->career_director_id = $director->id;
        $practicaPreprofesional->save();

        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Carta de compromiso generada correctamente'
        ], 200);

    }

    public function obtenerInfoCompromiso()
    {
        $authUser = Auth::user();
        $razonSocial = '';
        if ($authUser->student->preprofessionalPractices->first() != null) {
            $razonSocial = $authUser->student->preprofessionalPractices->first()->organization->razon_social;
        }

        return response()->json([
            'data' => [
                'carrera' => $authUser->student->carreraCatalogo->nombre,
                'nombreCompleto' => $authUser->nombre_completo,
                'identificacion' => $authUser->identificacion,
                'semestre' => $authUser->student->nivelCatalogo->nombre,
                'razonSocial' => $razonSocial,
            ],
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function aceptarCompromisoBioseguridad()
    {
        $authUser = Auth::user();

        if ($authUser->student == null) {
            return response()->json([
                'mensaje' => 'El usuario no es estudiante'
            ], Response::HTTP_BAD_REQUEST);
        } else if ($authUser->student->preprofessionalPractices->first() == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        } else if ($authUser->student->preprofessionalPractices->first()->estudiante_carta_compromiso == 1) {
            return response()->json([
                'mensaje' => 'El estudiante ya acepto el compromiso de bioseguridad'
            ], Response::HTTP_BAD_REQUEST);
        }

        $practicaPreprofesional = $authUser->student->preprofessionalPractices->first();
        $practicaPreprofesional->estudiante_carta_compromiso = 1;
        $practicaPreprofesional->estudiante_carta_compromiso_fecha = Carbon::now()->format('Y-m-d');
        $practicaPreprofesional->save();

        return response()->json([
            'estado' => 'Carta de compromiso generada!',
            'mensaje' => 'Ya puedes solicitar tus prácticas preprofesionales'
        ], 200);
    }

    public function solicitarPracticas(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'practicaPreprofesional.areaPropuesta' => 'required|string',
            'practicaPreprofesional.horasSolicitadas' => 'required|integer',
            'organizacion.representante' => 'required|integer',
            'compromisoEstudiante.acepta' => 'required|boolean',
        ]);
        $student = Auth::user()->student;
        $practicaPreprofesional = $student->preprofessionalPractices->first();
        if ($practicaPreprofesional == null) {
            return response()->json([
                'mensaje' => 'El estudiante aun no acepta el compromiso de bioseguridad'
            ], Response::HTTP_BAD_REQUEST);
        }
        $practicaPreprofesional->internship_representative_id = $request->input('organizacion.representante');
        $practicaPreprofesional->horas_practicas_solicitadas = $request->input('practicaPreprofesional.horasSolicitadas');
        $practicaPreprofesional->area_practicas_solicitadas = $request->input('practicaPreprofesional.areaPropuesta');
        $practicaPreprofesional->estudiante_compromiso = $request->input('compromisoEstudiante.acepta');
        $practicaPreprofesional->estudiante_compromiso_fecha = Carbon::now()->format('Y-m-d');
        $practicaPreprofesional->save();

        Notification::route('mail', $practicaPreprofesional->internshipRepresentative->user->email)
            ->notify(new \App\Notifications\PracticasNotificacion(nombre: $practicaPreprofesional->internshipRepresentative->user->nombre_completo, estudiante: $student->user->nombre_completo, carrera: $student->carreraCatalogo->nombre));

        // Puedes devolver una respuesta o redirigir a otra página según tus necesidades
        return response()->json(['mensaje' => 'Se ha notificado la solitud a tu representante'], 200);
    }

    public function enviarInformeFinal(Request $request)
    {
        $user = Auth::user();
        $practicaPreprofesional = $user->student->preprofessionalPractices->first();
        if ($practicaPreprofesional == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }

        $requestData = $request->all();

        $practicaPreprofesional->cumplimiento_objetivos = strtoupper($requestData['informe']['cumplimiento_objetivos']);
        $practicaPreprofesional->beneficios = strtoupper($requestData['informe']['beneficios']);
        $practicaPreprofesional->aprendizajes = strtoupper($requestData['informe']['aprendizajes']);
        $practicaPreprofesional->desarrollo_personal = strtoupper($requestData['informe']['desarrollo_personal']);
        $practicaPreprofesional->comentarios = strtoupper($requestData['informe']['comentarios']);
        $practicaPreprofesional->recomendaciones = strtoupper($requestData['informe']['recomendaciones']);
        $practicaPreprofesional->fecha_informe_enviado = Carbon::now()->format('Y-m-d');
        $practicaPreprofesional->save();

        return response()->json([
            'mensaje' => 'OK',
            'data' => ''
        ], 200);
    }

    public function obtenerOrganizaciones()
    {
        $organizaciones = Organization::all();
        if ($organizaciones->isEmpty()) {
            return response()->json([
                'mensaje' => 'No hay organizaciones registradas'
            ], 404);
        }

        $organizaciones->map(function ($organizacion) {
            return [
                'id' => $organizacion->id,
                'razon_social' => $organizacion->razon_social
            ];
        });

        return response()->json([
            'data' => $organizaciones,
            'mensaje' => 'OK'
        ], 200);
    }
    public function consultarOrganizacionAsignada()
    {
        $user = Auth::user();
        $practicaPreprofesional = $user->student->preprofessionalPractices->first();
        if ($practicaPreprofesional == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'data' => $practicaPreprofesional->organization->with('internshipRepresentatives')->with('internshipRepresentatives.user')->get()->first(),
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    public function obtenerEstadosPracticasPreprofesionales()
    {
        $statusCartaCompromiso = 'Pendiente';
        $statusSolicitud = 'Pendiente';
        $statusInformeFinal = 'Pendiente';
        $compromisoRecepcion = 'Pendiente';
        $statusEvaluacionDirector = 'Pendiente';
        $statusEvaluacionRepresentante = 'Pendiente';

        $estudiante = Auth::user()->student;
        if ($estudiante == null || $estudiante->preprofessionalPractices->first() == null) {
            return response()->json([
                'mensaje' => 'Las prácticas pre profesionales son requisito obligatorio para obtener el grado o título profesional en la Universidad Iberoamericana del Ecuador, con una duración mínima de 240 horas. Su cumplimiento es condición previa para la obtención del título, incluyendo seguimiento, evaluación, presentación de informes y certificados. Estas acciones son parte integral del proceso de titulación y validan la adquisición de experiencias prácticas relevantes en el campo profesional del estudiante.'
            ], Response::HTTP_BAD_REQUEST);
        }
        $practicaPreprofesional = $estudiante->preprofessionalPractices->first();

        if ($practicaPreprofesional->estudiante_carta_compromiso == 1) {
            $statusCartaCompromiso = 'Completado';
        }
        if ($practicaPreprofesional->horas_practicas_solicitadas != null) {
            $statusSolicitud = 'Completado';
        }
        if ($practicaPreprofesional->fecha_informe_enviado != null) {
            $statusInformeFinal = 'Completado';
        }
        if ($practicaPreprofesional->empresa_compromiso != null) {
            $compromisoRecepcion = 'Completado';
        }
        $practicaPreprofesional->grades->map(function ($grade) use (&$statusEvaluacionDirector, &$statusEvaluacionRepresentante) {
            if ($grade->user->tipoCatalogo->nombre == 'DIRECTOR DE CARRERA') {
                $statusEvaluacionDirector = 'Completado';
            }
            if ($grade->user->tipoCatalogo->nombre == 'REPRESENTANTE PRÁCTICAS') {
                $statusEvaluacionRepresentante = 'Completado';
            }
        });

        return response()->json([
            'data' => [
                'cartaCompromiso' => $statusCartaCompromiso,
                'solicitud' => $statusSolicitud,
                'compromisoRecepcion' => $compromisoRecepcion,
                'evaluacionDirector' => $statusEvaluacionDirector,
                'evaluacionRepresentante' => $statusEvaluacionRepresentante,
                'informeFinal' => $statusInformeFinal
            ],
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    function obtenerRepresentantes()
    {
        $estudiante = Auth::user()->student;
        if ($estudiante == null || $estudiante->preprofessionalPractices->first() == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }

        $practicaPreprofesional = $estudiante->preprofessionalPractices->first();
        $representantes = $practicaPreprofesional->organization->internshipRepresentatives;
        if ($representantes->isEmpty()) {
            return response()->json([
                'mensaje' => 'No hay representantes registrados'
            ], 404);
        }
        $representantes->map(function ($representante) {
            return [
                'id' => $representante->id,
                'nombre' => $representante->user->nombre_completo
            ];
        });

        return response()->json([
            'data' => $representantes,
            'mensaje' => 'OK'
        ], 200);
    }

    function obtenerEstudiante()
    {
        $estudiante = Auth::user()->student;

        $respuesta = [
            'identificacion' => $estudiante->user->identificacion,
            'nombre' => $estudiante->user->nombre_completo,
            'escuela' => $estudiante->carreraCatalogo->nombre,
            'nivel' => $estudiante->nivelCatalogo->nombre
        ];
        return response()->json([
            'data' => $respuesta,
            'mensaje' => 'OK'
        ], 200);
    }

    function obtenerInformeEstudiante()
    {
        $usuario = Auth::user();
        $estudiante = $usuario->student;
        $practicaPreprofesional = $estudiante->preprofessionalPractices->first();
        if ($practicaPreprofesional == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }
        $respuesta = [
            'identificacion' => $usuario->identificacion,
            'nombre_estudiante' => $usuario->nombre_completo,
            'carrera' => $estudiante->carreraCatalogo->nombre,
            'nivel' => $estudiante->nivelCatalogo->nombre,
            'organizacion' => $practicaPreprofesional->organization->razon_social,
            'numero_horas_realizada' => $practicaPreprofesional->horas_practicas_realizadas,
            'fecha_inicio' => $practicaPreprofesional->fecha_inicio,
            'fecha_fin' => $practicaPreprofesional->fecha_fin
        ];
        return response()->json([
            'data' => $respuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    function obtenerInfoEstudiante($identificacionEstudiante){
        $user = User::where('identificacion', $identificacionEstudiante)->first();
        if ($user == null) {
            return response()->json([
                'mensaje' => 'El estudiante no existe'
            ], Response::HTTP_BAD_REQUEST);
        }

        $student = $user->student;
        if ($student == null) {
            return response()->json([
                'mensaje' => 'El usuario no es estudiante'
            ], Response::HTTP_BAD_REQUEST);
        }

        $practicaPreprofesional = $student->preprofessionalPractices->first();
        if ($practicaPreprofesional == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }

        $director = CareerDirector::where('carrera_id', $user->student->carrera_id)->first();
        if ($director == null) {
            return response()->json([
                'mensaje' => 'ERROR',
                'data' => 'No se ha encontrado el director de carrera'
            ], 404);
        }

        $gradeDirector = $practicaPreprofesional->grades()->where('user_id', $director->user_id)->first();
        $calificacionesDirector = [];
        if ($gradeDirector != null) {
            $criterios = $gradeDirector->gradingCriterias;
            if ($criterios == null) {
                return response()->json([
                    'mensaje' => 'ERROR',
                    'data' => 'No se han encontrado los criterios de evaluación'
                ], 404);
            }
            foreach ($criterios as $criterio) {
                array_push($calificacionesDirector, [
                    'criterio' => $criterio->criterioCatalogo->nombre,
                    'calificacion' => $criterio->calificacion
                ]);
            }

        }

        $gradeOrganizacion = $practicaPreprofesional->grades()->where('user_id', $practicaPreprofesional->internshipRepresentative->user_id)->first();
        $calificacionesOrganizacion = [];
        if ($gradeOrganizacion != null) {
            $criteriosOrganizacion = $gradeOrganizacion->gradingCriterias;
            if ($criteriosOrganizacion == null) {
                return response()->json([
                    'mensaje' => 'ERROR',
                    'data' => 'No se han encontrado los criterios de evaluación'
                ], 404);
            }
            foreach ($criteriosOrganizacion as $criterio) {
                array_push($calificacionesOrganizacion, [
                    'criterio' => $criterio->criterioCatalogo->nombre,
                    'calificacion' => $criterio->calificacion
                ]);
            }
        }

        $respuesta = [
          'estudiante' =>[
              'nombre' => $user->nombre_completo== null? '': $user->nombre_completo,
              'identificacion' => $user->identificacion== null? '': $user->identificacion,
              'email'=> $user->email== null? '': $user->email,
              'carrera' => $user->student->carreraCatalogo->nombre== null? '': $user->student->carreraCatalogo->nombre,
              'nivel' => $user->student->nivelCatalogo->nombre== null? '': $user->student->nivelCatalogo->nombre,
              'areaPropuesta' => $practicaPreprofesional->area_practicas_solicitadas== null? '': $practicaPreprofesional->area_practicas_solicitadas,
              'horasSolicitadas' => $practicaPreprofesional->horas_practicas_solicitadas== null? '': $practicaPreprofesional->horas_practicas_solicitadas,
          ],
            'organizacion'=>[
                'razonSocial'=> $practicaPreprofesional->organization->razon_social== null? '': $practicaPreprofesional->organization->razon_social,
                'representanteLegal'=> $practicaPreprofesional->organization->representante_legal== null? '': $practicaPreprofesional->organization->representante_legal,
                'areaDedicacion'=> $practicaPreprofesional->organization->area_dedicacion== null? '': $practicaPreprofesional->organization->area_dedicacion,
                'direccion'=> $practicaPreprofesional->organization->direccion== null? '': $practicaPreprofesional->organization->direccion,
                'telefono'=> $practicaPreprofesional->organization->telefono== null? '': $practicaPreprofesional->organization->telefono,
                'email'=> $practicaPreprofesional->organization->email== null? '': $practicaPreprofesional->organization->email,
                'diasHabiles'=> $practicaPreprofesional->organization->dias_laborables== null? '': $practicaPreprofesional->organization->dias_laborables,
                'horario'=> $practicaPreprofesional->organization->horario== null? '': $practicaPreprofesional->organization->horario
            ],
            'representante'=>[
                'nombre'=> $practicaPreprofesional->internshipRepresentative->user->nombre_completo== null? '': $practicaPreprofesional->internshipRepresentative->user->nombre_completo,
                'funcion'=> $practicaPreprofesional->internshipRepresentative->funcion_laboral== null? '': $practicaPreprofesional->internshipRepresentative->funcion_laboral,
                'identificacion'=> $practicaPreprofesional->internshipRepresentative->user->identificacion== null? '': $practicaPreprofesional->internshipRepresentative->user->identificacion,
                'email'=> $practicaPreprofesional->internshipRepresentative->user->email== null? '': $practicaPreprofesional->internshipRepresentative->user->email,
                'telefono'=> $practicaPreprofesional->internshipRepresentative->telefono== null? '': $practicaPreprofesional->internshipRepresentative->telefono,
            ],
            'practica'=>[
                'areaPractica'=> $practicaPreprofesional->area_practicas_solicitadas== null? '': $practicaPreprofesional->area_practicas_solicitadas,
                'objetivos'=> $practicaPreprofesional->objetivos_practicas== null? '': $practicaPreprofesional->objetivos_practicas,
                'tareas'=> $practicaPreprofesional->tareas== null? '': $practicaPreprofesional->tareas,
                'fechaInicio'=> $practicaPreprofesional->fecha_inicio== null? '': $practicaPreprofesional->fecha_inicio,
                'fechaFin'=> $practicaPreprofesional->fecha_fin== null? '': $practicaPreprofesional->fecha_fin,
                'diasLaborables'=> $practicaPreprofesional->dias_laborables== null? '': $practicaPreprofesional->dias_laborables,
                'horario'=> $practicaPreprofesional->horario== null? '': $practicaPreprofesional->horario,
            ],
            'informe'=>[
                'cumplimientosObjetivos'=> $practicaPreprofesional->cumplimiento_objetivos== null? '': $practicaPreprofesional->cumplimiento_objetivos,
                'beneficios'=> $practicaPreprofesional->beneficios== null? '': $practicaPreprofesional->beneficios,
                'aprendizajes'=> $practicaPreprofesional->aprendizajes== null? '': $practicaPreprofesional->aprendizajes,
                'desarrolloPersonal'=> $practicaPreprofesional->desarrollo_personal== null? '': $practicaPreprofesional->desarrollo_personal,
                'comentarios'=> $practicaPreprofesional->comentarios== null? '': $practicaPreprofesional->comentarios,
                'recomendaciones'=> $practicaPreprofesional->recomendaciones== null? '': $practicaPreprofesional->recomendaciones,
            ],
            'valoracion'=>[
                'organizacion'=>[
                    'calificaciones'=> $calificacionesOrganizacion== null? '': $calificacionesOrganizacion,
                    'asistencia'=> $gradeOrganizacion == null? '': $gradeOrganizacion->porcentaje_asistencia,
                    'observaciones'=> $gradeOrganizacion == null? '': $gradeOrganizacion->observaciones,
                    'recomendaciones'=> $gradeOrganizacion == null? '': $gradeOrganizacion->recomendaciones,
                    'nota'=> $gradeOrganizacion == null? '': $gradeOrganizacion->nota_promedio
                ],
                'director'=>[
                    'calificaciones'=> $calificacionesDirector == null? '': $calificacionesDirector,
                    'asistencia'=> $gradeDirector== null? '': $gradeDirector->porcentaje_asistencia,
                    'observaciones'=> $gradeDirector == null? '': $gradeDirector->observaciones,
                    'nota'=> $gradeDirector== null? '': $gradeDirector->nota_promedio
                ],
                'promedio'=>[
                    'horasAprobadas'=> $practicaPreprofesional->horas_practicas_realizadas== null? '': $practicaPreprofesional->horas_practicas_realizadas,
                    'promedio'=> $practicaPreprofesional->nota_final== null? '': $practicaPreprofesional->nota_final,
                    'asistencia'=> $practicaPreprofesional->asistencia== null? '': $practicaPreprofesional->asistencia
                ]
            ]
        ];
        return response()->json([
            'data' => $respuesta,
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }
}
