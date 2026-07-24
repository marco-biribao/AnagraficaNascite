<?php

namespace Database\Seeders;

use App\Models\Dichiarante;
use Illuminate\Database\Seeder;

class DichiaranteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dichiaranti = [
            ['codice' => 'PADRE', 'descrizione' => 'Padre', 'ordine' => 1],
            ['codice' => 'MADRE', 'descrizione' => 'Madre', 'ordine' => 2],
            ['codice' => 'ALTRO', 'descrizione' => 'Altro', 'ordine' => 3],
        ];

        foreach ($dichiaranti as $dichiarante) {
            Dichiarante::updateOrCreate(['codice' => $dichiarante['codice']], $dichiarante);
        }
    }
}
