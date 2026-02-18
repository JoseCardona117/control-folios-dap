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
        Schema::create('minutas_externas', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->nullable();
            $table->string('motivo');
            $table->date('fecha_reunion');
            $table->string('convoca');
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
        Schema::dropIfExists('minutas_externas');
    }
};
