<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Organization;
use App\Models\PreProfessionalPractice;
use App\Models\Resource;
use App\Models\Student;
use App\Models\User;
use App\Validations\StudentValidator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class StudentController extends Controller
{
     public function obtenerInfoCompromiso() {
        $authUser = Auth::user();
        $usuario = User::where('identificacion', $authUser->identificacion)->first();
        if($usuario->student == null){
            return response()->json([
                'mensaje' => 'No existe estudiante creado'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'data' => [
                'carrera' => $usuario->student->carreraCatalogo->nombre,
                'nombreCompleto' => $usuario->nombre_completo,
                'identificacion' => $authUser->identificacion,
                'semestre' => $usuario->student->nivelCatalogo->nombre,
                'razonSocial' => $usuario->student->preprofessionalPractices->first()->organization->razon_social,
            ],
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }
    public function aceptarCompromisoBioseguridad()
    {
        $authUser = Auth::user();

        if($authUser->student == null){
            return response()->json([
                'mensaje' => 'El usuario no es estudiante'
            ], Response::HTTP_BAD_REQUEST);
        }else if($authUser->student->preprofessionalPractices->first() == null){
                return response()->json([
                    'mensaje' => 'El estudiante no tiene practicas preprofesionales'
                ], Response::HTTP_BAD_REQUEST);
        }else if($authUser->student->preprofessionalPractices->first()->estudiante_carta_compromiso == 1){
                return response()->json([
                    'mensaje' => 'El estudiante ya acepto el compromiso de bioseguridad'
                ], Response::HTTP_BAD_REQUEST);
        }

        $practicaPreprofesional = $authUser->student->preprofessionalPractices->first();
        $practicaPreprofesional->estudiante_carta_compromiso = 1;
        $practicaPreprofesional->estudiante_carta_compromiso_fecha = Carbon::now()->format('Y-m-d');
        $practicaPreprofesional->save();

        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Carta de compromiso aceptada'
        ], 200);
    }
    public function solicitarPracticas(Request $request){
        // Validar la solicitud
        $request->validate([
            'organizacion.nombreRazonSocial' => 'required|string',
            'organizacion.representanteLegal' => 'required|string',
            'organizacion.areaDedicacion' => 'required|string',
            'organizacion.direccion' => 'required|string',
            'organizacion.telefono' => 'required|string',
            'organizacion.email' => 'required|email',
            'organizacion.diasHabiles' => 'required|string',
            'organizacion.horario' => 'required|string',
            'practicaPreprofesional.areaPropuesta' => 'required|string',
            'practicaPreprofesional.horasSolicitadas' => 'required|integer',
            'organizacion.representante' => 'required|integer',
            'compromisoEstudiante.acepta' => 'required|boolean',
        ]);
        $student = Auth::user()->student;
        $practicaPreprofesional = $student->preprofessionalPractices->first();
        if($practicaPreprofesional == null){
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

        $organization = $practicaPreprofesional->organizacion;
        $organizacionExistente = Organization::where('razon_social', $request->input('organizacion.nombreRazonSocial'))->first();
        if( $organizacionExistente == null){
            $organization->razon_social = $request->input('organizacion.nombreRazonSocial');
            $organization->representante_legal = $request->input('organizacion.representanteLegal');
            $organization->area_dedicacion = $request->input('organizacion.areaDedicacion');
            $organization->direccion = $request->input('organizacion.direccion');
            $organization->telefono = $request->input('organizacion.telefono');
            $organization->email = $request->input('organizacion.email');
            $organization->dias_laborables = $request->input('organizacion.diasHabiles');
            $organization->horario = $request->input('organizacion.horario');
            $organization->save();
        }
        // Puedes devolver una respuesta o redirigir a otra página según tus necesidades
        return response()->json(['mensaje' => 'Solicitud de práctica preprofesional procesada correctamente'], 200);
    }
    public function enviarInformeFinal(Request $request)
    {
        $user= Auth::user();
        $practicaPreprofesional = $user->student->preprofessionalPractices->first();
        if($practicaPreprofesional == null){
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
        if($organizaciones->isEmpty()){
            return response()->json([
                'mensaje' => 'No hay organizaciones registradas'
            ], 404);
        }

        $organizaciones->map(function($organizacion){
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

    public function generarCartaCompromiso(Request $request){
        $request->validate([
            'estudiante.carrera' => 'required|string',
            'estudiante.semestre' => 'required|string',
            'organizacion.nombreRazonSocial' => 'required|string'
        ]);
        $authUser = Auth::user();
        $estudiante = $authUser->student;
        if($estudiante != null){
            return response()->json(['mensaje' => 'El estudiante ya completo su información básica'], 400);
        }

        $student = new Student;
        $student->user_id = $authUser->id;
        $student->carrera_id = $request->input('estudiante.carrera');
        $student->nivel_id = $request->input('estudiante.semestre');
        $student->save();

        $practicaPreprofesional = new PreProfessionalPractice();
        $practicaPreprofesional->student_id = $student->id;
        $practicaPreprofesional->organization_id = $request->input('organizacion.nombreRazonSocial');
        $practicaPreprofesional->save();

        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Carta de compromiso generada correctamente'
        ], 200);

    }
    public function consultarOrganizacionAsignada() {
        $user= Auth::user();
        $practicaPreprofesional = $user->student->preprofessionalPractices->first();
        if($practicaPreprofesional == null){
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

        $estudiante = Auth::user()->student;
        if($estudiante == null || $estudiante->preprofessionalPractices->first() == null){
            return response()->json([
                'mensaje' => 'Las prácticas pre profesionales son requisito obligatorio para obtener el grado o título profesional en la Universidad Iberoamericana del Ecuador, con una duración mínima de 240 horas. Su cumplimiento es condición previa para la obtención del título, incluyendo seguimiento, evaluación, presentación de informes y certificados. Estas acciones son parte integral del proceso de titulación y validan la adquisición de experiencias prácticas relevantes en el campo profesional del estudiante.'
            ], Response::HTTP_BAD_REQUEST);
        }
        $practicaPreprofesional = $estudiante->preprofessionalPractices->first();

            if($practicaPreprofesional->estudiante_carta_compromiso == 1){
                $statusCartaCompromiso = 'Completado';
            }
            if($practicaPreprofesional->horas_practicas_solicitadas != null){
                $statusSolicitud = 'Completado';
            }
            if($practicaPreprofesional->cumplimiento_objetivos != null){
                $statusInformeFinal = 'Completado';
            }

        return response()->json([
            'data' => [
                'cartaCompromiso' => $statusCartaCompromiso,
                'solicitud' => $statusSolicitud,
                'informeFinal' => $statusInformeFinal
            ],
            'mensaje' => 'OK'
        ], Response::HTTP_OK);
    }

    function obtenerRepresentantes(){
         $estudiante = Auth::user()->student;
        if($estudiante == null || $estudiante->preprofessionalPractices->first() == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }

        $practicaPreprofesional = $estudiante->preprofessionalPractices->first();
        $representantes = $practicaPreprofesional->organization->internshipRepresentatives;
        if($representantes->isEmpty()){
            return response()->json([
                'mensaje' => 'No hay representantes registrados'
            ], 404);
        }
        $representantes->map(function($representante){
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
}
