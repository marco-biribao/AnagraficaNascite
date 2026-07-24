@extends('layouts.app')

@section('titolo', 'Modifica dichiarazione')

@section('contenuto')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Modifica dichiarazione {{ $dichiarazione->codice_atto }}</h1>

    <form method="POST" action="{{ route('dichiarazioni.update', $dichiarazione) }}">
        @csrf
        @method('PUT')
        @include('dichiarazioni._form')
    </form>
@endsection
