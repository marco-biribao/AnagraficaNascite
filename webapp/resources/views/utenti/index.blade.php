@extends('layouts.app')

@section('titolo', 'Utenti')

@section('contenuto')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Utenti</h1>
    <p class="text-sm text-slate-500 mb-6">
        Gli utenti Active Directory vengono creati automaticamente al primo accesso: qui assegni i ruoli
        e puoi disattivare un'utenza. I ruoli non derivano dai gruppi di AD, sono gestiti solo qui.
    </p>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-2">Nome</th>
                    <th class="px-4 py-2">Utenza</th>
                    <th class="px-4 py-2">Provenienza</th>
                    <th class="px-4 py-2">Ruoli</th>
                    <th class="px-4 py-2">Stato</th>
                    <th class="px-4 py-2">Ultimo accesso</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($utenti as $utente)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $utente->name }}</td>
                        <td class="px-4 py-2">{{ $utente->username }}</td>
                        <td class="px-4 py-2">{{ $utente->auth_source === 'ldap' ? 'Active Directory' : 'Locale' }}</td>
                        <td class="px-4 py-2">
                            @forelse ($utente->roles as $ruolo)
                                <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-xs">{{ $ruolo->nome }}</span>
                            @empty
                                <span class="text-slate-400 text-xs">Nessuno</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-2">
                            @if ($utente->is_active)
                                <span class="text-green-700">Attivo</span>
                            @else
                                <span class="text-red-600">Disattivato</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ optional($utente->last_login_at)->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('utenti.edit', $utente) }}" class="text-slate-600 hover:underline">Modifica</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
