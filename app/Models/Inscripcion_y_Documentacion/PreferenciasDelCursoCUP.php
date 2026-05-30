<?php

namespace App\Models\Inscripcion_y_Documentacion;

use Illuminate\Database\Eloquent\Model;

class PreferenciasDelCursoCUP extends Model
{
    protected $table = 'preferencia_curso_cup';
    protected $primaryKey = 'id_preferencia';
    public $timestamps = false;

    protected $fillable = [
        'modalidad',
        'turno',
        'periodo_academico',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'descripcion'
    ];
}
