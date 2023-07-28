<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory;

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

    public function grades()
    {
        return $this->belongsToMany(Grade::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function careerDirectors()
    {
        return $this->hasMany(CareerDirector::class);
    }

    public function gradingCriteria()
    {
        return $this->hasMany(GradingCriteria::class);
    }
}
