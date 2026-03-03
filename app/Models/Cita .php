<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'user_id',
        'folio',
        'rfc',
        'curp',
        'nombre',
        'email',
        'telefono',
        'modulo_sat_id',
        'tramite',
        'fecha',
        'horario',
        'observaciones',
        'estatus',   // 'pendiente' | 'confirmada' | 'cancelada' | 'atendida'
        'codigo_confirmacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($m) {
            $m->folio              = 'CIT-' . strtoupper(uniqid());
            $m->estatus            = 'confirmada';
            $m->codigo_confirmacion = strtoupper(substr(md5(uniqid()), 0, 8));
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopeActivas($q)    { return $q->whereIn('estatus', ['pendiente', 'confirmada']); }
    public function scopeDeHoy($q)      { return $q->whereDate('fecha', today()); }

    // ─── Relaciones ──────────────────────────────────────────────
    public function user()    { return $this->belongsTo(User::class); }
    public function modulo()  { return $this->belongsTo(ModuloSat::class, 'modulo_sat_id'); }
}


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
        'tipo',      // 'mensaje' | 'queja' | 'sugerencia' | 'denuncia'
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

    public function user() { return $this->belongsTo(User::class); }
}


class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'slug',
        'resumen',
        'contenido',
        'imagen',
        'categoria',  // 'personas' | 'empresas' | 'general' | 'normatividad'
        'activo',
        'fecha',
        'autor',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha'  => 'date',
    ];

    public function scopeActivas($q) { return $q->where('activo', true); }
    public function scopeRecientes($q, $n = 3) { return $q->orderByDesc('fecha')->take($n); }
}


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

    public function scopeActivos($q)           { return $q->where('activo', true); }
    public function scopePorEstado($q, $estado){ return $q->where('estado', $estado); }

    public function citas() { return $this->hasMany(Cita::class); }
}