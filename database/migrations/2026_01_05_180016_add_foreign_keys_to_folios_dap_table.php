<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('folios_dap', function (Blueprint $table) {
            $table->foreign(['responsable'], 'id_responsable')->references(['id'])->on('usuarios_dap')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_seccion'], 'id_seccion')->references(['id_seccion'])->on('secciones_dap')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folios_dap', function (Blueprint $table) {
            $table->dropForeign('id_responsable');
            $table->dropForeign('id_seccion');
        });
    }
};
