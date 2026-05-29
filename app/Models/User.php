<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false; // Using default CURRENT_DATE for fecha_creacion

    protected $fillable = [
        'nombre_usuario',
        'correo',
        'contraseña',
        'estado',
        'fecha_creacion',
        'id_rol',
        'id_persona'
    ];

    protected $hidden = [
        'contraseña',
    ];

    // Map Laravel password verification to our custom 'contraseña' field
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function getAuthPasswordName()
    {
        return 'contraseña';
    }

    public function getEmailAttribute()
    {
        return $this->correo;
    }

    // Relationships
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function isSuperAdmin()
    {
        return $this->rol && $this->rol->nombre_rol === 'SuperAdministrador';
    }

    public function isAdmin()
    {
        return $this->rol && ($this->rol->nombre_rol === 'Admin' || $this->rol->nombre_rol === 'SuperAdministrador');
    }

    public function isDocente()
    {
        return $this->rol && $this->rol->nombre_rol === 'Docente';
    }

    public function isPostulante()
    {
        return $this->rol && $this->rol->nombre_rol === 'Postulante';
    }
}
