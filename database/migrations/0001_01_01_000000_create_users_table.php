<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Campos comunes
            $table->enum('tipo', ['fisica', 'moral'])->default('fisica');
            $table->string('rfc', 13)->unique();
            $table->string('email')->unique();
            $table->string('telefono', 10)->nullable();
            $table->string('password');
            
            // Campos para Persona Física
            $table->string('curp', 18)->nullable()->unique();
            $table->string('nombres', 80)->nullable();
            $table->string('primer_apellido', 50)->nullable();
            $table->string('segundo_apellido', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            
            // Campos para Persona Moral
            $table->string('razon_social', 200)->nullable();
            $table->string('tipo_sociedad')->nullable();
            $table->string('rep_nombre')->nullable();
            $table->string('rep_rfc', 13)->nullable();

            $table->boolean('activo')->default(true);
            // ESTA ES LA LÍNEA QUE TE FALTA:
            $table->boolean('acepta_notificaciones')->default(false); 

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void 
    { 
        Schema::dropIfExists('users'); 
    }
};