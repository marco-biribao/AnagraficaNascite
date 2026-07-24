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
        Schema::create('modelli_dichiarazione', function (Blueprint $table) {
            $table->id();
            $table->string('codice')->unique(); // A, A1, B, B1, C, C1, D, D1, A_madre, A1_madre
            $table->string('descrizione');
            $table->boolean('richiede_dati_padre')->default(true);
            $table->boolean('richiede_dati_madre')->default(true);
            $table->boolean('parto_plurimo')->default(false);
            $table->string('dichiarante_predefinito')->nullable(); // codice in dichiaranti, es. PADRE/MADRE
            $table->unsignedInteger('ordine')->default(0);
            $table->boolean('attivo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelli_dichiarazione');
    }
};
