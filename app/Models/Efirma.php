<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EFirma extends Model
{
    use HasFactory;

    protected $table = 'e_firmas';

    protected $fillable = [
        // Datos comunes
        'tipo',               // nueva | renovacion | revocacion
        'rfc',
        'curp',
        'email',
        'telefono',
        'folio',
        'estatus',            // pendiente | confirmado | cancelado

        // Nueva / Renovación — datos personales
        'fecha_nacimiento',
        'primer_apellido',
        'segundo_apellido',
        'nombres',
        'tipo_identificacion',

        // Cita (nueva y renovación vía módulo)
        'estado_modulo',
        'modulo_efirma',
        'fecha_cita',
        'horario_cita',

        // Renovación
        'via_renovacion',     // satid | modulo

        // Revocación
        'no_serie',
        'motivo_revocacion',
        'descripcion_revocacion',

        // Archivos (rutas)
        'archivo_ine',
        'archivo_domicilio',
        'archivo_curp',
        'archivo_foto',
        'archivo_cer',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_cita'       => 'date',
    ];

    // ── Relación con Contribuyente (si existe el modelo) ──────────
    public function contribuyente()
    {
        return $this->belongsTo(Contribuyente::class, 'rfc', 'rfc');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeNuevas($q)       { return $q->where('tipo', 'nueva'); }
    public function scopeRenovaciones($q) { return $q->where('tipo', 'renovacion'); }
    public function scopeRevocaciones($q) { return $q->where('tipo', 'revocacion'); }
    public function scopeVigentes($q)     { return $q->where('estatus', 'confirmado'); }

    // ── Accessors ─────────────────────────────────────────────────
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->primer_apellido} {$this->segundo_apellido} {$this->nombres}");
    }

    public function getEstatusLabelAttribute(): string
    {
        return match($this->estatus) {
            'confirmado' => 'Confirmado',
            'cancelado'  => 'Cancelado',
            default      => 'Pendiente',
        };
    }

    // ── Genera folio único ─────────────────────────────────────────
    public static function generarFolio(string $tipo): string
    {
        $prefijos = ['nueva' => 'EF', 'renovacion' => 'RN', 'revocacion' => 'RV'];
        $prefijo  = $prefijos[$tipo] ?? 'EF';
        return $prefijo . '-' . strtoupper(substr(uniqid(), -8)) . '-' . date('Y');
    }

    // ── Agrega esto ───────────────────────────────────────────
    public static function generarNoSerie(): string
    {
    // 20 dígitos, formato similar al SAT
    return '200010' . date('Ym') . str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
    }
}