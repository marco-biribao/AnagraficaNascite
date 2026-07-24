@extends('layouts.app')

@section('titolo', 'Modifica template ' . $template->nome)

@section('contenuto')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Template: {{ $template->nome }}</h1>
        <div class="space-x-2">
            <a href="{{ route('report-templates.index') }}" class="px-3 py-2 rounded border border-slate-300 text-slate-700 text-sm">Torna all'elenco</a>
            <a href="{{ route('report-templates.anteprima', $template) }}" target="_blank"
                class="px-3 py-2 rounded border border-slate-300 text-slate-700 text-sm">Anteprima</a>
        </div>
    </div>

    <form method="POST" action="{{ route('report-templates.update', $template) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 space-y-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700">Nome del template</label>
                            <input type="text" name="nome" value="{{ old('nome', $template->nome) }}" class="mt-1 block w-full rounded @error('nome') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
                        </div>
                        <label class="flex items-center gap-2 text-sm text-slate-600 pt-5">
                            <input type="checkbox" name="attivo" value="1" @checked(old('attivo', $template->attivo)) class="rounded border-slate-300">
                            Attivo
                        </label>
                    </div>

                    <textarea id="editor-contenuto" name="contenuto">{{ old('contenuto', $template->contenuto) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-900 text-white font-medium">
                        Salva nuova versione
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-sm font-semibold text-slate-800 mb-2">Segnaposto disponibili</h2>
                    <p class="text-xs text-slate-500 mb-3">Clicca per inserire nel punto in cui si trova il cursore.</p>
                    <div class="flex flex-wrap gap-1 max-h-80 overflow-y-auto">
                        @foreach ($segnaposto as $campo)
                            @php $token = '{{'.$campo.'}}'; @endphp
                            <button type="button"
                                onclick='window.tinymce.activeEditor.insertContent("{{ $token }}")'
                                class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-xs font-mono text-slate-700">
                                {{ $campo }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-sm font-semibold text-slate-800 mb-2">Cronologia versioni</h2>
                    <ul class="space-y-2 text-sm max-h-80 overflow-y-auto">
                        @forelse ($template->revisioni as $revisione)
                            <li class="flex items-center justify-between border-b border-slate-100 pb-1">
                                <span>
                                    v{{ $revisione->versione }}
                                    <span class="text-slate-400">- {{ optional($revisione->autore)->name ?? 'Sistema' }}</span>
                                </span>
                                <button type="button"
                                    form="form-ripristina-{{ $revisione->id }}"
                                    onclick='return confirm("Ripristinare la versione {{ $revisione->versione }}? Sarà salvata come nuova versione corrente.")'
                                    class="text-slate-500 hover:underline text-xs">
                                    Ripristina
                                </button>
                            </li>
                        @empty
                            <li class="text-slate-400">Nessuna versione precedente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </form>

    @foreach ($template->revisioni as $revisione)
        <form id="form-ripristina-{{ $revisione->id }}" method="POST"
            action="{{ route('report-templates.ripristina-revisione', [$template, $revisione]) }}" class="hidden">
            @csrf
        </form>
    @endforeach

    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor-contenuto',
            license_key: 'gpl',
            height: 650,
            menubar: false,
            plugins: 'lists table link code',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | table link | code',
            content_style: "body { font-family: 'Times New Roman', serif; font-size: 12pt; }",
            branding: false,
        });
    </script>
@endsection
