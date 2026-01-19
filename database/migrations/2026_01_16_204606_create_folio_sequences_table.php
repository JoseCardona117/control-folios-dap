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
        Schema::create('folio_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            //$table->unsignedBigInteger('id_seccion');
            $table->unsignedBigInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(['year', 'id_seccion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folio_sequences');
    }
};
