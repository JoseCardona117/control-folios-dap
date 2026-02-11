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
        Schema::create('minutas_dap', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->string('motivo');
            $table->date('fecha_reunion');
            $table->string('convoca');
            $table->enum('estado', ['abierta', 'cerrada'])
                ->default('abierta');
            $table->text('observaciones')->nullable();
            $table->string('evidencia')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minutas_dap');
    }
};
