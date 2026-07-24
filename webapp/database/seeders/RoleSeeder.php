<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruoli = [
            [
                'slug' => 'amministratore',
                'nome' => 'Amministratore',
                'descrizione' => 'Gestione utenti, ruoli, modelli di dichiarazione e template dei report.',
            ],
            [
                'slug' => 'operatore',
                'nome' => 'Operatore',
                'descrizione' => 'Inserimento e stampa delle dichiarazioni di nascita.',
            ],
            [
                'slug' => 'supervisore',
                'nome' => 'Supervisore',
                'descrizione' => 'Sola consultazione e ricerca delle dichiarazioni registrate.',
            ],
        ];

        foreach ($ruoli as $ruolo) {
            Role::updateOrCreate(['slug' => $ruolo['slug']], $ruolo);
        }
    }
}
