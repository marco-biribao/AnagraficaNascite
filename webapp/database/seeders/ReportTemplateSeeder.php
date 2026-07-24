<?php

namespace Database\Seeders;

use App\Models\ModelloDichiarazione;
use App\Models\ReportTemplate;
use Illuminate\Database\Seeder;

class ReportTemplateSeeder extends Seeder
{
    /**
     * Crea un template di stampa di base per ciascun modello di
     * dichiarazione e per la ricevuta. Sono pensati come punto di partenza:
     * il testo, i loghi e l'impaginazione si modificano poi liberamente
     * dall'editor, senza bisogno di intervento tecnico.
     */
    public function run(): void
    {
        $modelli = ModelloDichiarazione::all()->keyBy('codice');

        foreach ($modelli as $codice => $modello) {
            $slug = 'modello-'.str_replace('_', '-', strtolower($codice));

            ReportTemplate::updateOrCreate(
                ['slug' => $slug],
                [
                    'modello_dichiarazione_id' => $modello->id,
                    'nome' => 'Modello '.$codice,
                    'contenuto' => $this->contenutoModello($modello),
                    'versione' => 1,
                    'attivo' => true,
                ]
            );
        }

        ReportTemplate::updateOrCreate(
            ['slug' => 'ricevuta'],
            [
                'modello_dichiarazione_id' => $modelli->first()->id,
                'nome' => 'Ricevuta',
                'contenuto' => $this->contenutoRicevuta(),
                'versione' => 1,
                'attivo' => true,
            ]
        );
    }

    private function contenutoModello(ModelloDichiarazione $modello): string
    {
        $sezionePadre = $modello->richiede_dati_padre ? $this->sezionePadre() : '';
        $sezioneMadre = $modello->richiede_dati_madre ? $this->sezioneMadre() : '';
        $sezioneGemelli = $modello->parto_plurimo ? $this->sezioneGemelli() : '';

        return <<<HTML
        <div style="font-family: 'Times New Roman', serif; font-size: 12pt;">
            <p style="text-align:center; margin-bottom:0;">Servizio Sanitario Nazionale &ndash; Regione dell'Umbria</p>
            <p style="text-align:center; margin-top:0; font-size:10pt;">AZIENDA UNITA' SANITARIA LOCALE</p>
            <h2 style="text-align:center;">ATTO DI DICHIARAZIONE DI NASCITA</h2>
            <p style="text-align:center; font-style:italic;">{{MODELLO_DESCRIZIONE}}</p>

            <table style="width:100%; margin-top:1em;">
                <tr>
                    <td>Atto n. <strong>{{NUMERO_ATTO}}</strong> del {{DATA_ATTO}} alle ore {{ORA_ATTO}}</td>
                    <td style="text-align:right;">Modello {{MODELLO_CODICE}} &ndash; Codice atto {{CODICE_ATTO}}</td>
                </tr>
            </table>

            <p>
                Il/la sottoscritto/a <strong>{{DICHIARANTE}}</strong>, in data odierna, dichiara la nascita
                avvenuta {{DATA_NASCITA_LETTERE}} {{ORA_NASCITA_LETTERE}}, del/della bambino/a:
            </p>

            <table style="width:100%;">
                <tr><td style="width:30%;">Cognome e nome</td><td><strong>{{COGNOME_NASCITURO}} {{NOME_NASCITURO}}</strong></td></tr>
                <tr><td>Sesso</td><td>{{SESSO_NASCITURO}}</td></tr>
                <tr><td>Comune di trascrizione della nascita</td><td>{{COMUNE_TRASCRIZIONE_NASCITA}}</td></tr>
            </table>

            {$sezionePadre}
            {$sezioneMadre}
            {$sezioneGemelli}

            <p style="margin-top:2em;">
                Foligno, {{DATA_ATTO}} &ndash; L'operatore: {{OPERATORE}}
            </p>
        </div>
        HTML;
    }

    private function sezionePadre(): string
    {
        return <<<HTML
        <h3>Dati del padre</h3>
        <table style="width:100%;">
            <tr><td style="width:30%;">Cognome e nome</td><td>{{COGNOME_PADRE}} {{NOME_PADRE}}</td></tr>
            <tr><td>Nato a</td><td>{{CITTA_NASCITA_PADRE}} ({{PROVINCIA_NASCITA_PADRE}}) il {{DATA_NASCITA_PADRE}}</td></tr>
            <tr><td>Residente a</td><td>{{COMUNE_RESIDENZA_PADRE}}</td></tr>
            <tr><td>Cittadinanza</td><td>{{CITTADINANZA_PADRE}}</td></tr>
        </table>
        HTML;
    }

    private function sezioneMadre(): string
    {
        return <<<HTML
        <h3>Dati della madre</h3>
        <table style="width:100%;">
            <tr><td style="width:30%;">Cognome e nome</td><td>{{COGNOME_MADRE}} {{NOME_MADRE}}</td></tr>
            <tr><td>Nata a</td><td>{{CITTA_NASCITA_MADRE}} ({{PROVINCIA_NASCITA_MADRE}}) il {{DATA_NASCITA_MADRE}}</td></tr>
            <tr><td>Residente a</td><td>{{COMUNE_RESIDENZA_MADRE}}</td></tr>
            <tr><td>Cittadinanza</td><td>{{CITTADINANZA_MADRE}}</td></tr>
        </table>
        HTML;
    }

    private function sezioneGemelli(): string
    {
        return <<<HTML
        <h3>Parto plurimo</h3>
        <table style="width:100%;">
            <tr><td style="width:30%;">Atto gemello</td><td>{{CODICE_ATTO_GEMELLO}}</td></tr>
            <tr><td>Ordine di nascita</td><td>{{ORDINE_NASCITA_GEMELLO}}</td></tr>
        </table>
        HTML;
    }

    private function contenutoRicevuta(): string
    {
        return <<<HTML
        <div style="font-family: 'Times New Roman', serif; font-size: 12pt;">
            <h2 style="text-align:center;">RICEVUTA DI DICHIARAZIONE DI NASCITA</h2>
            <p>
                Si attesta che in data {{DATA_ATTO}} e' stata registrata la dichiarazione di nascita
                relativa a <strong>{{COGNOME_NASCITURO}} {{NOME_NASCITURO}}</strong>,
                atto n. {{CODICE_ATTO}} (modello {{MODELLO_CODICE}}).
            </p>
            <p>Dichiarante: {{DICHIARANTE}}</p>
            <p style="margin-top:2em;">Foligno, {{OGGI_DATA}} &ndash; L'operatore: {{OPERATORE}}</p>
        </div>
        HTML;
    }
}
