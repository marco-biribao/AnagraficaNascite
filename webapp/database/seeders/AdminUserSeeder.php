<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea un account amministratore locale (auth_source=local), utilizzabile
     * come accesso di emergenza quando Active Directory non e' raggiungibile.
     * Username e password si impostano tramite le variabili d'ambiente
     * ADMIN_USERNAME e ADMIN_PASSWORD (vedi .env), per non avere credenziali
     * in chiaro nel codice sorgente.
     */
    public function run(): void
    {
        $username = env('ADMIN_USERNAME', 'admin.locale');
        $password = env('ADMIN_PASSWORD');

        if (! $password) {
            $this->command?->warn(
                'ADMIN_PASSWORD non impostata in .env: uso una password provvisoria, cambiala subito dopo il primo accesso.'
            );
            $password = 'CambiaMi!'.random_int(1000, 9999);
            $this->command?->warn("Password provvisoria per {$username}: {$password}");
        }

        $admin = User::updateOrCreate(
            ['username' => $username],
            [
                'name' => 'Amministratore locale',
                'email' => env('ADMIN_EMAIL'),
                'password' => $password,
                'auth_source' => 'local',
                'is_active' => true,
            ]
        );

        $ruoloAmministratore = Role::where('slug', 'amministratore')->first();

        if ($ruoloAmministratore) {
            $admin->roles()->syncWithoutDetaching([$ruoloAmministratore->id]);
        }
    }
}
