<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['report_template_id', 'contenuto', 'versione', 'created_by'])]
class ReportTemplateRevision extends Model
{
    const UPDATED_AT = null;

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function autore(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
