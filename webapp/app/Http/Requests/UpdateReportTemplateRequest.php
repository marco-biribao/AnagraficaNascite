<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestire-sistema');
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'contenuto' => ['required', 'string'],
            'attivo' => ['sometimes', 'boolean'],
        ];
    }
}
