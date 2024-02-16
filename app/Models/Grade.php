<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nota_promedio',
        'porcentaje_asistencia',
        'observaciones',
        'recomendaciones'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preprofessionalPractice()
    {
        return $this->belongsTo(PreProfessionalPractice::class);
    }

    public function gradingCriterias()
    {
        return $this->hasMany(GradingCriteria::class);
    }
}
