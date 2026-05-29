<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'monto',
        'fecha_pago',
        'metodo_pago',
        'estado_pago',
        'observaciones',
        'id_comprobante',
        'id_inscripcion'
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante', 'id_comprobante');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion', 'id_inscripcion');
    }
}
