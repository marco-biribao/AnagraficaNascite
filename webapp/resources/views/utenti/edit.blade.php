@extends('layouts.app')

@section('titolo', 'Modifica utente')

@section('contenuto')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Utente: {{ $utente->name }}</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-xl">
        <dl class="text-sm space-y-1 mb-6">
            <div class="flex justify-between"><dt class="text-slate-500">Utenza</dt><dd>{{ $utente->username }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Email</dt><dd>{{ $utente->email ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Provenienza</dt><dd>{{ $utente->auth_source === 'ldap' ? 'Active Directory' : 'Locale' }}</dd></div>
        </dl>

        <form method="POST" action="{{ route('utenti.update', $utente) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $utente->is_active)) class="rounded border-slate-300">
                    Utenza attiva
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Ruoli</label>
                <div class="space-y-1">
                    @foreach ($ruoli as $ruolo)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="ruoli[]" value="{{ $ruolo->id }}"
                                @checked(collect(old('ruoli', $utente->roles->pluck('id')->all()))->contains($ruolo->id))
                                class="rounded border-slate-300">
                            {{ $ruolo->nome }}
                            <span class="text-slate-400">- {{ $ruolo->descrizione }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('utenti.index') }}" class="px-4 py-2 rounded border border-slate-300 text-slate-700">Annulla</a>
                <button type="submit" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-900 text-white font-medium">Salva</button>
            </div>
        </form>
    </div>
@endsection
