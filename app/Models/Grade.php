<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'promedio'
    ];

    public function preprofessionalPractice()
    {
        return $this->belongsTo(PreProfessionalPractice::class);
    }

    public function gradingCriteria()
    {
        return $this->hasMany(GradingCriteria::class);
    }
}
