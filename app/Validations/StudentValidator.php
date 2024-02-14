<?php

namespace App\Validations;

use Illuminate\Http\Request;

class StudentValidator
{
    public static function validateRequestPractice(Request $request)
    {
        $request->validate([
            'estudiante.id' => 'required|numeric',
            'estudiante.carrera_id' => 'required|numeric',
            'estudiante.nivel_id' => 'required|numeric',
            'practicaPreprofesional.area' => 'required|string|max:255',
            'practicaPreprofesional.numeroHoras' => 'required|numeric'
        ]);
    }

    public static function validateAcceptCompromise(Request $request)
    {
        $request->validate([
            'estudiante.id' => 'required|numeric'
        ]);
    }
}
