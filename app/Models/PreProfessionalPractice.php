<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreProfessionalPractice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'estudiante_carta_compromiso',
        'estudiante_carta_compromiso_fecha',
        'estudiante_compromiso',
        'estudiante_compromiso_fecha',
        'area_practicas',
        'objetivos_practicas',
        'tareas',
        'numero_horas_practicas',
        'fecha_inicio',
        'fecha_fin',
        'dias_laborables',
        'horario',
        'empresa_compromiso_fecha',
        'cumplimiento_objetivos',
        'beneficios',
        'aprendizajes',
        'desarrollo_personal',
        'comentarios',
        'recomendaciones'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function careerDirector()
    {
        return $this->belongsTo(CareerDirector::class);
    }

    public function internshipRepresentative()
    {
        return $this->belongsTo(InternshipRepresentative::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function estadoCatalogo()
    {
        return $this->belongsTo(Catalogue::class, 'estado_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
