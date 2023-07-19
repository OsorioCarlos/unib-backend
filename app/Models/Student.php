<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'level_id',
        'career_id',
        'user_id',
    ];

    public function preprofessionalPractices()
    {
        return $this->hasMany(PreProfessionalPractice::class);
    }
}
