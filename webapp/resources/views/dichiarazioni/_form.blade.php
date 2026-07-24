@php
    $datiModelli = $modelli->mapWithKeys(fn ($m) => [$m->id => [
        'padre' => (bool) $m->richiede_dati_padre,
        'madre' => (bool) $m->richiede_dati_madre,
        'gemello' => (bool) $m->parto_plurimo,
    ]]);

    $valore = fn (string $campo, $formato = null) => old(
        $campo,
        $formato ? optional($dichiarazione->$campo)->format($formato) : $dichiarazione->$campo
    );
@endphp

<div
    x-data="{
        modelli: {{ $datiModelli->toJson() }},
        modelloId: '{{ old('modello_dichiarazione_id', $dichiarazione->modello_dichiarazione_id) }}',
        get info() { return this.modelli[this.modelloId] ?? { padre: true, madre: true, gemello: false }; },
    }"
    class="space-y-8"
>
    <section class="bg-white rounded-lg shadow p-6">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Atto e nascituro</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Modello dichiarazione</label>
                <select name="modello_dichiarazione_id" x-model="modelloId"
                    class="mt-1 block w-full rounded @error('modello_dichiarazione_id') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
                    <option value="">-- seleziona --</option>
                    @foreach ($modelli as $modello)
                        <option value="{{ $modello->id }}">{{ $modello->codice }} - {{ $modello->descrizione }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Dichiarante</label>
                <select name="dichiarante_id" class="mt-1 block w-full rounded @error('dichiarante_id') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
                    <option value="">-- seleziona --</option>
                    @foreach ($dichiaranti as $dichiarante)
                        <option value="{{ $dichiarante->id }}" @selected(old('dichiarante_id', $dichiarazione->dichiarante_id) == $dichiarante->id)>
                            {{ $dichiarante->descrizione }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Data atto</label>
                <input type="date" name="data_atto" value="{{ $valore('data_atto', 'Y-m-d') }}" class="mt-1 block w-full rounded @error('data_atto') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Ora atto</label>
                <input type="time" name="ora_atto" value="{{ $valore('ora_atto', 'H:i') }}" class="mt-1 block w-full rounded @error('ora_atto') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Numero atto</label>
                <input type="number" name="numero_atto" value="{{ old('numero_atto', $dichiarazione->numero_atto) }}" class="mt-1 block w-full rounded @error('numero_atto') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Nome nascituro</label>
                <input type="text" name="nome_nascituro" value="{{ old('nome_nascituro', $dichiarazione->nome_nascituro) }}" class="mt-1 block w-full rounded @error('nome_nascituro') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Cognome nascituro</label>
                <input type="text" name="cognome_nascituro" value="{{ old('cognome_nascituro', $dichiarazione->cognome_nascituro) }}" class="mt-1 block w-full rounded @error('cognome_nascituro') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Sesso</label>
                <select name="sesso_nascituro" class="mt-1 block w-full rounded @error('sesso_nascituro') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
                    <option value="">-- seleziona --</option>
                    <option value="MASCHILE" @selected(old('sesso_nascituro', $dichiarazione->sesso_nascituro) == 'MASCHILE')>Maschile</option>
                    <option value="FEMMINILE" @selected(old('sesso_nascituro', $dichiarazione->sesso_nascituro) == 'FEMMINILE')>Femminile</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Data nascita</label>
                <input type="date" name="data_nascita" value="{{ $valore('data_nascita', 'Y-m-d') }}" class="mt-1 block w-full rounded @error('data_nascita') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Ora nascita</label>
                <input type="time" name="ora_nascita" value="{{ $valore('ora_nascita', 'H:i') }}" class="mt-1 block w-full rounded @error('ora_nascita') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Comune trascrizione nascita</label>
                <input type="text" name="comune_trascrizione_nascita" value="{{ old('comune_trascrizione_nascita', $dichiarazione->comune_trascrizione_nascita) }}" class="mt-1 block w-full rounded @error('comune_trascrizione_nascita') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Nome nascituro straniero (art. 24)</label>
                <input type="text" name="nome_nascituro_straniero_art24" value="{{ old('nome_nascituro_straniero_art24', $dichiarazione->nome_nascituro_straniero_art24) }}" class="mt-1 block w-full rounded @error('nome_nascituro_straniero_art24') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Cognome nascituro straniero (art. 24)</label>
                <input type="text" name="cognome_nascituro_straniero_art24" value="{{ old('cognome_nascituro_straniero_art24', $dichiarazione->cognome_nascituro_straniero_art24) }}" class="mt-1 block w-full rounded @error('cognome_nascituro_straniero_art24') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Cognome concordato</label>
                <input type="text" name="cognome_concordato" value="{{ old('cognome_concordato', $dichiarazione->cognome_concordato) }}" class="mt-1 block w-full rounded @error('cognome_concordato') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
        </div>
    </section>

    <section class="bg-white rounded-lg shadow p-6" x-show="info.padre" x-cloak>
        <h2 class="text-base font-semibold text-slate-800 mb-4">Dati del padre</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Cognome</label>
                <input type="text" name="cognome_padre" value="{{ old('cognome_padre', $dichiarazione->cognome_padre) }}" class="mt-1 block w-full rounded @error('cognome_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Nome</label>
                <input type="text" name="nome_padre" value="{{ old('nome_padre', $dichiarazione->nome_padre) }}" class="mt-1 block w-full rounded @error('nome_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Cittadinanza</label>
                <input type="text" name="cittadinanza_padre" value="{{ old('cittadinanza_padre', $dichiarazione->cittadinanza_padre ?? 'ITALIANA') }}" class="mt-1 block w-full rounded @error('cittadinanza_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Città di nascita</label>
                <input type="text" name="citta_nascita_padre" value="{{ old('citta_nascita_padre', $dichiarazione->citta_nascita_padre) }}" class="mt-1 block w-full rounded @error('citta_nascita_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Provincia di nascita</label>
                <input type="text" name="provincia_nascita_padre" value="{{ old('provincia_nascita_padre', $dichiarazione->provincia_nascita_padre) }}" class="mt-1 block w-full rounded @error('provincia_nascita_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Data di nascita</label>
                <input type="date" name="data_nascita_padre" value="{{ $valore('data_nascita_padre', 'Y-m-d') }}" class="mt-1 block w-full rounded @error('data_nascita_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-700">Comune di residenza</label>
                <input type="text" name="comune_residenza_padre" value="{{ old('comune_residenza_padre', $dichiarazione->comune_residenza_padre) }}" class="mt-1 block w-full rounded @error('comune_residenza_padre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
        </div>
    </section>

    <section class="bg-white rounded-lg shadow p-6" x-show="info.madre" x-cloak>
        <h2 class="text-base font-semibold text-slate-800 mb-4">Dati della madre</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Cognome</label>
                <input type="text" name="cognome_madre" value="{{ old('cognome_madre', $dichiarazione->cognome_madre) }}" class="mt-1 block w-full rounded @error('cognome_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Nome</label>
                <input type="text" name="nome_madre" value="{{ old('nome_madre', $dichiarazione->nome_madre) }}" class="mt-1 block w-full rounded @error('nome_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Cittadinanza</label>
                <input type="text" name="cittadinanza_madre" value="{{ old('cittadinanza_madre', $dichiarazione->cittadinanza_madre ?? 'ITALIANA') }}" class="mt-1 block w-full rounded @error('cittadinanza_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Città di nascita</label>
                <input type="text" name="citta_nascita_madre" value="{{ old('citta_nascita_madre', $dichiarazione->citta_nascita_madre) }}" class="mt-1 block w-full rounded @error('citta_nascita_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Provincia di nascita</label>
                <input type="text" name="provincia_nascita_madre" value="{{ old('provincia_nascita_madre', $dichiarazione->provincia_nascita_madre) }}" class="mt-1 block w-full rounded @error('provincia_nascita_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Data di nascita</label>
                <input type="date" name="data_nascita_madre" value="{{ $valore('data_nascita_madre', 'Y-m-d') }}" class="mt-1 block w-full rounded @error('data_nascita_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-700">Comune di residenza</label>
                <input type="text" name="comune_residenza_madre" value="{{ old('comune_residenza_madre', $dichiarazione->comune_residenza_madre) }}" class="mt-1 block w-full rounded @error('comune_residenza_madre') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
        </div>
    </section>

    <section class="bg-white rounded-lg shadow p-6" x-show="info.gemello" x-cloak>
        <h2 class="text-base font-semibold text-slate-800 mb-4">Parto plurimo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Numero atto gemello</label>
                <input type="number" name="numero_atto_gemello" value="{{ old('numero_atto_gemello', $dichiarazione->numero_atto_gemello) }}" class="mt-1 block w-full rounded @error('numero_atto_gemello') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Codice atto gemello</label>
                <input type="text" name="codice_atto_gemello" value="{{ old('codice_atto_gemello', $dichiarazione->codice_atto_gemello) }}" class="mt-1 block w-full rounded @error('codice_atto_gemello') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Ordine di nascita</label>
                <select name="ordine_nascita_gemello" class="mt-1 block w-full rounded @error('ordine_nascita_gemello') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
                    <option value="">-- seleziona --</option>
                    @foreach (['PRIMO', 'SECONDO', 'TERZO', 'QUARTO', 'QUINTO', 'SESTO', 'SETTIMO', 'OTTAVO', 'NONO', 'DECIMO'] as $ordine)
                        <option value="{{ $ordine }}" @selected(old('ordine_nascita_gemello', $dichiarazione->ordine_nascita_gemello) == $ordine)>{{ ucfirst(strtolower($ordine)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    <section class="bg-white rounded-lg shadow p-6">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Trasmissione al Comune (facoltativo in questa fase)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Data spedizione raccomandata</label>
                <input type="date" name="data_spedizione_raccomandata" value="{{ $valore('data_spedizione_raccomandata', 'Y-m-d') }}" class="mt-1 block w-full rounded @error('data_spedizione_raccomandata') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Data invio comunicazione telematica</label>
                <input type="date" name="data_invio_comunicazione_telematica" value="{{ $valore('data_invio_comunicazione_telematica', 'Y-m-d') }}" class="mt-1 block w-full rounded @error('data_invio_comunicazione_telematica') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Numero protocollo</label>
                <input type="text" name="numero_protocollo" value="{{ old('numero_protocollo', $dichiarazione->numero_protocollo) }}" class="mt-1 block w-full rounded @error('numero_protocollo') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Comune destinatario</label>
                <input type="text" name="comune_destinatario" value="{{ old('comune_destinatario', $dichiarazione->comune_destinatario) }}" class="mt-1 block w-full rounded @error('comune_destinatario') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Comune di trascrizione</label>
                <input type="text" name="comune_di_trascrizione" value="{{ old('comune_di_trascrizione', $dichiarazione->comune_di_trascrizione) }}" class="mt-1 block w-full rounded @error('comune_di_trascrizione') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Conferma avvenuta trascrizione</label>
                <input type="text" name="conferma_avvenuta_trascrizione" value="{{ old('conferma_avvenuta_trascrizione', $dichiarazione->conferma_avvenuta_trascrizione) }}" class="mt-1 block w-full rounded @error('conferma_avvenuta_trascrizione') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Numero atto Comune</label>
                <input type="text" name="numero_atto_comune" value="{{ old('numero_atto_comune', $dichiarazione->numero_atto_comune) }}" class="mt-1 block w-full rounded @error('numero_atto_comune') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Anno trascrizione</label>
                <input type="number" name="anno_trascrizione" value="{{ old('anno_trascrizione', $dichiarazione->anno_trascrizione) }}" class="mt-1 block w-full rounded @error('anno_trascrizione') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-700">Note</label>
                <textarea name="note" rows="3" class="mt-1 block w-full rounded @error('note') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">{{ old('note', $dichiarazione->note) }}</textarea>
            </div>
        </div>
    </section>

    <div class="flex justify-end gap-3">
        <a href="{{ route('dichiarazioni.index') }}" class="px-4 py-2 rounded border border-slate-300 text-slate-700">Annulla</a>
        <button type="submit" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-900 text-white font-medium">Salva</button>
    </div>
</div>
