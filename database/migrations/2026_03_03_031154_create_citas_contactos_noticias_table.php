<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ─── MÓDULOS SAT ──────────────────────────────────────
        Schema::create('modulos_sat', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('estado', 60)->index();
            $table->string('municipio', 80);
            $table->string('direccion', 300);
            $table->string('horario', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->boolean('activo')->default(true);
            $table->json('servicios')->nullable();  // ['RFC','EFIRMA','CIF',...]
            $table->timestamps();
        });

        // ─── CITAS ────────────────────────────────────────────
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('folio', 30)->unique();
            $table->string('rfc', 13);
            $table->string('curp', 18);
            $table->string('nombre', 150);
            $table->string('email', 100);
            $table->string('telefono', 10)->nullable();
            $table->foreignId('modulo_sat_id')->nullable()->constrained('modulos_sat')->nullOnDelete();
            $table->string('tramite', 20);
            $table->date('fecha')->index();
            $table->string('horario', 5);
            $table->text('observaciones')->nullable();
            $table->enum('estatus', ['pendiente', 'confirmada', 'cancelada', 'atendida'])->default('confirmada');
            $table->string('codigo_confirmacion', 10)->nullable();
            $table->timestamps();

            $table->index(['modulo_sat_id', 'fecha', 'horario']);
        });

        // ─── CONTACTOS / QUEJAS ───────────────────────────────
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombre', 150)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->enum('tipo', ['mensaje', 'queja', 'sugerencia', 'reconocimiento', 'denuncia']);
            $table->string('tema', 30)->nullable();
            $table->string('area', 20)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('mensaje')->nullable();
            $table->string('folio', 25)->unique();
            $table->enum('estatus', ['nuevo', 'en_proceso', 'resuelto', 'cerrado'])->default('nuevo');
            $table->text('respuesta')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->boolean('acepta_privacidad')->default(false);
            $table->timestamps();

            $table->index(['tipo', 'estatus']);
        });

        // ─── NOTICIAS ─────────────────────────────────────────
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 300);
            $table->string('slug', 300)->unique();
            $table->text('resumen')->nullable();
            $table->longText('contenido')->nullable();
            $table->string('imagen')->nullable();
            $table->enum('categoria', ['personas', 'empresas', 'general', 'normatividad'])->default('general');
            $table->boolean('activo')->default(true);
            $table->date('fecha');
            $table->string('autor', 100)->default('SAT');
            $table->timestamps();

            $table->index(['activo', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('citas');
        Schema::dropIfExists('modulos_sat');
    }
};