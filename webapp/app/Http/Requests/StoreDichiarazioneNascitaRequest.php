<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidaCampiDichiarazione;
use App\Models\ModelloDichiarazione;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreDichiarazioneNascitaRequest extends FormRequest
{
    use ValidaCampiDichiarazione;

    public function authorize(): bool
    {
        return $this->user()->can('gestire-dichiarazioni');
    }

    public function rules(): array
    {
        $modello = ModelloDichiarazione::find($this->input('modello_dichiarazione_id'));

        $regole = $this->regoleCampiDichiarazione($modello);

        $anno = $this->filled('data_atto') ? Carbon::parse($this->input('data_atto'))->year : null;

        $regole['numero_atto'][] = Rule::unique('dichiarazioni_nascita')
            ->where(fn ($query) => $query->whereRaw('YEAR(data_atto) = ?', [$anno]));

        return $regole;
    }
}
