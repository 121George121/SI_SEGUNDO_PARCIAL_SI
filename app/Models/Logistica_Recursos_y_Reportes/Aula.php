<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $table = 'aula';
    protected $primaryKey = 'id_aula';
    public $timestamps = false;

    protected $fillable = [
        'codigo_aula',
        'capacidad',
        'ubicacion'
    ];

    protected $appends = ['facultad', 'edificio', 'piso', 'estado', 'descripcion'];

    private function getUbicacionData()
    {
        $val = $this->attributes['ubicacion'] ?? '';
        if (empty($val)) {
            return [];
        }
        $data = json_decode($val, true);
        return is_array($data) ? $data : [];
    }

    private function setUbicacionData(array $data)
    {
        $this->attributes['ubicacion'] = json_encode($data);
    }

    public function getFacultadAttribute()
    {
        return $this->getUbicacionData()['facultad'] ?? '';
    }

    public function setFacultadAttribute($value)
    {
        $data = $this->getUbicacionData();
        $data['facultad'] = $value;
        $this->setUbicacionData($data);
    }

    public function getEdificioAttribute()
    {
        return $this->getUbicacionData()['edificio'] ?? '';
    }

    public function setEdificioAttribute($value)
    {
        $data = $this->getUbicacionData();
        $data['edificio'] = $value;
        $this->setUbicacionData($data);
    }

    public function getPisoAttribute()
    {
        return $this->getUbicacionData()['piso'] ?? '';
    }

    public function setPisoAttribute($value)
    {
        $data = $this->getUbicacionData();
        $data['piso'] = $value;
        $this->setUbicacionData($data);
    }

    public function getEstadoAttribute()
    {
        return $this->getUbicacionData()['estado'] ?? 'Activo';
    }

    public function setEstadoAttribute($value)
    {
        $data = $this->getUbicacionData();
        $data['estado'] = $value;
        $this->setUbicacionData($data);
    }

    public function getDescripcionAttribute()
    {
        return $this->getUbicacionData()['descripcion'] ?? '';
    }

    public function setDescripcionAttribute($value)
    {
        $data = $this->getUbicacionData();
        $data['descripcion'] = $value;
        $this->setUbicacionData($data);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_aula', 'id_aula');
    }
}
