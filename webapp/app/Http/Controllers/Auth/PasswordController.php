<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        abort_unless(auth()->user()->auth_source === 'local', 403, 'La password di questa utenza è gestita da Active Directory.');

        return view('auth.password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password' => $request->validated('password_nuova')]);

        return redirect()->route('password.edit')
            ->with('successo', 'Password aggiornata correttamente.');
    }
}
