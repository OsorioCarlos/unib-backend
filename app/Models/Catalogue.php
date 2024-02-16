<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function tipoUsuarios()
    {
        return $this->hasMany(User::class, 'tipo_id');
    }

    public function estadoUsuarios()
    {
        return $this->hasMany(User::class, 'estado_id');
    }

    public function carreraEstudiantes()
    {
        return $this->hasMany(Student::class, 'carrera_id');
    }

    public function nivelEstudiantes()
    {
        return $this->hasMany(Student::class, 'nivel_id');
    }

    public function carreraDirectoresCarrera()
    {
        return $this->hasMany(CareerDirector::class, 'carrera_id');
    }

    public function estadoPracticasPreProfesionales()
    {
        return $this->hasMany(PreProfessionalPractice::class, 'estado_id');
    }

    public function criterioCalificaciones()
    {
        return $this->hasMany(GradingCriteria::class, 'criterio_id');
    }
}
