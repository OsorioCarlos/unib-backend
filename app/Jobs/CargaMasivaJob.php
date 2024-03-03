<?php

namespace App\Jobs;

use App\Models\CareerDirector;
use App\Models\InternshipRepresentative;
use App\Models\Organization;
use App\Models\Student;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CargaMasivaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tipo_carga = '';
    protected $datoExcel = null;

    /**
     * Create a new job instance.
     */
    public function __construct($tipo_carga, $datoExcel)
    {
        $this->tipo_carga = $tipo_carga;
        $this->datoExcel = $datoExcel;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->tipo_carga) {
            case 'usuario_administrador':
                $usuarios = User::where('identificacion', $this->datoExcel[0])->count();
                if (!($usuarios > 0)) {
                    $usuario = new User();
                    $usuario->identificacion = $this->datoExcel[0];
                    $usuario->nombre_completo = $this->datoExcel[1];
                    $usuario->email = $this->datoExcel[2];
                    $usuario->password = $this->datoExcel[3];
                    $usuario->tipo_id = 16;
                    $usuario->save();
                }
                break;
            case 'usuario_area_vinculacion':
                $usuarios = User::where('identificacion', $this->datoExcel[0])->count();
                if (!($usuarios > 0)) {
                    $usuario = new User();
                    $usuario->identificacion = $this->datoExcel[0];
                    $usuario->nombre_completo = $this->datoExcel[1];
                    $usuario->email = $this->datoExcel[2];
                    $usuario->password = $this->datoExcel[3];
                    $usuario->tipo_id = 17;
                    $usuario->save();
                }
                break;
            case 'usuario_director_carrera':
                $usuarios = User::where('identificacion', $this->datoExcel[0])->count();
                if (!($usuarios > 0)) {
                    $usuario = new User();
                    $usuario->identificacion = $this->datoExcel[0];
                    $usuario->nombre_completo = $this->datoExcel[1];
                    $usuario->email = $this->datoExcel[2];
                    $usuario->password = $this->datoExcel[3];
                    $usuario->tipo_id = 18;
                    $usuario->save();

                    $directorCarrera = new CareerDirector();
                    $directorCarrera->user_id = $usuario->id;
                    $directorCarrera->carrera_id = $this->datoExcel[4];
                    $directorCarrera->save();
                }
                break;
            case 'usuario_representante_practicas':
                $usuarios = User::where('identificacion', $this->datoExcel[0])->count();
                if (!($usuarios > 0)) {
                    $usuario = new User();
                    $usuario->identificacion = $this->datoExcel[0];
                    $usuario->nombre_completo = $this->datoExcel[1];
                    $usuario->email = $this->datoExcel[2];
                    $usuario->password = $this->datoExcel[3];
                    $usuario->tipo_id = 20;
                    $usuario->save();

                    $internshipRepresentative = new InternshipRepresentative();
                    $internshipRepresentative->user_id = $usuario->id;
                    $internshipRepresentative->organization_id = $this->datoExcel[4];
                    $internshipRepresentative->save();
                }
                break;
            case 'usuario_estudiante':
                $usuarios = User::where('identificacion', $this->datoExcel[0])->count();
                if (!($usuarios > 0)) {
                    $usuario = new User();
                    $usuario->identificacion = $this->datoExcel[0];
                    $usuario->nombre_completo = $this->datoExcel[1];
                    $usuario->email = $this->datoExcel[2];
                    $usuario->password = $this->datoExcel[3];
                    $usuario->tipo_id = 19;
                    $usuario->save();

                    $estudiante = new Student();
                    $estudiante->user_id = $usuario->id;
                    $estudiante->carrera_id = $this->datoExcel[4];
                    $estudiante->nivel_id = $this->datoExcel[5];
                    $estudiante->save();
                }
                break;
            case 'organizacion':
                $organizaciones = Organization::where('ruc', $this->datoExcel[0])->count();
                if (!($organizaciones > 0)) {
                    $organizacion = new Organization();
                    $organizacion->ruc = $this->datoExcel[0];
                    $organizacion->razon_social = $this->datoExcel[1];
                    $organizacion->representante_legal = $this->datoExcel[2];
                    $organizacion->direccion = $this->datoExcel[3];
                    $organizacion->telefono = $this->datoExcel[4];
                    $organizacion->email = $this->datoExcel[5];
                    $organizacion->area_dedicacion = $this->datoExcel[6];
                    $organizacion->horario = $this->datoExcel[7];
                    $organizacion->dias_laborables = $this->datoExcel[8];
                    $organizacion->save();
                }
                break;
            default:
                break;
        }
    }
}
