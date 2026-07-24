<?php

namespace App\Services;

use App\Models\Dichiarante;
use App\Models\DichiarazioneNascita;
use App\Models\ReportTemplate;
use App\Models\User;

/**
 * Costruisce una dichiarazione di esempio (non persistita) da usare
 * nell'anteprima di un template quando non esiste ancora nessuna
 * dichiarazione reale per quel modello. Isolata dal controller cosi' la
 * logica di costruzione dei dati fittizi resta testabile e riusabile per
 * conto proprio.
 */
class DichiarazioneEsempioFactory
{
    public static function perTemplate(ReportTemplate $reportTemplate): DichiarazioneNascita
    {
        $esempio = new DichiarazioneNascita([
            'numero_atto' => 1,
            'data_atto' => now(),
            'ora_atto' => '10:00',
            'nome_nascituro' => 'MARIO',
            'cognome_nascituro' => 'ESEMPIO',
            'sesso_nascituro' => 'MASCHILE',
            'data_nascita' => now()->subDay(),
            'ora_nascita' => '08:00',
            'comune_trascrizione_nascita' => 'FOLIGNO',
            'cognome_padre' => 'ESEMPIO', 'nome_padre' => 'LUIGI',
            'citta_nascita_padre' => 'FOLIGNO', 'provincia_nascita_padre' => 'PG',
            'data_nascita_padre' => '1980-01-01', 'comune_residenza_padre' => 'FOLIGNO', 'cittadinanza_padre' => 'ITALIANA',
            'cognome_madre' => 'ESEMPIO', 'nome_madre' => 'ANNA',
            'citta_nascita_madre' => 'FOLIGNO', 'provincia_nascita_madre' => 'PG',
            'data_nascita_madre' => '1982-01-01', 'comune_residenza_madre' => 'FOLIGNO', 'cittadinanza_madre' => 'ITALIANA',
            'codice_atto_gemello' => '2/'.now()->year, 'ordine_nascita_gemello' => 'PRIMO',
            'modello_dichiarazione_id' => $reportTemplate->modello_dichiarazione_id,
        ]);

        $esempio->setRelation('modello', $reportTemplate->modello);
        $esempio->setRelation('dichiarante', new Dichiarante(['descrizione' => 'Padre']));
        $esempio->setRelation('operatore', new User(['name' => 'Operatore di esempio']));

        return $esempio;
    }
}
