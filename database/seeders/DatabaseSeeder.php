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
            'nombre' => 'Representante de practicas'
        ]);

        \App\Models\Role::factory()->create([
            'nombre' => 'Estudiante'
        ]);
        
        \App\Models\User::factory()->create([
            'identificacion' => '1111111111',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'estado' => 'activo',
            'rol_id' => 1
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '1751592013',
            'name' => 'Delgado Landa Byron Daniel',
            'estado' => 'activo',
            'email' => 'byrondanipm@gmail.com',
            'rol_id' => 2
        ]);

        \App\Models\User::factory()->create([
            'identificacion' => '1751592222',
            'name' => 'Osorio Carlos',
            'estado' => 'activo',
            'email' => 'carlosalexander.2001@hotmail.com',
            'rol_id' => 2
        ]);

        \App\Models\Student::factory()->create([
            'level_id' => 1,
            'career_id' => 1,
            'user_id' => 1,
        ]);
        \App\Models\Student::factory()->create([
            'level_id' => 1,
            'career_id' => 1,
            'user_id' => 2,
        ]);

    }
}
