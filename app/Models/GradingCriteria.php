<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GradingCriteria extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'calificacion'
    ];

    public function criterioCatalogo()
    {
        return $this->belongsTo(Catalogue::class, 'criterio_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

}
