@extends('layouts.app')

@section('titolo', 'Template dei report')

@section('contenuto')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Template dei report</h1>
    <p class="text-sm text-slate-500 mb-6">
        Qui puoi modificare il testo e l'impaginazione dei moduli stampabili, ad esempio quando cambia la normativa.
        Non serve alcuna competenza tecnica: ogni modifica salva una nuova versione e puoi tornare indietro in qualsiasi momento.
    </p>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-2">Template</th>
                    <th class="px-4 py-2">Modello collegato</th>
                    <th class="px-4 py-2">Versione</th>
                    <th class="px-4 py-2">Stato</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($templates as $template)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $template->nome }}</td>
                        <td class="px-4 py-2">{{ $template->modello->codice }}</td>
                        <td class="px-4 py-2">v{{ $template->versione }}</td>
                        <td class="px-4 py-2">
                            @if ($template->attivo)
                                <span class="text-green-700">Attivo</span>
                            @else
                                <span class="text-slate-400">Disattivato</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('report-templates.edit', $template) }}" class="text-slate-600 hover:underline">Modifica</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
