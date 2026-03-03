<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';

    protected $fillable = [
        'user_id',
        'nombre',
        'rfc',
        'email',
        'telefono',
        'tipo',      // 'mensaje' | 'queja' | 'sugerencia' | 'denuncia' | 'reconocimiento'
        'tema',
        'area',
        'descripcion',
        'mensaje',
        'folio',
        'estatus',   // 'nuevo' | 'en_proceso' | 'resuelto' | 'cerrado'
        'respuesta',
        'fecha_respuesta',
        'acepta_privacidad',
    ];

    protected $casts = [
        'acepta_privacidad' => 'boolean',
        'fecha_respuesta'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($m) {
            $m->folio   = 'CNT-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $m->estatus = 'nuevo';
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopeNuevos($q)    { return $q->where('estatus', 'nuevo'); }
    public function scopePorTipo($q, $tipo) { return $q->where('tipo', $tipo); }

    public function user() { return $this->belongsTo(User::class); }
}