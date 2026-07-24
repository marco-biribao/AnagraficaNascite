<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Il cambio password ha senso solo per le utenze locali: la password
     * di un'utenza sincronizzata da Active Directory si cambia in AD, non
     * qui.
     */
    public function authorize(): bool
    {
        return $this->user()->auth_source === 'local';
    }

    public function rules(): array
    {
        return [
            'password_attuale' => ['required', 'current_password'],
            'password_nuova' => ['required', 'confirmed', Password::min(10)->mixedCase()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'password_attuale.current_password' => 'non è corretta.',
        ];
    }
}
