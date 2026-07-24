<?php

namespace App\Auth;

use App\Models\User;

/**
 * Esito di un tentativo di autenticazione: invece di un semplice null in
 * caso di fallimento, porta con se' il motivo (credenziali errate vs
 * utenza disabilitata), cosi' il controller puo' mostrare un messaggio
 * specifico senza conoscere i dettagli della strategia usata.
 */
final class RisultatoAutenticazione
{
    private function __construct(
        public readonly bool $riuscita,
        public readonly ?User $utente = null,
        public readonly ?string $motivo = null,
    ) {}

    public static function ok(User $utente): self
    {
        return new self(true, $utente);
    }

    public static function fallita(string $motivo = 'credenziali'): self
    {
        return new self(false, null, $motivo);
    }
}
