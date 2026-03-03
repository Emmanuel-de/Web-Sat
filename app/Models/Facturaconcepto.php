<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaConcepto extends Model
{
    use HasFactory;

    protected $table = 'factura_conceptos';

    protected $fillable = [
        'factura_id',
        'clave_prod_serv',   // Catálogo SAT c_ClaveProdServ
        'clave_unidad',      // Catálogo SAT c_ClaveUnidad
        'descripcion',
        'cantidad',
        'valor_unitario',
        'descuento',
        'importe',
        'objeto_imp',        // '01' no objeto, '02' si objeto
        'iva_tasa',          // 0.16 por defecto
        'iva_importe',
    ];

    protected $casts = [
        'cantidad'      => 'decimal:6',
        'valor_unitario'=> 'decimal:6',
        'descuento'     => 'decimal:6',
        'importe'       => 'decimal:6',
        'iva_tasa'      => 'decimal:6',
        'iva_importe'   => 'decimal:6',
    ];

    // ─── Relaciones ──────────────────────────────────────────────
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}