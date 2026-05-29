<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobante';
    protected $primaryKey = 'id_comprobante';
    public $timestamps = false;

    protected $fillable = [
        'tipo_comprobante',
        'numero_comprobante',
        'fecha_emision'
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_comprobante', 'id_comprobante');
    }
}
