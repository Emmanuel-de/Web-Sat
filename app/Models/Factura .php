<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';

    protected $fillable = [
        'user_id',
        'uuid',
        // Emisor
        'rfc_emisor',
        'nombre_emisor',
        'regimen_emisor',
        // Receptor
        'rfc_receptor',
        'nombre_receptor',
        'cp_receptor',
        'regimen_receptor',
        'uso_cfdi',
        // Comprobante
        'tipo_comprobante',  // I | E | T | N
        'serie',
        'folio',
        'metodo_pago',       // PUE | PPD
        'forma_pago',        // 01 Efectivo, 03 Transferencia...
        'moneda',
        'exportacion',
        // Totales
        'subtotal',
        'descuento',
        'iva',
        'total',
        // Metadatos
        'estatus',           // 'vigente' | 'cancelado' | 'pendiente_cancelacion'
        'motivo_cancelacion',
        'uuid_sustituto',
        'fecha_timbrado',
        'fecha_cancelacion',
        'noCertificadoSAT',
        'selloCFD',
        'selloSAT',
        'xml_path',
        'pdf_path',
    ];

    protected $casts = [
        'subtotal'          => 'decimal:6',
        'descuento'         => 'decimal:6',
        'iva'               => 'decimal:6',
        'total'             => 'decimal:6',
        'fecha_timbrado'    => 'datetime',
        'fecha_cancelacion' => 'datetime',
    ];

    // ─── Boot ────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid   = $model->uuid   ?? (string) Str::uuid();
            $model->estatus = $model->estatus ?? 'vigente';
            $model->fecha_timbrado = now();
            // Folio auto-incremental por emisor
            $ultimo = self::where('rfc_emisor', $model->rfc_emisor)->max('folio');
            $model->folio = ($ultimo ?? 0) + 1;
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopeVigentes($q)   { return $q->where('estatus', 'vigente'); }
    public function scopeCanceladas($q) { return $q->where('estatus', 'cancelado'); }
    public function scopeDelEmisor($q, $rfc) { return $q->where('rfc_emisor', $rfc); }
    public function scopeDelReceptor($q, $rfc){ return $q->where('rfc_receptor', $rfc); }

    // ─── Relaciones ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conceptos()
    {
        return $this->hasMany(FacturaConcepto::class);
    }
}