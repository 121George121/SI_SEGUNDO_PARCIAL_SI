<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidad';
    protected $primaryKey = 'id_modalidad';
    public $timestamps = false;

    protected $fillable = [
        'nombre_modalidad',
        'descripcion'
    ];

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_modalidad', 'id_modalidad');
    }
}
