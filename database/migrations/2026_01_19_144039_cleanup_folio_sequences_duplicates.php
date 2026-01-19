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
        // Obtener años duplicados
        $years = DB::table('folio_sequences')
            ->select('year')
            ->groupBy('year')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('year');

        foreach ($years as $year) {
            // Obtener el mayor last_number para ese año
            $maxLastNumber = DB::table('folio_sequences')
                ->where('year', $year)
                ->max('last_number');

            // Eliminar todas las filas de ese año
            DB::table('folio_sequences')
                ->where('year', $year)
                ->delete();

            // Insertar una sola fila consolidada
            DB::table('folio_sequences')->insert([
                'year' => $year,
                'last_number' => $maxLastNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
