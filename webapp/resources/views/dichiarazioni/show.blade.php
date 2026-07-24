@extends('layouts.app')

@section('titolo', 'Dichiarazione ' . $dichiarazione->codice_atto)

@section('contenuto')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-slate-800">
            Dichiarazione {{ $dichiarazione->codice_atto }}
            <span class="text-sm font-normal text-slate-500">({{ $dichiarazione->modello->codice }})</span>
        </h1>
        <div class="space-x-2">
            <a href="{{ route('dichiarazioni.index') }}" class="px-3 py-2 rounded border border-slate-300 text-slate-700 text-sm">Torna all'elenco</a>
            @can('gestire-dichiarazioni')
                <a href="{{ route('dichiarazioni.edit', $dichiarazione) }}" class="px-3 py-2 rounded bg-slate-800 hover:bg-slate-900 text-white text-sm">Modifica</a>
            @endcan
        </div>
    </div>

    @can('gestire-dichiarazioni')
    @if ($templateStampabili->isNotEmpty())
        <div class="bg-white rounded-lg shadow p-4 mb-6 flex items-center gap-3 flex-wrap">
            <span class="text-sm text-slate-500">Stampa:</span>
            @foreach ($templateStampabili as $template)
                <a href="{{ route('dichiarazioni.stampa', [$dichiarazione, $template->slug]) }}" target="_blank"
                    class="px-3 py-1.5 rounded border border-slate-300 text-sm text-slate-700 hover:bg-slate-50">
                    {{ $template->nome }}
                </a>
            @endforeach
        </div>
    @endif
    @endcan

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-slate-800 mb-3">Atto e nascituro</h2>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Modello</dt><dd>{{ $dichiarazione->modello->descrizione }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Dichiarante</dt><dd>{{ $dichiarazione->dichiarante->descrizione }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Data/ora atto</dt><dd>{{ $dichiarazione->data_atto->format('d/m/Y') }} {{ $dichiarazione->ora_atto->format('H:i') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Nascituro</dt><dd>{{ $dichiarazione->cognome_nascituro }} {{ $dichiarazione->nome_nascituro }} ({{ ucfirst(strtolower($dichiarazione->sesso_nascituro)) }})</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Data/ora nascita</dt><dd>{{ $dichiarazione->data_nascita->format('d/m/Y') }} {{ $dichiarazione->ora_nascita->format('H:i') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Comune trascrizione</dt><dd>{{ $dichiarazione->comune_trascrizione_nascita }}</dd></div>
            </dl>
        </section>

        @if ($dichiarazione->modello->richiede_dati_padre)
        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-slate-800 mb-3">Padre</h2>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Nome</dt><dd>{{ $dichiarazione->cognome_padre }} {{ $dichiarazione->nome_padre }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Nascita</dt><dd>{{ optional($dichiarazione->data_nascita_padre)->format('d/m/Y') }} - {{ $dichiarazione->citta_nascita_padre }} ({{ $dichiarazione->provincia_nascita_padre }})</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Residenza</dt><dd>{{ $dichiarazione->comune_residenza_padre }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Cittadinanza</dt><dd>{{ $dichiarazione->cittadinanza_padre }}</dd></div>
            </dl>
        </section>
        @endif

        @if ($dichiarazione->modello->richiede_dati_madre)
        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-slate-800 mb-3">Madre</h2>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Nome</dt><dd>{{ $dichiarazione->cognome_madre }} {{ $dichiarazione->nome_madre }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Nascita</dt><dd>{{ optional($dichiarazione->data_nascita_madre)->format('d/m/Y') }} - {{ $dichiarazione->citta_nascita_madre }} ({{ $dichiarazione->provincia_nascita_madre }})</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Residenza</dt><dd>{{ $dichiarazione->comune_residenza_madre }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Cittadinanza</dt><dd>{{ $dichiarazione->cittadinanza_madre }}</dd></div>
            </dl>
        </section>
        @endif

        @if ($dichiarazione->modello->parto_plurimo)
        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-slate-800 mb-3">Parto plurimo</h2>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Atto gemello</dt><dd>{{ $dichiarazione->codice_atto_gemello }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Ordine di nascita</dt><dd>{{ $dichiarazione->ordine_nascita_gemello }}</dd></div>
            </dl>
        </section>
        @endif

        <section class="bg-white rounded-lg shadow p-6 md:col-span-2">
            <h2 class="font-semibold text-slate-800 mb-3">Tracciabilità</h2>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Operatore</dt><dd>{{ $dichiarazione->operatore->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Registrata il</dt><dd>{{ $dichiarazione->created_at->format('d/m/Y H:i') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Ultima modifica</dt><dd>{{ $dichiarazione->updated_at->format('d/m/Y H:i') }}</dd></div>
                @if ($dichiarazione->note)
                <div class="flex justify-between"><dt class="text-slate-500">Note</dt><dd>{{ $dichiarazione->note }}</dd></div>
                @endif
            </dl>
        </section>
    </div>
@endsection
