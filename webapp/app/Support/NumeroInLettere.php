<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Conversione di numeri e date in lettere (italiano), usata per riprodurre
 * la dicitura degli atti di stato civile (es. "il giorno quattordici del
 * mese di febbraio dell'anno duemilaquattordici alle ore nove e minuti
 * cinquantatre'"). Nel vecchio archivio EpiInfo questi valori erano
 * digitati/registrati a mano in colonne separate; qui si calcolano al volo
 * dalla data/ora reale, cosi' restano sempre corretti.
 */
class NumeroInLettere
{
    private const UNITA = [
        'zero', 'uno', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove',
        'dieci', 'undici', 'dodici', 'tredici', 'quattordici', 'quindici', 'sedici',
        'diciassette', 'diciotto', 'diciannove',
    ];

    private const DECINE = [
        2 => 'venti', 3 => 'trenta', 4 => 'quaranta', 5 => 'cinquanta',
        6 => 'sessanta', 7 => 'settanta', 8 => 'ottanta', 9 => 'novanta',
    ];

    private const MESI = [
        1 => 'gennaio', 2 => 'febbraio', 3 => 'marzo', 4 => 'aprile',
        5 => 'maggio', 6 => 'giugno', 7 => 'luglio', 8 => 'agosto',
        9 => 'settembre', 10 => 'ottobre', 11 => 'novembre', 12 => 'dicembre',
    ];

    public static function cardinale(int $numero): string
    {
        if ($numero < 0) {
            return 'meno '.self::cardinale(-$numero);
        }

        if ($numero < 20) {
            return self::UNITA[$numero];
        }

        if ($numero < 100) {
            $decina = intdiv($numero, 10);
            $unita = $numero % 10;
            $prefisso = self::DECINE[$decina];

            if ($unita === 0) {
                return $prefisso;
            }

            // elisione della vocale finale davanti a "uno" e "otto"
            if (in_array($unita, [1, 8], true)) {
                $prefisso = mb_substr($prefisso, 0, -1);
            }

            return $prefisso.self::UNITA[$unita];
        }

        if ($numero < 1000) {
            $centinaia = intdiv($numero, 100);
            $resto = $numero % 100;
            $prefisso = $centinaia === 1 ? 'cento' : self::UNITA[$centinaia].'cento';

            return $resto === 0 ? $prefisso : $prefisso.self::cardinale($resto);
        }

        if ($numero < 1_000_000) {
            $migliaia = intdiv($numero, 1000);
            $resto = $numero % 1000;
            $prefisso = $migliaia === 1 ? 'mille' : self::cardinale($migliaia).'mila';

            return $resto === 0 ? $prefisso : $prefisso.self::cardinale($resto);
        }

        // oltre il milione non serve per gli atti di nascita: fallback numerico
        return (string) $numero;
    }

    public static function nomeMese(int $mese): string
    {
        return self::MESI[$mese] ?? '';
    }

    /**
     * Es: "il giorno quattordici del mese di febbraio dell'anno duemilaquattordici"
     */
    public static function dataInLettere(?Carbon $data): string
    {
        if (! $data) {
            return '';
        }

        return sprintf(
            "il giorno %s del mese di %s dell'anno %s",
            self::cardinale($data->day),
            self::nomeMese($data->month),
            self::cardinale($data->year)
        );
    }

    /**
     * Es: "alle ore nove e minuti cinquantatre"
     */
    public static function oraInLettere(?string $orario): string
    {
        if (! $orario) {
            return '';
        }

        [$ore, $minuti] = array_pad(explode(':', $orario), 2, '0');

        return sprintf(
            'alle ore %s e minuti %s',
            self::cardinale((int) $ore),
            self::cardinale((int) $minuti)
        );
    }
}
