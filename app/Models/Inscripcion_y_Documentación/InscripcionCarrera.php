<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionCarrera extends Model
{
    protected $table = 'inscripcion_carrera';
    protected $primaryKey = ['id_inscripcion', 'id_carrera'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_inscripcion',
        'id_carrera',
        'prioridad',
        'estado'
    ];
}
