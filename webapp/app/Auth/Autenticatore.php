<?php

namespace App\Auth;

/**
 * Contratto comune a ogni strategia di autenticazione (locale, LDAP, ...).
 * AuthenticatedSessionController dipende solo da questa interfaccia: per
 * aggiungere una nuova fonte (es. SAML) non serve modificare il
 * controller, solo scrivere una nuova classe e registrarla nel
 * SelettoreAutenticatore.
 */
interface Autenticatore
{
    public function autentica(string $username, string $password): RisultatoAutenticazione;
}
