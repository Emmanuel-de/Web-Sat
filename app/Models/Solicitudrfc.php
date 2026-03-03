<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudRfc extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_rfc';

    protected $fillable = [
        'user_id',
        'folio',
        'primer_apellido',
        'segundo_apellido',
        'nombres',
        'fecha_nacimiento',
        'sexo',
        'estado_nacimiento',
        'curp',
        'email',
        'telefono',
        'tipo_identificacion',
        // Domicilio fiscal
        'codigo_postal',
        'estado',
        'municipio',
        'colonia',
        'calle',
        'no_exterior',
        'no_interior',
        'entre_calles',
        // Actividad
        'regimen_fiscal',
        'actividad_principal',
        'descripcion_actividad',
        'fecha_inicio_actividades',
        // Resultado
        'rfc_asignado',
        'estatus',       // 'pendiente' | 'procesada' | 'rechazada'
        'observaciones',
        'fecha_resolucion',
    ];

    protected $casts = [
        'fecha_nacimiento'        => 'date',
        'fecha_inicio_actividades'=> 'date',
        'fecha_resolucion'        => 'datetime',
    ];

    // ─── Boot ────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->folio  = 'RFC-' . strtoupper(substr(uniqid(), -8));
            $model->estatus = $model->estatus ?? 'pendiente';
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopePendientes($q)   { return $q->where('estatus', 'pendiente'); }
    public function scopeProcesadas($q)   { return $q->where('estatus', 'procesada'); }

    // ─── Relaciones ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}