<?php

namespace App\Models\Gestion_Academica;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'gestion';
    protected $primaryKey = 'id_gestion';
    public $timestamps = false;

    protected $fillable = [
        'anio',
        'periodo',
        'fecha_inicio',
        'fecha_fin'
    ];

    public function cupos()
    {
        return $this->hasMany(CupoCarrera::class, 'id_gestion', 'id_gestion');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionCupo::class, 'id_gestion', 'id_gestion');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_gestion', 'id_gestion');
    }
}
