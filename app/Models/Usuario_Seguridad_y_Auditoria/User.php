<?php

namespace App\Models\Usuario_Seguridad_y_Auditoria;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Tabla y llave primaria
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    // Columnas que se pueden llenar masivamente
    protected $fillable = [
        'nombre_usuario',
        'correo',
        'contraseña',
        'estado',
        'fecha_creacion',
        'id_rol',
        'id_persona',
        'intentos_fallidos',
        'bloqueado_hasta',
        'ultimo_login',
    ];

    // Cast para manejar fecha de bloqueo como Carbon
    protected $casts = [
        'bloqueado_hasta' => 'datetime',
        'ultimo_login'    => 'datetime',
    ];

    // Columnas ocultas
    protected $hidden = [
        'contraseña',
    ];

    // Laravel usa getAuthPassword para Hash::check
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Relaciones con otras tablas
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Funciones de rol
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