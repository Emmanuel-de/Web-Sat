<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitudes_rfc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('folio', 20)->unique();
            // Datos personales
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();
            $table->string('nombres', 80);
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['Hombre', 'Mujer']);   // ← coincide con el blade
            $table->string('estado_nacimiento', 50);
            $table->string('curp', 18)->unique();
            $table->string('rfc', 13)->nullable();        // ← solo una columna rfc
            $table->string('email', 100);
            $table->string('telefono', 10)->nullable();
            $table->string('tipo_identificacion', 20);
            // Domicilio fiscal
            $table->string('codigo_postal', 5);
            $table->string('estado', 50);
            $table->string('municipio', 80);
            $table->string('colonia', 100);
            $table->string('calle', 100);
            $table->string('no_exterior', 10);
            $table->string('no_interior', 10)->nullable();
            $table->string('entre_calles', 200)->nullable();
            // Actividad económica
            $table->string('regimen_fiscal', 10);
            $table->string('actividad_principal', 200);
            $table->text('descripcion_actividad')->nullable();
            $table->date('fecha_inicio_actividades');
            // Resultado
            $table->enum('estatus', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();

            $table->index(['curp', 'estatus']);
            $table->index('estatus');
        });
    }

    public function down(): void { Schema::dropIfExists('solicitudes_rfc'); }
};