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
        Schema::create('dichiarazioni_nascita', function (Blueprint $table) {
            $table->id();

            $table->foreignId('modello_dichiarazione_id')->constrained('modelli_dichiarazione');
            $table->foreignId('dichiarante_id')->constrained('dichiaranti');

            // Atto
            $table->date('data_atto');
            $table->time('ora_atto');
            $table->unsignedInteger('numero_atto');
            $table->unsignedSmallInteger('anno_atto')->storedAs('YEAR(data_atto)');

            // Nascituro
            $table->string('nome_nascituro');
            $table->string('cognome_nascituro');
            $table->enum('sesso_nascituro', ['MASCHILE', 'FEMMINILE']);
            $table->date('data_nascita');
            $table->time('ora_nascita');
            $table->string('comune_trascrizione_nascita');
            $table->string('nome_nascituro_straniero_art24')->nullable();
            $table->string('cognome_nascituro_straniero_art24')->nullable();
            $table->string('cognome_concordato')->nullable();

            // Padre (assente per modello B/B1)
            $table->string('cognome_padre')->nullable();
            $table->string('nome_padre')->nullable();
            $table->string('citta_nascita_padre')->nullable();
            $table->string('provincia_nascita_padre')->nullable();
            $table->date('data_nascita_padre')->nullable();
            $table->string('comune_residenza_padre')->nullable();
            $table->string('cittadinanza_padre')->nullable();

            // Madre (assente per modello C/C1)
            $table->string('cognome_madre')->nullable();
            $table->string('nome_madre')->nullable();
            $table->string('citta_nascita_madre')->nullable();
            $table->string('provincia_nascita_madre')->nullable();
            $table->date('data_nascita_madre')->nullable();
            $table->string('comune_residenza_madre')->nullable();
            $table->string('cittadinanza_madre')->nullable();

            // Parto plurimo (modelli A1/B1/C1/D1)
            $table->unsignedInteger('numero_atto_gemello')->nullable();
            $table->string('codice_atto_gemello')->nullable();
            $table->string('ordine_nascita_gemello')->nullable();

            // Pagina 2 - trasmissione al Comune
            $table->date('data_spedizione_raccomandata')->nullable();
            $table->date('data_invio_comunicazione_telematica')->nullable();
            $table->string('numero_protocollo')->nullable();
            $table->string('comune_destinatario')->nullable();
            $table->string('comune_di_trascrizione')->nullable();
            $table->string('conferma_avvenuta_trascrizione')->nullable();
            $table->string('numero_atto_comune')->nullable();
            $table->unsignedSmallInteger('anno_trascrizione')->nullable();
            $table->text('note')->nullable();

            // Tracciabilita'
            $table->foreignId('operatore_id')->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['anno_atto', 'numero_atto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dichiarazioni_nascita');
    }
};
