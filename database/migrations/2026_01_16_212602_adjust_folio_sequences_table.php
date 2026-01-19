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
        // 1️⃣ Eliminar índice solo si existe
        $indexExists = DB::select("
            SELECT 1
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
            AND table_name = 'folio_sequences'
            AND index_name = 'folio_sequences_year_id_seccion_unique'
            LIMIT 1
        ");

        if (!empty($indexExists)) {
            Schema::table('folio_sequences', function ($table) {
                $table->dropUnique('folio_sequences_year_id_seccion_unique');
            });
        }

        // 2️⃣ Eliminar columna solo si existe
        if (Schema::hasColumn('folio_sequences', 'id_seccion')) {
            Schema::table('folio_sequences', function ($table) {
                $table->dropColumn('id_seccion');
            });
        }

        // 3️⃣ Crear índice correcto solo si no existe
        $yearIndexExists = DB::select("
            SELECT 1
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
            AND table_name = 'folio_sequences'
            AND index_name = 'folio_sequences_year_unique'
            LIMIT 1
        ");

        if (empty($yearIndexExists)) {
            Schema::table('folio_sequences', function ($table) {
                $table->unique('year');
            });
        }
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
