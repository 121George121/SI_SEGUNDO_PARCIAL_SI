<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'ci',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'correo'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id_persona', 'id_persona');
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'id_administrador', 'id_persona');
    }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_docente', 'id_persona');
    }

    public function postulante()
    {
        return $this->hasOne(Postulante::class, 'id_postulante', 'id_persona');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}
