<?php

namespace App\Auth;

use App\Models\User;

/**
 * Sceglie quale strategia di autenticazione usare per un dato username: le
 * utenze marcate esplicitamente "local" passano sempre da
 * AutenticatoreLocale, tutte le altre (comprese quelle non ancora
 * sincronizzate da AD) da AutenticatoreLdap.
 */
class SelettoreAutenticatore
{
    public function __construct(
        private readonly AutenticatoreLocale $locale,
        private readonly AutenticatoreLdap $ldap,
    ) {}

    public function perUsername(string $username): Autenticatore
    {
        $utenteEsistente = User::where('username', $username)->first();

        if ($utenteEsistente && $utenteEsistente->auth_source === 'local') {
            return $this->locale;
        }

        return $this->ldap;
    }
}
