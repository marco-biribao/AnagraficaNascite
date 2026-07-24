<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUtenteRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\ProtezioneAutoBlocco;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class UtenteController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:gestire-sistema'),
        ];
    }

    public function index(): View
    {
        $utenti = User::with('roles')->orderBy('name')->get();

        return view('utenti.index', compact('utenti'));
    }

    public function edit(User $utente): View
    {
        return view('utenti.edit', [
            'utente' => $utente,
            'ruoli' => Role::orderBy('nome')->get(),
        ]);
    }

    public function update(UpdateUtenteRequest $request, User $utente, ProtezioneAutoBlocco $protezione): RedirectResponse
    {
        $ruoliSelezionati = $request->validated('ruoli', []);
        $restaAttivo = $request->boolean('is_active');

        $protezione->verificaModificaUtente($request->user(), $utente, $restaAttivo, $ruoliSelezionati);

        $utente->update(['is_active' => $restaAttivo]);
        $utente->roles()->sync($ruoliSelezionati);

        return redirect()->route('utenti.index')
            ->with('successo', "Utente {$utente->name} aggiornato correttamente.");
    }
}
