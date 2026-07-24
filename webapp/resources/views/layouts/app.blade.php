<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} @hasSection('titolo') - @yield('titolo') @endif</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    @auth
    <nav class="bg-slate-800 text-white">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-14">
            <div class="flex items-center gap-6">
                <a href="{{ route('dichiarazioni.index') }}" class="font-semibold">{{ config('app.name') }}</a>
                <a href="{{ route('dichiarazioni.index') }}" class="text-sm text-slate-200 hover:text-white">Dichiarazioni</a>
                <a href="{{ route('dichiarazioni.create') }}" class="text-sm text-slate-200 hover:text-white">Nuova dichiarazione</a>
                @can('gestire-sistema')
                @if (Route::has('report-templates.index'))
                <a href="{{ route('report-templates.index') }}" class="text-sm text-slate-200 hover:text-white">Template report</a>
                @endif
                @if (Route::has('utenti.index'))
                <a href="{{ route('utenti.index') }}" class="text-sm text-slate-200 hover:text-white">Utenti</a>
                @endif
                @endcan
            </div>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-slate-300">{{ auth()->user()->name }}</span>
                @if (auth()->user()->auth_source === 'local' && Route::has('password.edit'))
                    <a href="{{ route('password.edit') }}" class="text-slate-200 hover:text-white">Cambia password</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-slate-200 hover:text-white">Esci</button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6">
        @if (session('successo'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-3 text-sm">
                {{ session('successo') }}
            </div>
        @endif

        @if ($errors->any())
            @php $etichetteCampi = trans('validation.attributes'); @endphp
            <div class="mb-4 rounded border border-red-300 bg-red-50 text-red-800 px-4 py-3 text-sm">
                <p class="font-medium mb-1">Controlla i campi indicati:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->messages() as $campo => $messaggi)
                        @php
                            $etichetta = $etichetteCampi[$campo] ?? str_replace('_', ' ', $campo);
                        @endphp
                        <li>
                            <span class="font-semibold">{{ ucfirst($etichetta) }}</span>
                            {{ implode(' ', $messaggi) }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('contenuto')
    </main>
</body>
</html>
