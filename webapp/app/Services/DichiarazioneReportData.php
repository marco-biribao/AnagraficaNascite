<?php

namespace App\Services;

use App\Models\DichiarazioneNascita;
use App\Support\NumeroInLettere;
use Illuminate\Support\Carbon;

/**
 * Costruisce la mappa segnaposto => valore usata per il merge dei template
 * dei report. I segnaposto "in lettere" (date/orari scritti a parole) non
 * sono colonne del database: si calcolano qui al momento della stampa, cosi'
 * restano sempre corretti senza bisogno di doppia digitazione (a differenza
 * del vecchio archivio EpiInfo, dove operatori e sviluppatori dovevano
 * inserire/aggiornare a mano i campi "in lettere").
 */
class DichiarazioneReportData
{
    public static function segnaposto(DichiarazioneNascita $dichiarazione): array
    {
        $formatData = fn (?Carbon $data) => $data?->format('d/m/Y') ?? '';
        $formatOra = fn (?Carbon $ora) => $ora?->format('H:i') ?? '';

        return [
            'MODELLO_CODICE' => $dichiarazione->modello->codice,
            'MODELLO_DESCRIZIONE' => $dichiarazione->modello->descrizione,
            'DICHIARANTE' => $dichiarazione->dichiarante->descrizione,

            'NUMERO_ATTO' => (string) $dichiarazione->numero_atto,
            'ANNO_ATTO' => (string) $dichiarazione->anno_atto,
            'CODICE_ATTO' => $dichiarazione->codice_atto,
            'DATA_ATTO' => $formatData($dichiarazione->data_atto),
            'ORA_ATTO' => $formatOra($dichiarazione->ora_atto),
            'DATA_ATTO_LETTERE' => NumeroInLettere::dataInLettere($dichiarazione->data_atto),
            'ORA_ATTO_LETTERE' => NumeroInLettere::oraInLettere($formatOra($dichiarazione->ora_atto)),

            'NOME_NASCITURO' => $dichiarazione->nome_nascituro,
            'COGNOME_NASCITURO' => $dichiarazione->cognome_nascituro,
            'SESSO_NASCITURO' => ucfirst(strtolower($dichiarazione->sesso_nascituro)),
            'DATA_NASCITA' => $formatData($dichiarazione->data_nascita),
            'ORA_NASCITA' => $formatOra($dichiarazione->ora_nascita),
            'DATA_NASCITA_LETTERE' => NumeroInLettere::dataInLettere($dichiarazione->data_nascita),
            'ORA_NASCITA_LETTERE' => NumeroInLettere::oraInLettere($formatOra($dichiarazione->ora_nascita)),
            'COMUNE_TRASCRIZIONE_NASCITA' => $dichiarazione->comune_trascrizione_nascita,
            'NOME_NASCITURO_STRANIERO_ART24' => $dichiarazione->nome_nascituro_straniero_art24 ?? '',
            'COGNOME_NASCITURO_STRANIERO_ART24' => $dichiarazione->cognome_nascituro_straniero_art24 ?? '',
            'COGNOME_CONCORDATO' => $dichiarazione->cognome_concordato ?? '',

            'COGNOME_PADRE' => $dichiarazione->cognome_padre ?? '',
            'NOME_PADRE' => $dichiarazione->nome_padre ?? '',
            'CITTA_NASCITA_PADRE' => $dichiarazione->citta_nascita_padre ?? '',
            'PROVINCIA_NASCITA_PADRE' => $dichiarazione->provincia_nascita_padre ?? '',
            'DATA_NASCITA_PADRE' => $formatData($dichiarazione->data_nascita_padre),
            'COMUNE_RESIDENZA_PADRE' => $dichiarazione->comune_residenza_padre ?? '',
            'CITTADINANZA_PADRE' => $dichiarazione->cittadinanza_padre ?? '',

            'COGNOME_MADRE' => $dichiarazione->cognome_madre ?? '',
            'NOME_MADRE' => $dichiarazione->nome_madre ?? '',
            'CITTA_NASCITA_MADRE' => $dichiarazione->citta_nascita_madre ?? '',
            'PROVINCIA_NASCITA_MADRE' => $dichiarazione->provincia_nascita_madre ?? '',
            'DATA_NASCITA_MADRE' => $formatData($dichiarazione->data_nascita_madre),
            'COMUNE_RESIDENZA_MADRE' => $dichiarazione->comune_residenza_madre ?? '',
            'CITTADINANZA_MADRE' => $dichiarazione->cittadinanza_madre ?? '',

            'NUMERO_ATTO_GEMELLO' => $dichiarazione->numero_atto_gemello ? (string) $dichiarazione->numero_atto_gemello : '',
            'CODICE_ATTO_GEMELLO' => $dichiarazione->codice_atto_gemello ?? '',
            'ORDINE_NASCITA_GEMELLO' => $dichiarazione->ordine_nascita_gemello ? ucfirst(strtolower($dichiarazione->ordine_nascita_gemello)) : '',

            'DATA_SPEDIZIONE_RACCOMANDATA' => $formatData($dichiarazione->data_spedizione_raccomandata),
            'DATA_INVIO_COMUNICAZIONE_TELEMATICA' => $formatData($dichiarazione->data_invio_comunicazione_telematica),
            'NUMERO_PROTOCOLLO' => $dichiarazione->numero_protocollo ?? '',
            'COMUNE_DESTINATARIO' => $dichiarazione->comune_destinatario ?? '',
            'COMUNE_DI_TRASCRIZIONE' => $dichiarazione->comune_di_trascrizione ?? '',
            'CONFERMA_AVVENUTA_TRASCRIZIONE' => $dichiarazione->conferma_avvenuta_trascrizione ?? '',
            'NUMERO_ATTO_COMUNE' => $dichiarazione->numero_atto_comune ?? '',
            'ANNO_TRASCRIZIONE' => $dichiarazione->anno_trascrizione ? (string) $dichiarazione->anno_trascrizione : '',
            'NOTE' => $dichiarazione->note ?? '',

            'OPERATORE' => $dichiarazione->operatore->name,
            'DATA_REGISTRAZIONE' => ($dichiarazione->created_at ?? now())->format('d/m/Y H:i'),

            'OGGI_DATA' => now()->format('d/m/Y'),
            'OGGI_DATA_LETTERE' => NumeroInLettere::dataInLettere(now()),
        ];
    }

    /**
     * Elenco dei segnaposto disponibili, per la sidebar dell'editor.
     *
     * @return string[]
     */
    public static function elencoSegnaposto(): array
    {
        return [
            'MODELLO_CODICE', 'MODELLO_DESCRIZIONE', 'DICHIARANTE',
            'NUMERO_ATTO', 'ANNO_ATTO', 'CODICE_ATTO', 'DATA_ATTO', 'ORA_ATTO', 'DATA_ATTO_LETTERE', 'ORA_ATTO_LETTERE',
            'NOME_NASCITURO', 'COGNOME_NASCITURO', 'SESSO_NASCITURO', 'DATA_NASCITA', 'ORA_NASCITA',
            'DATA_NASCITA_LETTERE', 'ORA_NASCITA_LETTERE', 'COMUNE_TRASCRIZIONE_NASCITA',
            'NOME_NASCITURO_STRANIERO_ART24', 'COGNOME_NASCITURO_STRANIERO_ART24', 'COGNOME_CONCORDATO',
            'COGNOME_PADRE', 'NOME_PADRE', 'CITTA_NASCITA_PADRE', 'PROVINCIA_NASCITA_PADRE',
            'DATA_NASCITA_PADRE', 'COMUNE_RESIDENZA_PADRE', 'CITTADINANZA_PADRE',
            'COGNOME_MADRE', 'NOME_MADRE', 'CITTA_NASCITA_MADRE', 'PROVINCIA_NASCITA_MADRE',
            'DATA_NASCITA_MADRE', 'COMUNE_RESIDENZA_MADRE', 'CITTADINANZA_MADRE',
            'NUMERO_ATTO_GEMELLO', 'CODICE_ATTO_GEMELLO', 'ORDINE_NASCITA_GEMELLO',
            'DATA_SPEDIZIONE_RACCOMANDATA', 'DATA_INVIO_COMUNICAZIONE_TELEMATICA', 'NUMERO_PROTOCOLLO',
            'COMUNE_DESTINATARIO', 'COMUNE_DI_TRASCRIZIONE', 'CONFERMA_AVVENUTA_TRASCRIZIONE',
            'NUMERO_ATTO_COMUNE', 'ANNO_TRASCRIZIONE', 'NOTE',
            'OPERATORE', 'DATA_REGISTRAZIONE', 'OGGI_DATA', 'OGGI_DATA_LETTERE',
        ];
    }
}
