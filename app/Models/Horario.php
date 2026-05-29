<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horario';
    protected $primaryKey = 'id_horario';
    public $timestamps = false;

    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_horario', 'id_horario', 'id_grupo');
    }
}
