<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'sigla_grupo',
        'capacidad_max',
        'estado',
        'cant_estudiantes',
        'id_aula',
        'id_modalidad',
        'id_turno',
        'id_docente',
        'id_gestion',
        'id_carrera',
        'descripcion'
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function aula()

    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad', 'id_modalidad');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno', 'id_turno');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion', 'id_gestion');
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'grupo_materia', 'id_grupo', 'id_materia')
                    ->withPivot('id_docente');
    }

    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'grupo_horario', 'id_grupo', 'id_horario');
    }

    public function postulantes()
    {
        return $this->belongsToMany(Postulante::class, 'grupo_postulante', 'id_grupo', 'id_postulante')
                    ->withPivot('estado', 'fecha_asignacion');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_grupo', 'id_grupo');
    }
}
