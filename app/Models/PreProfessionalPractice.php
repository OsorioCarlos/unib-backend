<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreProfessionalPractice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_horas_practica',
        'estudiante_compromiso',
        'estudiante_compromiso_fecha',
        'objetivos_practica',
        'tareas',
        'horario',
        'fecha_inicio',
        'fecha_finalizacion',
        'empresa_compromiso',
        'empresa_compromiso_fecha',
        'area_practicas',
        'nota_final',
        'student_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
