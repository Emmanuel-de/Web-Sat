<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaracion extends Model
{
    use HasFactory;

    protected $table = 'declaraciones';

    protected $fillable = [
        'user_id',
        'rfc',
        'no_operacion',
        'tipo',              // 'anual' | 'provisional' | 'complementaria'
        'impuesto',          // 'ISR' | 'IVA' | 'IEPS'
        'ejercicio',
        'periodo',           // mes 1-12 o null para anual
        'tipo_declaracion',  // 'normal' | 'complementaria' | 'extemporanea'
        // Ingresos
        'ingresos_acumulables',
        'ingresos_exentos',
        // Deducciones
        'honorarios_medicos',
        'gastos_hospitalarios',
        'primas_seguro',
        'colegiaturas',
        'intereses_hipotecarios',
        'donativos',
        // ISR
        'base_gravable',
        'isr_determinado',
        'isr_retenido',
        'saldo_cargo',
        'saldo_favor',
        'importe',
        // Metadatos
        'fecha_presentacion',
        'fecha_limite',
        'estatus',           // 'presentada' | 'en_proceso' | 'error'
        'acuse_path',
        'no_operacion_original', // para complementarias
        'motivo',
    ];

    protected $casts = [
        'fecha_presentacion'    => 'datetime',
        'fecha_limite'          => 'date',
        'ingresos_acumulables'  => 'decimal:2',
        'ingresos_exentos'      => 'decimal:2',
        'honorarios_medicos'    => 'decimal:2',
        'gastos_hospitalarios'  => 'decimal:2',
        'primas_seguro'         => 'decimal:2',
        'colegiaturas'          => 'decimal:2',
        'intereses_hipotecarios'=> 'decimal:2',
        'donativos'             => 'decimal:2',
        'base_gravable'         => 'decimal:2',
        'isr_determinado'       => 'decimal:2',
        'isr_retenido'          => 'decimal:2',
        'saldo_cargo'           => 'decimal:2',
        'saldo_favor'           => 'decimal:2',
        'importe'               => 'decimal:2',
    ];

    // ─── Boot ────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->no_operacion = self::generarNoOperacion();
            $model->estatus      = $model->estatus ?? 'presentada';
            $model->fecha_presentacion = now();
        });
    }

    private static function generarNoOperacion(): string
    {
        return date('Y') . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
    }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopeAnuales($q)     { return $q->where('tipo', 'anual'); }
    public function scopeProvisionales($q){ return $q->where('tipo', 'provisional'); }
    public function scopePorRfc($q, $rfc){ return $q->where('rfc', $rfc); }

    // ─── Relaciones ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}