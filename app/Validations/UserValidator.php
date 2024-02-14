<?php

namespace App\Validations;

use Illuminate\Http\Request;

class UserValidator
{
    public static function validate(Request $request)
    {
        $request->validate([
            'identificacion' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'rol_id' => 'required|numeric',
        ]);
    }
}
