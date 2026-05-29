<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoAcademico extends Model
{
    protected $table = 'resultadoacademico';
    protected $primaryKey = 'id_resultado';
    public $timestamps = false;

    protected $fillable = [
        'promedio_final',
        'estado_final',
        'fecha_calculo',
        'id_postulante'
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }
}
