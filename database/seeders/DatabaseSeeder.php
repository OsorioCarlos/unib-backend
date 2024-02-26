<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        /*\App\Models\Role::factory()->create([
            'nombre' => 'Representante de practicas',
        ]);

        \App\Models\Role::factory()->create([
            'nombre' => 'Estudiante'
        ]);*/

        \App\Models\Resource::factory()->create([
            'nombre' => 'ESTADOS USUARIO',
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'ACTIVO',
            'resource_id' => 1
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'INACTIVO',
            'resource_id' => 1
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'ESTADOS PRACTICA PREPROFESIONAL',
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'EN PROCESO',
            'resource_id' => 2
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'APROBADO',
            'resource_id' => 2
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'REPROBADO',
            'resource_id' => 2
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'NIVELES'
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'PRIMER NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'SEGUNDO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'TERCER NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'CUARTO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'QUINTO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'SEXTO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'SEPTIMO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'OCTAVO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'NOVENO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'DÉCIMO NIVEL',
            'resource_id' => 3
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'TIPOS USUARIO',
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'ADMINISTRADOR',
            'resource_id' => 4
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'ÁREA VINCULACIÓN',
            'resource_id' => 4
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'DIRECTOR DE CARRERA',
            'resource_id' => 4
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'ESTUDIANTE',
            'resource_id' => 4
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'REPRESENTANTE PRÁCTICAS',
            'resource_id' => 4
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'CARRERAS',
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'ENFERMERÍA',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'FISIOTERAPIA',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'FISNUTRICIÓN Y DIETÉTICAIOTERAPIA',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'PSICOLOGÍA',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'CIENCIAS DE LA EDUCACIÓN BÁSICA',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'MULTIMEDIA Y PRODUCCIÓN AUDIOVISUAL',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'MEDICINA',
            'resource_id' => 5
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'SOFTWARE',
            'resource_id' => 5
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'CRITERIOS CALIFFICACIÓN'
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'CUMPLIMIENTO DE LOS OBJETIVOS DE LA PRÁCTICA',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'COMPETENCIAS NECESARIAS PARA LA TAREA',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'RESPONSABILIDAD EN EL TRABAJO',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'CREATIVIDAD E INICIATIVA',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'ADAPTACIÓN AL LUGAR DEL TRABAJO',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'COOPERACIÓN',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'HONESTIDAD',
            'resource_id' => 6
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'PUNTUALIDAD',
            'resource_id' => 6
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '1752397172',
            'nombre_completo' => 'OSORIO HINOJOSA CARLOS ALEXANDER',
            'email' => 'carlosalexander.2001@hotmail.com',
            'password' => '123456789',
            'tipo_id' => 16//ADMINISTRADOR
        ]);


        \App\Models\User::factory()->create([
            'identificacion' => '2929291291',
            'nombre_completo' => 'MOLINA RIVERA EWDIN MARCELO',
            'email' => 'representante@test.com',
                'password' => '123456789',
            'tipo_id' => 20
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '2222222222',
            'nombre_completo' => 'MEDINA PONCE DARIO JOSE',
            'email' => 'director@test.com',
            'password' => '123456789',
            'tipo_id' => 18
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '1751592013',
            'nombre_completo' => 'DELGADO LANDA BYRON DANIEL',
            'email' => 'estudiante@test.com',
            'password' => '123456789',
            'tipo_id' => 19
        ]);

        \App\Models\Organization::factory()->create([
            'razon_social' => 'Empresa de prueba',
            'representante_legal' => 'Representante de prueba',
            'direccion' => 'Direccion de prueba',
            'area_dedicacion' => 'Area de prueba',
            'telefono' => 'Telefono de prueba',
            'horario' => 'Horario de prueba',
            'dias_laborables' => 'Dias laborables de prueba',
            'email' => 'email@gmail.com'
        ]);

        \App\Models\CareerDirector::factory()->create([
            'user_id' => 3,
            'carrera_id' => 23
        ]);

        \App\Models\Student::factory()->create([
            'user_id' => 4,
            'carrera_id' => 23,
            'nivel_id' => 10
        ]);

    }
}
