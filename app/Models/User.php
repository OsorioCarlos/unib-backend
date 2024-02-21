<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identificacion',
        'nombre_completo',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*public function role()
    {
        return $this->belongsTo(Role::class);
    }*/

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function careerDirector()
    {
        return $this->hasOne(CareerDirector::class);
    }

    public function internshipRepresentative()
    {
        return $this->hasOne(InternshipRepresentative::class);
    }

    public function tipoCatalogo()
    {
        return $this->belongsTo(Catalogue::class, 'tipo_id');
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
