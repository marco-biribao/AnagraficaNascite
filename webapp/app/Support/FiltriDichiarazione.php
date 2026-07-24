<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Raccoglie i criteri di ricerca dell'elenco dichiarazioni in un unico
 * oggetto, cosi' il controller non deve leggere singolarmente ogni
 * parametro della richiesta e la logica di filtro puo' vivere nel modello
 * (scopeFiltra) invece che nel controller.
 */
final class FiltriDichiarazione
{
    public function __construct(
        public readonly ?string $ricerca = null,
        public readonly ?int $modelloId = null,
        public readonly ?int $annoAtto = null,
        public readonly bool $mostraEsclusi = false,
    ) {}

    public static function daRichiesta(Request $request): self
    {
        return new self(
            ricerca: $request->filled('ricerca') ? (string) $request->string('ricerca') : null,
            modelloId: $request->filled('modello_dichiarazione_id') ? (int) $request->input('modello_dichiarazione_id') : null,
            annoAtto: $request->filled('anno_atto') ? (int) $request->input('anno_atto') : null,
            mostraEsclusi: $request->boolean('mostra_esclusi'),
        );
    }
}
