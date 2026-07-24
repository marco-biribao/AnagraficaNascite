<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'codice', 'descrizione', 'richiede_dati_padre', 'richiede_dati_madre',
    'parto_plurimo', 'dichiarante_predefinito', 'ordine', 'attivo',
])]
class ModelloDichiarazione extends Model
{
    protected $table = 'modelli_dichiarazione';

    protected function casts(): array
    {
        return [
            'richiede_dati_padre' => 'boolean',
            'richiede_dati_madre' => 'boolean',
            'parto_plurimo' => 'boolean',
            'attivo' => 'boolean',
        ];
    }

    public function dichiarazioni(): HasMany
    {
        return $this->hasMany(DichiarazioneNascita::class, 'modello_dichiarazione_id');
    }

    public function reportTemplates(): HasMany
    {
        return $this->hasMany(ReportTemplate::class, 'modello_dichiarazione_id');
    }
}
