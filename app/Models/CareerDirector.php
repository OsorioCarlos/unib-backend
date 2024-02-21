<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerDirector extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carreraCatalogo()
    {
        return $this->belongsTo(Catalogue::class, 'carrera_id');
    }

    public function preprofessionalPractices()
    {
        return $this->hasMany(PreProfessionalPractice::class);
    }
}
