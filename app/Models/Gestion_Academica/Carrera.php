<?php
namespace App\Models\Gestion_Academica;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carrera';
    protected $primaryKey = 'id_carrera';
    public $timestamps = false;

    protected $fillable = ['nombre_carrera', 'descripcion', 'duracion_anios'];

    public function cupos()
    {
        return $this->hasMany(CupoCarrera::class, 'id_carrera', 'id_carrera');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionCupo::class, 'id_carrera', 'id_carrera');
    }
}