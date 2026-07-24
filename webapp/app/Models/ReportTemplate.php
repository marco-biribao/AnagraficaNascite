<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['modello_dichiarazione_id', 'nome', 'slug', 'contenuto', 'versione', 'attivo', 'updated_by'])]
class ReportTemplate extends Model
{
    protected function casts(): array
    {
        return [
            'attivo' => 'boolean',
        ];
    }

    public function modello(): BelongsTo
    {
        return $this->belongsTo(ModelloDichiarazione::class, 'modello_dichiarazione_id');
    }

    public function revisioni(): HasMany
    {
        return $this->hasMany(ReportTemplateRevision::class)->latest('versione');
    }

    public function salvaNuovaVersione(string $contenuto, ?int $userId): void
    {
        $this->revisioni()->create([
            'contenuto' => $this->contenuto,
            'versione' => $this->versione,
            'created_by' => $this->updated_by,
        ]);

        $this->update([
            'contenuto' => $contenuto,
            'versione' => $this->versione + 1,
            'updated_by' => $userId,
        ]);
    }
}
