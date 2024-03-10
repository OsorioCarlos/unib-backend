<?php

namespace App\Http\Controllers;

use App\Models\CareerDirector;
use App\Models\PreProfessionalPractice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreProfessionalPracticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $practicasPreprofesionales = PreProfessionalPractice::where('id', '<>', null);

        if ($authUser->tipoCatalogo->nombre == 'REPRESENTANTE PRÁCTICAS') {
            $representantePracticas = $authUser->internshipRepresentative;

            $practicasPreprofesionales->with(['student.user', 'student.carreraCatalogo', 'student.nivelCatalogo', 'estadoCatalogo'])
                ->where('internship_representative_id', $representantePracticas->id);
        } else if ($authUser->tipoCatalogo->nombre == 'ESTUDIANTE') {
            $estudiante = $authUser->student;

            $practicasPreprofesionales->with(['organization', 'estadoCatalogo'])
                ->where('student_id', $estudiante->id);
        } else {

        }
        $practicasPreprofesionales = $practicasPreprofesionales->get();

        return response()->json([
            'data' => $practicasPreprofesionales,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $practicaPreprofesionalData = $request->get('practica_preprofesional');

        $practicaPreprofesional = new PreProfessionalPractice();
        $practicaPreprofesional->numero_horas_practica = $practicaPreprofesionalData['numero_horas_practica'];
        $practicaPreprofesional->estudiante_compromiso = $practicaPreprofesionalData['estudiante_compromiso'];
        $practicaPreprofesional->estudiante_compromiso_fecha = Carbon::parse($practicaPreprofesionalData['estudiante_compromiso_fecha'])->format('Y-m-d');
        $practicaPreprofesional->objetivos_practica = $practicaPreprofesionalData['objetivos_practica'];
        $practicaPreprofesional->tareas = $practicaPreprofesionalData['tareas'];
        $practicaPreprofesional->horario = $practicaPreprofesionalData['horario'];
        $practicaPreprofesional->fecha_inicio = Carbon::parse($practicaPreprofesionalData['fecha_inicio'])->format('Y-m-d');
        $practicaPreprofesional->fecha_finalizacion = Carbon::parse($practicaPreprofesionalData['fecha_finalizacion'])->format('Y-m-d');
        $practicaPreprofesional->empresa_compromiso = $practicaPreprofesionalData['empresa_compromiso'];
        $practicaPreprofesional->empresa_compromiso_fecha = Carbon::parse($practicaPreprofesionalData['empresa_compromiso_fecha'])->format('Y-m-d');
        $practicaPreprofesional->area_practicas = $practicaPreprofesionalData['area_practicas'];
        $practicaPreprofesional->nota_final = $practicaPreprofesionalData['nota_final'];
        $practicaPreprofesional->estudiante_id = $practicaPreprofesionalData['estudiante_id'];
        $practicaPreprofesional->save();

        return response()->json([
            'practica_preprofesional' => $practicaPreprofesional,
            'mensaje' => 'OK'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $practicaPreprofesional = PreProfessionalPractice::find($id);
        if ($practicaPreprofesional == null) {
            return response()->json([
                'mensaje' => 'El estudiante no tiene practicas preprofesionales'
            ], Response::HTTP_BAD_REQUEST);
        }

        $director = CareerDirector::find($practicaPreprofesional->career_director_id);
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
              'nombre' => $practicaPreprofesional->student->user->nombre_completo== null? '': $practicaPreprofesional->student->user->nombre_completo,
              'identificacion' => $practicaPreprofesional->student->user->identificacion== null? '': $practicaPreprofesional->student->user->identificacion,
              'email'=> $practicaPreprofesional->student->user->email== null? '': $practicaPreprofesional->student->user->email,
              'carrera' => $practicaPreprofesional->student->carreraCatalogo->nombre== null? '': $practicaPreprofesional->student->carreraCatalogo->nombre,
              'nivel' => $practicaPreprofesional->student->nivelCatalogo->nombre== null? '': $practicaPreprofesional->student->nivelCatalogo->nombre,
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
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
