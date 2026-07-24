@extends('layouts.app')

@section('titolo', 'Cambia password')

@section('contenuto')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Cambia la tua password</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-md">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700">Password attuale</label>
                <input type="password" name="password_attuale" required autofocus
                    class="mt-1 block w-full rounded @error('password_attuale') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Nuova password</label>
                <input type="password" name="password_nuova" required
                    class="mt-1 block w-full rounded @error('password_nuova') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
                <p class="text-xs text-slate-500 mt-1">Almeno 10 caratteri, con lettere maiuscole, minuscole e numeri.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Conferma nuova password</label>
                <input type="password" name="password_nuova_confirmation" required
                    class="mt-1 block w-full rounded @error('password_nuova_confirmation') border-red-500 ring-1 ring-red-500 @else border-slate-300 @enderror">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-900 text-white font-medium">
                    Aggiorna password
                </button>
            </div>
        </form>
    </div>
@endsection
