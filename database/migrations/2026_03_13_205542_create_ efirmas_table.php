<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('e_firmas', function (Blueprint $table) {
            $table->id();

            // ── Campos comunes ────────────────────────────────────
            $table->string('tipo', 20);                        // nueva | renovacion | revocacion
            $table->string('rfc', 13);
            $table->string('curp', 18)->nullable();
            $table->string('email', 150);
            $table->string('telefono', 15)->nullable();
            $table->string('folio', 30)->unique();
            $table->string('estatus', 20)->default('pendiente');// pendiente | confirmado | cancelado

            // ── Datos personales (nueva / renovación) ────────────
            $table->date('fecha_nacimiento')->nullable();
            $table->string('primer_apellido', 80)->nullable();
            $table->string('segundo_apellido', 80)->nullable();
            $table->string('nombres', 100)->nullable();
            $table->string('tipo_identificacion', 20)->nullable();

            // ── Cita ──────────────────────────────────────────────
            $table->string('estado_modulo', 60)->nullable();
            $table->string('modulo_efirma', 150)->nullable();
            $table->date('fecha_cita')->nullable();
            $table->string('horario_cita', 10)->nullable();

            // ── Renovación ────────────────────────────────────────
            $table->string('via_renovacion', 20)->nullable();  // satid | modulo

            // ── Revocación ────────────────────────────────────────
            $table->string('no_serie', 30)->nullable();
            $table->string('motivo_revocacion', 30)->nullable();
            $table->text('descripcion_revocacion')->nullable();

            // ── Archivos ──────────────────────────────────────────
            $table->string('archivo_ine', 255)->nullable();
            $table->string('archivo_domicilio', 255)->nullable();
            $table->string('archivo_curp', 255)->nullable();
            $table->string('archivo_foto', 255)->nullable();
            $table->string('archivo_cer', 255)->nullable();

            $table->timestamps();

            // ── Índices ───────────────────────────────────────────
            $table->index('rfc');
            $table->index('tipo');
            $table->index('estatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_firmas');
    }
};
