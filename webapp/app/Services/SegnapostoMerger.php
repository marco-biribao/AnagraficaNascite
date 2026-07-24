<?php

namespace App\Services;

use App\Models\DichiarazioneNascita;

/**
 * Sostituisce nel contenuto di un template i segnaposto {{CAMPO}} con i
 * valori reali di una dichiarazione. E' una sostituzione di testo semplice
 * (nessun codice eseguibile), quindi resta sicura anche se il template e'
 * modificato da utenti non tecnici.
 */
class SegnapostoMerger
{
    public function unisci(string $contenuto, DichiarazioneNascita $dichiarazione): string
    {
        $segnaposto = DichiarazioneReportData::segnaposto($dichiarazione);

        $cerca = array_map(fn ($chiave) => '{{'.$chiave.'}}', array_keys($segnaposto));
        $sostituisci = array_map(fn ($valore) => e($valore), array_values($segnaposto));

        return str_replace($cerca, $sostituisci, $contenuto);
    }
}
