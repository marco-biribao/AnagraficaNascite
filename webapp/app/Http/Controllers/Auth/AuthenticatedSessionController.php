<?php

namespace App\Http\Controllers\Auth;

use App\Auth\SelettoreAutenticatore;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, SelettoreAutenticatore $selettore): RedirectResponse
    {
        $credenziali = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $risultato = $selettore->perUsername($credenziali['username'])
            ->autentica($credenziali['username'], $credenziali['password']);

        if (! $risultato->riuscita) {
            throw ValidationException::withMessages([
                'username' => $risultato->motivo === 'disabilitato'
                    ? 'Utenza disabilitata. Contatta un amministratore.'
                    : 'Credenziali non valide.',
            ]);
        }

        Auth::login($risultato->utente, $request->boolean('ricordami'));
        $request->session()->regenerate();

        $risultato->utente->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('dichiarazioni.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
