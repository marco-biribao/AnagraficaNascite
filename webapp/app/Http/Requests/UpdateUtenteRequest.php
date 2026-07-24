<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUtenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestire-sistema');
    }

    public function rules(): array
    {
        return [
            'is_active' => ['sometimes', 'boolean'],
            'ruoli' => ['array'],
            'ruoli.*' => ['integer', 'exists:roles,id'],
        ];
    }
}
