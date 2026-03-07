<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'user_id', 'folio', 'rfc', 'curp', 'nombre',
        'email', 'telefono', 'modulo_sat_id', 'tramite',
        'fecha', 'horario', 'observaciones', 'estatus',
        'codigo_confirmacion',
    ];

    protected $casts = ['fecha' => 'date'];

    protected static function booted(): void
    {
        static::creating(function ($m) {
            $m->folio               = 'CIT-' . strtoupper(uniqid());
            $m->estatus             = 'confirmada';
            $m->codigo_confirmacion = strtoupper(substr(md5(uniqid()), 0, 8));
        });
    }

    public function scopeActivas($q) { return $q->whereIn('estatus', ['pendiente', 'confirmada']); }
    public function scopeDeHoy($q)   { return $q->whereDate('fecha', today()); }

    public function user()   { return $this->belongsTo(User::class); }
    public function modulo() { return $this->belongsTo(ModuloSat::class, 'modulo_sat_id'); }
}