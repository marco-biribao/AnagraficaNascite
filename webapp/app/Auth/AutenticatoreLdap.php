<?php

namespace App\Auth;

use App\Models\User;
use App\Services\LdapAuthenticator;

/**
 * Autentica su Active Directory (tramite il bind LDAP di LdapAuthenticator)
 * e sincronizza l'utente locale corrispondente. I ruoli applicativi non
 * vengono mai toccati qui: restano una tabella locale gestita dagli
 * amministratori (vedi UtenteController).
 */
class AutenticatoreLdap implements Autenticatore
{
    public function __construct(private readonly LdapAuthenticator $ldap) {}

    public function autentica(string $username, string $password): RisultatoAutenticazione
    {
        $utenteEsistente = User::where('username', $username)->first();

        if ($utenteEsistente && ! $utenteEsistente->is_active) {
            return RisultatoAutenticazione::fallita('disabilitato');
        }

        $esitoLdap = $this->ldap->autentica($username, $password);

        if (! $esitoLdap) {
            return RisultatoAutenticazione::fallita('credenziali');
        }

        $utente = User::updateOrCreate(
            ['username' => $username],
            [
                'name' => $esitoLdap['nome'],
                'email' => $esitoLdap['email'],
                'guid' => $esitoLdap['guid'],
                'auth_source' => 'ldap',
                // is_active non viene toccato se l'utente esiste gia': solo
                // un amministratore puo' riattivarlo dalla pagina Utenti.
                'is_active' => $utenteEsistente->is_active ?? true,
            ]
        );

        return RisultatoAutenticazione::ok($utente);
    }
}
