<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripcion';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;

    protected $fillable = [
        'codigo_inscripcion',
        'estado',
        'fecha_inscripcion',
        'id_postulante'
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'inscripcion_carrera', 'id_inscripcion', 'id_carrera')
                    ->withPivot('prioridad', 'estado');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_inscripcion', 'id_inscripcion');
    }
}
