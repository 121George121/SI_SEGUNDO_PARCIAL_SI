<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacion';
    protected $primaryKey = 'id_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'numero_evaluacion',
        'porcentaje',
        'fecha',
        'estado',
        'id_grupo',
        'id_materia'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_evaluacion', 'id_evaluacion');
    }
}
