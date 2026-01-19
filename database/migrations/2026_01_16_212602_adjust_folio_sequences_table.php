<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('folio_sequences', function (Blueprint $table) {

            // 🔒 Eliminar índice compuesto si existe
            try {
                $table->dropUnique(['year', 'id_seccion']);
            } catch (\Throwable $e) {
                // índice no existe, continuar
            }

            // ❌ Eliminar columna id_seccion SOLO si existe
            if (Schema::hasColumn('folio_sequences', 'id_seccion')) {
                $table->dropColumn('id_seccion');
            }
        });

        // ✅ Crear índice único por year si no existe
        Schema::table('folio_sequences', function (Blueprint $table) {
            try {
                $table->unique('year');
            } catch (\Throwable $e) {
                // índice ya existe
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback seguro
        Schema::table('folio_sequences', function (Blueprint $table) {
            $table->dropUnique(['year']);
            $table->unsignedBigInteger('id_seccion')->after('year');
            $table->unique(['year', 'id_seccion']);
        });
    }
};
