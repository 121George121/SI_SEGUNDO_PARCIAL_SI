<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionCupo extends Model
{
    protected $table = 'asignacioncupo';
    protected $primaryKey = 'id_asignacioncupo';
    public $timestamps = false;

    protected $fillable = [
        'fecha_asignacion',
        'promedio_final',
        'puesto_merito',
        'estado_asignacion',
        'id_carrera',
        'id_gestion'
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion', 'id_gestion');
    }

    public function postulantes()
    {
        return $this->hasMany(Postulante::class, 'id_asignacion', 'id_asignacioncupo');
    }
}
