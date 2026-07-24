<?php

namespace App\Http\Requests\Concerns;

use App\Models\ModelloDichiarazione;

trait ValidaCampiDichiarazione
{
    /**
     * Regole di validazione dei campi che dipendono dal modello di
     * dichiarazione selezionato: i dati del padre non sono richiesti per i
     * modelli B/B1 (riconoscimento solo materno), quelli della madre non
     * sono richiesti per i modelli C/C1 (riconoscimento solo paterno), i
     * campi del gemello sono richiesti solo per i modelli di parto plurimo.
     */
    protected function regoleCampiDichiarazione(?ModelloDichiarazione $modello): array
    {
        $richiedePadre = $modello?->richiede_dati_padre ?? true;
        $richiedeMadre = $modello?->richiede_dati_madre ?? true;
        $partoPlurimo = $modello?->parto_plurimo ?? false;

        $obbligatorioSe = fn (bool $condizione) => $condizione ? 'required' : 'nullable';

        return [
            'modello_dichiarazione_id' => ['required', 'integer', 'exists:modelli_dichiarazione,id'],
            'dichiarante_id' => ['required', 'integer', 'exists:dichiaranti,id'],

            'data_atto' => ['required', 'date'],
            'ora_atto' => ['required', 'date_format:H:i'],
            'numero_atto' => ['required', 'integer', 'min:1'],

            'nome_nascituro' => ['required', 'string', 'max:255'],
            'cognome_nascituro' => ['required', 'string', 'max:255'],
            'sesso_nascituro' => ['required', 'in:MASCHILE,FEMMINILE'],
            'data_nascita' => ['required', 'date', 'before_or_equal:data_atto'],
            'ora_nascita' => ['required', 'date_format:H:i'],
            'comune_trascrizione_nascita' => ['required', 'string', 'max:255'],
            'nome_nascituro_straniero_art24' => ['nullable', 'string', 'max:255'],
            'cognome_nascituro_straniero_art24' => ['nullable', 'string', 'max:255'],
            'cognome_concordato' => ['nullable', 'string', 'max:255'],

            'cognome_padre' => [$obbligatorioSe($richiedePadre), 'string', 'max:255'],
            'nome_padre' => [$obbligatorioSe($richiedePadre), 'string', 'max:255'],
            'citta_nascita_padre' => [$obbligatorioSe($richiedePadre), 'string', 'max:255'],
            'provincia_nascita_padre' => ['nullable', 'string', 'max:255'],
            'data_nascita_padre' => [$obbligatorioSe($richiedePadre), 'date'],
            'comune_residenza_padre' => [$obbligatorioSe($richiedePadre), 'string', 'max:255'],
            'cittadinanza_padre' => [$obbligatorioSe($richiedePadre), 'string', 'max:255'],

            'cognome_madre' => [$obbligatorioSe($richiedeMadre), 'string', 'max:255'],
            'nome_madre' => [$obbligatorioSe($richiedeMadre), 'string', 'max:255'],
            'citta_nascita_madre' => [$obbligatorioSe($richiedeMadre), 'string', 'max:255'],
            'provincia_nascita_madre' => ['nullable', 'string', 'max:255'],
            'data_nascita_madre' => [$obbligatorioSe($richiedeMadre), 'date'],
            'comune_residenza_madre' => [$obbligatorioSe($richiedeMadre), 'string', 'max:255'],
            'cittadinanza_madre' => [$obbligatorioSe($richiedeMadre), 'string', 'max:255'],

            'numero_atto_gemello' => [$obbligatorioSe($partoPlurimo), 'integer', 'min:1'],
            'codice_atto_gemello' => [$obbligatorioSe($partoPlurimo), 'string', 'max:255'],
            'ordine_nascita_gemello' => [$obbligatorioSe($partoPlurimo), 'string', 'max:255'],

            'data_spedizione_raccomandata' => ['nullable', 'date'],
            'data_invio_comunicazione_telematica' => ['nullable', 'date'],
            'numero_protocollo' => ['nullable', 'string', 'max:255'],
            'comune_destinatario' => ['nullable', 'string', 'max:255'],
            'comune_di_trascrizione' => ['nullable', 'string', 'max:255'],
            'conferma_avvenuta_trascrizione' => ['nullable', 'string', 'max:255'],
            'numero_atto_comune' => ['nullable', 'string', 'max:255'],
            'anno_trascrizione' => ['nullable', 'integer', 'min:2000', 'max:'.(now()->year + 1)],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
