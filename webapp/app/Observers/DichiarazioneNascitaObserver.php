<?php

namespace App\Observers;

use App\Models\DichiarazioneNascita;
use Illuminate\Support\Facades\Auth;

/**
 * Osserva il ciclo di vita di una dichiarazione per tracciare chi l'ha
 * creata, modificata, esclusa o ripristinata. Prima questa responsabilita'
 * era sparsa nei singoli metodi del controller (store/update/destroy),
 * col rischio di dimenticarla in un punto (come infatti succedeva per il
 * ripristino): qui e' garantita in un unico posto per ogni operazione.
 */
class DichiarazioneNascitaObserver
{
    public function creating(DichiarazioneNascita $dichiarazione): void
    {
        $utenteId = Auth::id();

        $dichiarazione->operatore_id ??= $utenteId;
        $dichiarazione->created_by ??= $utenteId;
        $dichiarazione->updated_by ??= $utenteId;
    }

    public function updating(DichiarazioneNascita $dichiarazione): void
    {
        $dichiarazione->updated_by = Auth::id();
    }

    public function deleting(DichiarazioneNascita $dichiarazione): void
    {
        $dichiarazione->updated_by = Auth::id();
        $dichiarazione->saveQuietly();
    }

    public function restoring(DichiarazioneNascita $dichiarazione): void
    {
        $dichiarazione->updated_by = Auth::id();
    }
}
