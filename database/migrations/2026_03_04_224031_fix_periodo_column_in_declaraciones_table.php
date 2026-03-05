<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cambiar periodo de INT a VARCHAR para aceptar 'Enero', 'Febrero', etc.
        DB::statement("
            ALTER TABLE declaraciones 
            MODIFY COLUMN periodo VARCHAR(50) NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE declaraciones 
            MODIFY COLUMN periodo TINYINT NULL
        ");
    }
};