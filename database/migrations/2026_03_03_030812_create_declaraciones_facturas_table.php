<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ─── DECLARACIONES ────────────────────────────────────
        Schema::create('declaraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rfc', 13)->index();
            $table->string('no_operacion', 20)->unique();
            $table->enum('tipo', ['anual', 'provisional', 'complementaria']);
            $table->string('impuesto', 10)->nullable();   // ISR, IVA, IEPS
            $table->smallInteger('ejercicio');
            $table->tinyInteger('periodo')->nullable();   // 1-12
            $table->enum('tipo_declaracion', ['normal', 'complementaria', 'extemporanea'])->default('normal');
            // Ingresos
            $table->decimal('ingresos_acumulables', 15, 2)->default(0);
            $table->decimal('ingresos_exentos', 15, 2)->default(0);
            // Deducciones
            $table->decimal('honorarios_medicos', 15, 2)->default(0);
            $table->decimal('gastos_hospitalarios', 15, 2)->default(0);
            $table->decimal('primas_seguro', 15, 2)->default(0);
            $table->decimal('colegiaturas', 15, 2)->default(0);
            $table->decimal('intereses_hipotecarios', 15, 2)->default(0);
            $table->decimal('donativos', 15, 2)->default(0);
            // ISR calculado
            $table->decimal('base_gravable', 15, 2)->default(0);
            $table->decimal('isr_determinado', 15, 2)->default(0);
            $table->decimal('isr_retenido', 15, 2)->default(0);
            $table->decimal('saldo_cargo', 15, 2)->default(0);
            $table->decimal('saldo_favor', 15, 2)->default(0);
            $table->decimal('importe', 15, 2)->default(0);
            // Metadatos
            $table->timestamp('fecha_presentacion')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->enum('estatus', ['presentada', 'en_proceso', 'error'])->default('presentada');
            $table->string('acuse_path')->nullable();
            $table->string('no_operacion_original', 20)->nullable();
            $table->text('motivo')->nullable();
            $table->timestamps();

            $table->index(['rfc', 'ejercicio', 'tipo']);
        });

        // ─── FACTURAS ─────────────────────────────────────────
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('uuid')->unique();
            // Emisor
            $table->string('rfc_emisor', 13)->index();
            $table->string('nombre_emisor', 300);
            $table->string('regimen_emisor', 10);
            // Receptor
            $table->string('rfc_receptor', 13)->index();
            $table->string('nombre_receptor', 300);
            $table->string('cp_receptor', 5);
            $table->string('regimen_receptor', 10);
            $table->string('uso_cfdi', 3);
            // Comprobante
            $table->char('tipo_comprobante', 1)->default('I');
            $table->string('serie', 25)->nullable();
            $table->unsignedBigInteger('folio')->default(1);
            $table->string('metodo_pago', 3);
            $table->string('forma_pago', 2);
            $table->char('moneda', 3)->default('MXN');
            $table->char('exportacion', 2)->default('01');
            // Totales
            $table->decimal('subtotal', 20, 6)->default(0);
            $table->decimal('descuento', 20, 6)->default(0);
            $table->decimal('iva', 20, 6)->default(0);
            $table->decimal('total', 20, 6)->default(0);
            // Estado
            $table->enum('estatus', ['vigente', 'cancelado', 'pendiente_cancelacion'])->default('vigente');
            $table->string('motivo_cancelacion', 2)->nullable();
            $table->uuid('uuid_sustituto')->nullable();
            $table->timestamp('fecha_timbrado')->nullable();
            $table->timestamp('fecha_cancelacion')->nullable();
            // Sellos
            $table->string('noCertificadoSAT', 20)->nullable();
            $table->text('selloCFD')->nullable();
            $table->text('selloSAT')->nullable();
            $table->string('xml_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['rfc_emisor', 'estatus']);
            $table->index('fecha_timbrado');
        });

        // ─── CONCEPTOS DE FACTURA ─────────────────────────────
        Schema::create('factura_conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas')->cascadeOnDelete();
            $table->string('clave_prod_serv', 8);
            $table->string('clave_unidad', 3);
            $table->string('descripcion', 1000);
            $table->decimal('cantidad', 20, 6);
            $table->decimal('valor_unitario', 20, 6);
            $table->decimal('descuento', 20, 6)->default(0);
            $table->decimal('importe', 20, 6);
            $table->char('objeto_imp', 2)->default('02');
            $table->decimal('iva_tasa', 10, 6)->default(0.160000);
            $table->decimal('iva_importe', 20, 6)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_conceptos');
        Schema::dropIfExists('facturas');
        Schema::dropIfExists('declaraciones');
    }
};
