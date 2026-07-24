<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accesso - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-white shadow rounded-lg p-8">
        <h1 class="text-lg font-semibold text-slate-800 mb-1">{{ config('app.name') }}</h1>
        <p class="text-sm text-slate-500 mb-6">Accedi con le tue credenziali</p>

        @if ($errors->any())
            <div class="mb-4 rounded border border-red-300 bg-red-50 text-red-800 px-3 py-2 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-slate-700">Nome utente</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                    class="mt-1 block w-full rounded shadow-sm focus:border-slate-500 focus:ring-slate-500 @error('username') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded shadow-sm focus:border-slate-500 focus:ring-slate-500 @error('username') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="ricordami" class="rounded border-slate-300">
                Ricordami
            </label>
            <button type="submit"
                class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 rounded">
                Accedi
            </button>
        </form>
    </div>
</body>
</html>
