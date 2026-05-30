<?php

namespace App\Models\Usuario_Seguridad_y_Auditoria;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'descripcion',
        'fecha',
        'hora',
        'estado',
        'id_usuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleBitacora::class, 'id_bitacora', 'id_bitacora');
    }
}
