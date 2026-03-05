<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE declaraciones 
            MODIFY COLUMN tipo 
            ENUM('anual','provisional','complementaria','mensual','diot') 
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE declaraciones 
            MODIFY COLUMN tipo 
            ENUM('anual','provisional','complementaria') 
            NOT NULL
        ");
    }
};