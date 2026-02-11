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
        Schema::create('acuerdos_dap', function (Blueprint $table) {
            $table->id();

            $table->foreignId('minuta_id')
                ->constrained('minutas_dap')
                ->cascadeOnDelete();
            $table->text('description');
            $table->string('responsable'); //Aquí veremos si se cambia por el id de la sección o de una persona en paricular o el código de sección. 
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
        Schema::dropIfExists('acuerdos_dap');
    }
};
