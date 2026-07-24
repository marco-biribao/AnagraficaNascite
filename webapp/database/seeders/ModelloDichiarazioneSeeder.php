<?php

namespace Database\Seeders;

use App\Models\ModelloDichiarazione;
use Illuminate\Database\Seeder;

class ModelloDichiarazioneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Dati derivati dai modelli dell'archivio EpiInfo storico (codeMODELLODICHIARAZIONE),
     * coerenti con il DPR 396/2000.
     */
    public function run(): void
    {
        $modelli = [
            [
                'codice' => 'A',
                'descrizione' => 'Nascite dei figli nati in costanza di matrimonio',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => true,
                'parto_plurimo' => false,
                'dichiarante_predefinito' => 'PADRE',
                'ordine' => 1,
            ],
            [
                'codice' => 'A1',
                'descrizione' => 'Nascite plurime dei figli nati in costanza di matrimonio',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => true,
                'parto_plurimo' => true,
                'dichiarante_predefinito' => 'PADRE',
                'ordine' => 2,
            ],
            [
                'codice' => 'A_madre',
                'descrizione' => 'Nascite dei figli nati in costanza di matrimonio (dichiarante madre)',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => true,
                'parto_plurimo' => false,
                'dichiarante_predefinito' => 'MADRE',
                'ordine' => 3,
            ],
            [
                'codice' => 'A1_madre',
                'descrizione' => 'Nascite plurime dei figli nati in costanza di matrimonio (dichiarante madre)',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => true,
                'parto_plurimo' => true,
                'dichiarante_predefinito' => 'MADRE',
                'ordine' => 4,
            ],
            [
                'codice' => 'B',
                'descrizione' => 'Nascite di figli naturali riconosciuti solo dalla madre',
                'richiede_dati_padre' => false,
                'richiede_dati_madre' => true,
                'parto_plurimo' => false,
                'dichiarante_predefinito' => 'MADRE',
                'ordine' => 5,
            ],
            [
                'codice' => 'B1',
                'descrizione' => 'Nascite plurime di figli naturali riconosciuti solo dalla madre',
                'richiede_dati_padre' => false,
                'richiede_dati_madre' => true,
                'parto_plurimo' => true,
                'dichiarante_predefinito' => 'MADRE',
                'ordine' => 6,
            ],
            [
                'codice' => 'C',
                'descrizione' => 'Nascite di figli naturali riconosciuti solo dal padre',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => false,
                'parto_plurimo' => false,
                'dichiarante_predefinito' => 'PADRE',
                'ordine' => 7,
            ],
            [
                'codice' => 'C1',
                'descrizione' => 'Nascite plurime di figli naturali riconosciuti solo dal padre',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => false,
                'parto_plurimo' => true,
                'dichiarante_predefinito' => 'PADRE',
                'ordine' => 8,
            ],
            [
                'codice' => 'D',
                'descrizione' => 'Nascite di figli nati fuori dal matrimonio riconosciuti contemporaneamente dal padre e dalla madre',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => true,
                'parto_plurimo' => false,
                'dichiarante_predefinito' => 'PADRE',
                'ordine' => 9,
            ],
            [
                'codice' => 'D1',
                'descrizione' => 'Nascite plurime di figli nati fuori dal matrimonio riconosciuti contemporaneamente dal padre e dalla madre',
                'richiede_dati_padre' => true,
                'richiede_dati_madre' => true,
                'parto_plurimo' => true,
                'dichiarante_predefinito' => 'PADRE',
                'ordine' => 10,
            ],
        ];

        foreach ($modelli as $modello) {
            ModelloDichiarazione::updateOrCreate(['codice' => $modello['codice']], $modello);
        }
    }
}
