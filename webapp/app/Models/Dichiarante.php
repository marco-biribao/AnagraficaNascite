<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['codice', 'descrizione', 'ordine', 'attivo'])]
class Dichiarante extends Model
{
    protected $table = 'dichiaranti';

    protected function casts(): array
    {
        return [
            'attivo' => 'boolean',
        ];
    }

    public function dichiarazioni(): HasMany
    {
        return $this->hasMany(DichiarazioneNascita::class, 'dichiarante_id');
    }
}
