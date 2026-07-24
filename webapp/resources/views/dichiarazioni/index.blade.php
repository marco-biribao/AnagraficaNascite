@extends('layouts.app')

@section('titolo', 'Dichiarazioni di nascita')

@section('contenuto')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Dichiarazioni di nascita</h1>
        @can('gestire-dichiarazioni')
        <a href="{{ route('dichiarazioni.create') }}" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium">
            + Nuova dichiarazione
        </a>
        @endcan
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-600">Nome, cognome o numero atto</label>
            <input type="text" name="ricerca" value="{{ request('ricerca') }}" class="mt-1 block w-full rounded border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-600">Modello</label>
            <select name="modello_dichiarazione_id" class="mt-1 block w-full rounded border-slate-300 text-sm">
                <option value="">Tutti</option>
                @foreach ($modelli as $modello)
                    <option value="{{ $modello->id }}" @selected(request('modello_dichiarazione_id') == $modello->id)>{{ $modello->codice }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-600">Anno atto</label>
            <input type="number" name="anno_atto" value="{{ request('anno_atto') }}" class="mt-1 block w-full rounded border-slate-300 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="mostra_esclusi" value="1" @checked(request('mostra_esclusi')) class="rounded border-slate-300">
                Mostra esclusi
            </label>
        </div>
        <div class="md:col-span-5">
            <button type="submit" class="px-4 py-2 rounded border border-slate-300 text-slate-700 text-sm">Cerca</button>
            <a href="{{ route('dichiarazioni.index') }}" class="px-4 py-2 rounded text-slate-500 text-sm">Reimposta</a>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-2">Atto</th>
                    <th class="px-4 py-2">Modello</th>
                    <th class="px-4 py-2">Nascituro</th>
                    <th class="px-4 py-2">Data nascita</th>
                    <th class="px-4 py-2">Operatore</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($dichiarazioni as $dichiarazione)
                    <tr class="{{ $dichiarazione->trashed() ? 'bg-red-50 text-slate-400' : '' }}">
                        <td class="px-4 py-2 font-medium">{{ $dichiarazione->codice_atto }}</td>
                        <td class="px-4 py-2">{{ $dichiarazione->modello->codice }}</td>
                        <td class="px-4 py-2">{{ $dichiarazione->cognome_nascituro }} {{ $dichiarazione->nome_nascituro }}</td>
                        <td class="px-4 py-2">{{ $dichiarazione->data_nascita->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $dichiarazione->operatore->name }}</td>
                        <td class="px-4 py-2 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('dichiarazioni.show', $dichiarazione) }}" class="text-slate-600 hover:underline">Apri</a>
                            @can('gestire-dichiarazioni')
                                @if ($dichiarazione->trashed())
                                    <form method="POST" action="{{ route('dichiarazioni.restore', $dichiarazione->id) }}" class="inline">
                                        @csrf
                                        <button class="text-green-700 hover:underline">Ripristina</button>
                                    </form>
                                @else
                                    <a href="{{ route('dichiarazioni.edit', $dichiarazione) }}" class="text-slate-600 hover:underline">Modifica</a>
                                    <form method="POST" action="{{ route('dichiarazioni.destroy', $dichiarazione) }}" class="inline" onsubmit="return confirm('Escludere questa dichiarazione?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-700 hover:underline">Escludi</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">Nessuna dichiarazione trovata.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $dichiarazioni->links() }}
    </div>
@endsection
