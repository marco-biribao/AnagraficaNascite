<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * L'identificativo di login e' "username" (piu' il GUID per gli utenti
     * AD), non l'email: l'email e' solo un dato descrittivo sincronizzato
     * da Active Directory. Vincolarla come unica non serve a nulla per
     * l'autenticazione e crea collisioni fragili (es. l'account locale di
     * emergenza e un utente AD reale con la stessa email finiscono per
     * confliggere in fase di provisioning automatico).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
};
