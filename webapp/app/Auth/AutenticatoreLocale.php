<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Verifica le utenze locali (auth_source=local), come l'amministratore di
 * emergenza usato quando Active Directory non e' raggiungibile. Non tocca
 * mai LDAP.
 */
class AutenticatoreLocale implements Autenticatore
{
    public function autentica(string $username, string $password): RisultatoAutenticazione
    {
        $utente = User::where('username', $username)
            ->where('auth_source', 'local')
            ->first();

        if (! $utente) {
            return RisultatoAutenticazione::fallita('credenziali');
        }

        if (! $utente->is_active) {
            return RisultatoAutenticazione::fallita('disabilitato');
        }

        if (! Hash::check($password, (string) $utente->password)) {
            return RisultatoAutenticazione::fallita('credenziali');
        }

        return RisultatoAutenticazione::ok($utente);
    }
}
