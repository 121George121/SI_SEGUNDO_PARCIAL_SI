<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administrador';
    protected $primaryKey = 'id_administrador';
    public $incrementing = false; // Primary key is foreign key referencing PERSONA
    public $timestamps = false;

    protected $fillable = [
        'id_administrador',
        'cargo',
        'area',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_administrador', 'id_persona');
    }

    public function documentosValidados()
    {
        return $this->hasMany(Documento::class, 'id_administrador', 'id_administrador');
    }
}
