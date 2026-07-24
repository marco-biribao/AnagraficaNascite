<?php

namespace App\Models;

use App\Observers\DichiarazioneNascitaObserver;
use App\Support\FiltriDichiarazione;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable([
    'modello_dichiarazione_id', 'dichiarante_id',
    'data_atto', 'ora_atto', 'numero_atto',
    'nome_nascituro', 'cognome_nascituro', 'sesso_nascituro',
    'data_nascita', 'ora_nascita', 'comune_trascrizione_nascita',
    'nome_nascituro_straniero_art24', 'cognome_nascituro_straniero_art24', 'cognome_concordato',
    'cognome_padre', 'nome_padre', 'citta_nascita_padre', 'provincia_nascita_padre',
    'data_nascita_padre', 'comune_residenza_padre', 'cittadinanza_padre',
    'cognome_madre', 'nome_madre', 'citta_nascita_madre', 'provincia_nascita_madre',
    'data_nascita_madre', 'comune_residenza_madre', 'cittadinanza_madre',
    'numero_atto_gemello', 'codice_atto_gemello', 'ordine_nascita_gemello',
    'data_spedizione_raccomandata', 'data_invio_comunicazione_telematica', 'numero_protocollo',
    'comune_destinatario', 'comune_di_trascrizione', 'conferma_avvenuta_trascrizione',
    'numero_atto_comune', 'anno_trascrizione', 'note',
    'operatore_id', 'created_by', 'updated_by',
])]
#[ObservedBy(DichiarazioneNascitaObserver::class)]
class DichiarazioneNascita extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'dichiarazioni_nascita';

    protected function casts(): array
    {
        return [
            'data_atto' => 'date',
            'ora_atto' => 'datetime:H:i',
            'data_nascita' => 'date',
            'ora_nascita' => 'datetime:H:i',
            'data_nascita_padre' => 'date',
            'data_nascita_madre' => 'date',
            'data_spedizione_raccomandata' => 'date',
            'data_invio_comunicazione_telematica' => 'date',
        ];
    }

    protected function codiceAtto(): Attribute
    {
        return Attribute::get(fn () => "{$this->numero_atto}/{$this->anno_atto}");
    }

    public function modello(): BelongsTo
    {
        return $this->belongsTo(ModelloDichiarazione::class, 'modello_dichiarazione_id');
    }

    public function dichiarante(): BelongsTo
    {
        return $this->belongsTo(Dichiarante::class, 'dichiarante_id');
    }

    public function operatore(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operatore_id');
    }

    public function scopeFiltra(Builder $query, FiltriDichiarazione $filtri): Builder
    {
        if ($filtri->mostraEsclusi) {
            $query->withTrashed();
        }

        if ($filtri->ricerca) {
            $query->where(function (Builder $q) use ($filtri) {
                $q->where('nome_nascituro', 'like', "%{$filtri->ricerca}%")
                    ->orWhere('cognome_nascituro', 'like', "%{$filtri->ricerca}%")
                    ->orWhere('numero_atto', 'like', "%{$filtri->ricerca}%");
            });
        }

        if ($filtri->modelloId) {
            $query->where('modello_dichiarazione_id', $filtri->modelloId);
        }

        if ($filtri->annoAtto) {
            $query->where('anno_atto', $filtri->annoAtto);
        }

        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
