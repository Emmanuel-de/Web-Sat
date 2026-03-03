<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloSat extends Model
{
    use HasFactory;

    protected $table = 'modulos_sat';

    protected $fillable = [
        'nombre',
        'estado',
        'municipio',
        'direccion',
        'horario',
        'telefono',
        'latitud',
        'longitud',
        'activo',
        'servicios',  // JSON: ['RFC','EFIRMA','CIF',...]
    ];

    protected $casts = [
        'activo'   => 'boolean',
        'latitud'  => 'decimal:8',
        'longitud' => 'decimal:8',
        'servicios'=> 'array',
    ];

    public function scopeActivos($q)            { return $q->where('activo', true); }
    public function scopePorEstado($q, $estado) { return $q->where('estado', $estado); }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'modulo_sat_id');
    }
}