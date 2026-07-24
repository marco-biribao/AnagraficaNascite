@extends('layouts.app')

@section('titolo', 'Nuova dichiarazione')

@section('contenuto')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Nuova dichiarazione di nascita</h1>

    <form method="POST" action="{{ route('dichiarazioni.store') }}">
        @csrf
        @include('dichiarazioni._form')
    </form>
@endsection
