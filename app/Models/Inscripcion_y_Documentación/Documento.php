<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documento';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;

    protected $fillable = [
        'tipo_documento',
        'nombre',
        'estado',
        'observacion',
        'fecha_registro',
        'fecha_validacion',
        'id_administrador',
        'id_postulante'
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    public function administrador()
    {
        return $this->belongsTo(Administrador::class, 'id_administrador', 'id_administrador');
    }
}
