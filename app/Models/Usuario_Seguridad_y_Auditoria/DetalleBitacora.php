<?php

namespace App\Models\Usuario_Seguridad_y_Auditoria;

use Illuminate\Database\Eloquent\Model;

class DetalleBitacora extends Model
{
    protected $table = 'detalle_bitacora';
    protected $primaryKey = 'id_detallebitacora';
    public $timestamps = false;

    protected $fillable = [
        'id_bitacora',
        'direccion_ip',
        'hora_inicio',
        'hora_fin',
        'accion'
    ];

    public function bitacora()
    {
        return $this->belongsTo(Bitacora::class, 'id_bitacora', 'id_bitacora');
    }
}
