<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Impedisce che un amministratore, modificando la propria stessa utenza,
 * si disattivi o si tolga l'ultimo ruolo di amministratore, restando
 * cosi' bloccato fuori dal sistema senza che nessun altro possa rimediare.
 * Isolata dal controller per essere verificabile per conto proprio.
 */
class ProtezioneAutoBlocco
{
    /**
     * @param  int[]  $ruoliSelezionati
     *
     * @throws ValidationException
     */
    public function verificaModificaUtente(User $attore, User $target, bool $restaAttivo, array $ruoliSelezionati): void
    {
        if ($attore->id !== $target->id) {
            return;
        }

        $restaAmministratore = Role::whereIn('id', $ruoliSelezionati)->where('slug', 'amministratore')->exists();

        if (! $restaAttivo || ! $restaAmministratore) {
            throw ValidationException::withMessages([
                'ruoli' => 'Non puoi disattivare la tua utenza o rimuovere a te stesso il ruolo di amministratore.',
            ]);
        }
    }
}
