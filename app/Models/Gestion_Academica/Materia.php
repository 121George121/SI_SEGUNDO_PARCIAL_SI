<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materia';
    protected $primaryKey = 'id_materia';
    public $timestamps = false;

    protected $fillable = [
        'nombre_materia',
        'codigo_materia',
        'creditos'
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_materia', 'id_materia', 'id_grupo');
    }
}
