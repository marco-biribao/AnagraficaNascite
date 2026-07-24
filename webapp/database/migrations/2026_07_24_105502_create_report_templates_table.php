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
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modello_dichiarazione_id')->constrained('modelli_dichiarazione');
            $table->string('nome'); // es. "Modello A", "Ricevuta"
            $table->string('slug')->unique(); // es. modello-a, ricevuta
            $table->longText('contenuto'); // HTML con segnaposto {{NOME_MADRE}}
            $table->unsignedInteger('versione')->default(1);
            $table->boolean('attivo')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
