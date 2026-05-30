<?php

namespace App\Models\Gestion_Academica;

use Illuminate\Database\Eloquent\Model;

class CupoCarrera extends Model
{
    protected $table = 'cupocarrera';
    protected $primaryKey = 'id_cupo';
    public $timestamps = false;

    protected $fillable = [
        'gestion',
        'cantidad_cupos',
        'cupos_ocupados',
        'cupos_disponibles',
        'id_gestion',
        'id_carrera'
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function gestionRel()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion', 'id_gestion');
    }
}
