<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DichiarazioneNascitaController;
use App\Http\Controllers\ReportTemplateController;
use App\Http\Controllers\UtenteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dichiarazioni.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::resource('dichiarazioni', DichiarazioneNascitaController::class)
        ->parameters(['dichiarazioni' => 'dichiarazione']);

    Route::post('/dichiarazioni/{id}/ripristina', [DichiarazioneNascitaController::class, 'restore'])
        ->name('dichiarazioni.restore');

    Route::get('/dichiarazioni/{dichiarazione}/stampa/{reportTemplate:slug}', [DichiarazioneNascitaController::class, 'stampa'])
        ->name('dichiarazioni.stampa')
        ->withoutScopedBindings();

    Route::get('/report-templates', [ReportTemplateController::class, 'index'])->name('report-templates.index');
    Route::get('/report-templates/{reportTemplate}/modifica', [ReportTemplateController::class, 'edit'])->name('report-templates.edit');
    Route::put('/report-templates/{reportTemplate}', [ReportTemplateController::class, 'update'])->name('report-templates.update');
    Route::get('/report-templates/{reportTemplate}/anteprima', [ReportTemplateController::class, 'anteprima'])->name('report-templates.anteprima');
    Route::post('/report-templates/{reportTemplate}/revisioni/{revisione}/ripristina', [ReportTemplateController::class, 'ripristinaRevisione'])
        ->name('report-templates.ripristina-revisione');

    Route::get('/utenti', [UtenteController::class, 'index'])->name('utenti.index');
    Route::get('/utenti/{utente}/modifica', [UtenteController::class, 'edit'])->name('utenti.edit');
    Route::put('/utenti/{utente}', [UtenteController::class, 'update'])->name('utenti.update');
});
