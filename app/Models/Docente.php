<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'id_docente';
    public $incrementing = false; // Primary key references PERSONA
    public $timestamps = false;

    protected $fillable = [
        'id_docente',
        'anio_servicio',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_docente', 'id_persona');
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'docente_especialidad', 'id_docente', 'id_especialidad');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_docente', 'id_docente');
    }
}
