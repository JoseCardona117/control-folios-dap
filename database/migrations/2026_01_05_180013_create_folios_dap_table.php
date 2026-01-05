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
        Schema::create('folios_dap', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('folio');
            $table->integer('id_seccion')->index('id_seccion');
            $table->integer('responsable')->index('id_responsable');
            $table->string('asunto');
            $table->string('dirigido');
            $table->date('fecha');
            $table->string('archivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios_dap');
    }
};
