<?php

namespace App\Models\Usuario_Seguridad_y_Auditoria;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
        'descripcion'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
}
