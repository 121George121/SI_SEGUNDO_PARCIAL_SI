<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    protected $table = 'postulante';
    protected $primaryKey = 'id_postulante';
    public $incrementing = false; // Primary key references PERSONA
    public $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'estado_inscripcion',
        'fecha_registro',
        'id_asignacion'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_postulante', 'id_persona');
    }

    public function asignacion()
    {
        return $this->belongsTo(AsignacionCupo::class, 'id_asignacion', 'id_asignacioncupo');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_postulante', 'id_postulante');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_postulante', 'id_postulante');
    }

    public function resultadoAcademico()
    {
        return $this->hasOne(ResultadoAcademico::class, 'id_postulante', 'id_postulante');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_postulante', 'id_postulante');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_postulante', 'id_postulante');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_postulante', 'id_postulante', 'id_grupo');
    }
}
