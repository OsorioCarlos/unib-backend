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

        \App\Models\Role::factory()->create([
            'nombre' => 'Representante de practicas',
        ]);

        \App\Models\Role::factory()->create([
            'nombre' => 'Estudiante'
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'Carreras',
        ]);

        \App\Models\Resource::factory()->create([
            'nombre' => 'Niveles'
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'Software',
            'recurso_id' => 1
        ]);

        \App\Models\Catalogue::factory()->create([
            'nombre' => 'Primer Nivel',
            'recurso_id' => 2
        ]);
        
        \App\Models\User::factory()->create([
            'identificacion' => '1111111111',
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123456789',
            'estado' => 'activo',
            'rol_id' => 1
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '1751592013',
            'nombre' => 'Delgado Landa Byron Daniel',
            'estado' => 'activo',
            'email' => 'byrondanipm@gmail.com',
            'password' => '123456789',
            'rol_id' => 2
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '1752397172',
            'nombre' => 'Osorio Carlos',
            'estado' => 'activo',
            'email' => 'carlosalexander.2001@hotmail.com',
            'password' => '123456789',
            'rol_id' => 2
        ]);

        \App\Models\Student::factory()->create([
            'nivel_id' => 2,
            'carrera_id' => 1,
            'usuario_id' => 3,
        ]);
        \App\Models\Student::factory()->create([
            'nivel_id' => 2,
            'carrera_id' => 1,
            'usuario_id' => 2,
        ]);

    }
}
