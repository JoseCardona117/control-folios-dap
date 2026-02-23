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
        Schema::create('acuerdos_externos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('minuta_id')
                ->constrained('minutas_externas')
                ->cascadeOnDelete();
            $table->text('description');
            $table->string('responsable');
            $table->enum('estado',['pendiente','en_proceso','cumplido', 'no_cumplido'])
                ->default('pendiente');

            //Fechas de seguimiento
            $table->date('fecha_compromiso')->nullable();
            $table->date('fecha_cumplimiento')->nullable();
            
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuerdos_externos');
    }
};
