<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ruc',
        'razon_social',
        'representante_legal',
        'direccion',
        'area_dedicacion',
        'telefono',
        'horario',
        'dias_laborables'
    ];

    public function internshipRepresentatives()
    {
        return $this->hasMany(InternshipRepresentative::class);
    }

    public function preprofessionalPractices()
    {
        return $this->hasMany(PreProfessionalPractice::class);
    }

}
