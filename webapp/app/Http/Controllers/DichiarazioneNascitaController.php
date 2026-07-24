<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDichiarazioneNascitaRequest;
use App\Http\Requests\UpdateDichiarazioneNascitaRequest;
use App\Models\Dichiarante;
use App\Models\DichiarazioneNascita;
use App\Models\ModelloDichiarazione;
use App\Models\ReportTemplate;
use App\Services\ReportTemplateRenderer;
use App\Support\FiltriDichiarazione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DichiarazioneNascitaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:gestire-dichiarazioni', except: ['index', 'show']),
        ];
    }

    public function index(Request $request): View
    {
        $dichiarazioni = DichiarazioneNascita::filtra(FiltriDichiarazione::daRichiesta($request))
            ->with(['modello', 'dichiarante', 'operatore'])
            ->orderByDesc('data_atto')
            ->orderByDesc('numero_atto')
            ->paginate(20)
            ->withQueryString();

        return view('dichiarazioni.index', [
            'dichiarazioni' => $dichiarazioni,
            'modelli' => ModelloDichiarazione::orderBy('ordine')->get(),
        ]);
    }

    public function create(): View
    {
        return view('dichiarazioni.create', array_merge(
            ['dichiarazione' => new DichiarazioneNascita],
            $this->opzioniForm(new DichiarazioneNascita)
        ));
    }

    public function store(StoreDichiarazioneNascitaRequest $request): RedirectResponse
    {
        // operatore_id/created_by/updated_by vengono valorizzati automaticamente
        // da DichiarazioneNascitaObserver (evento "creating").
        $dichiarazione = DichiarazioneNascita::create($request->validated());

        return redirect()->route('dichiarazioni.show', $dichiarazione)
            ->with('successo', "Dichiarazione {$dichiarazione->codice_atto} registrata correttamente.");
    }

    public function show(DichiarazioneNascita $dichiarazione): View
    {
        $dichiarazione->load(['modello', 'dichiarante', 'operatore']);

        $templateStampabili = ReportTemplate::where('attivo', true)
            ->where(function ($q) use ($dichiarazione) {
                $q->where('modello_dichiarazione_id', $dichiarazione->modello_dichiarazione_id)
                    ->orWhere('slug', 'ricevuta');
            })
            ->get();

        return view('dichiarazioni.show', compact('dichiarazione', 'templateStampabili'));
    }

    public function stampa(DichiarazioneNascita $dichiarazione, ReportTemplate $reportTemplate, ReportTemplateRenderer $renderer): Response
    {
        $dichiarazione->load(['modello', 'dichiarante', 'operatore']);

        $nomeFile = $reportTemplate->slug.'-'.str_replace('/', '-', $dichiarazione->codice_atto).'.pdf';

        return response($renderer->pdf($reportTemplate, $dichiarazione), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$nomeFile}\"",
        ]);
    }

    public function edit(DichiarazioneNascita $dichiarazione): View
    {
        return view('dichiarazioni.edit', array_merge(
            ['dichiarazione' => $dichiarazione],
            $this->opzioniForm($dichiarazione)
        ));
    }

    public function update(UpdateDichiarazioneNascitaRequest $request, DichiarazioneNascita $dichiarazione): RedirectResponse
    {
        // updated_by viene valorizzato automaticamente dall'observer (evento "updating").
        $dichiarazione->update($request->validated());

        return redirect()->route('dichiarazioni.show', $dichiarazione)
            ->with('successo', "Dichiarazione {$dichiarazione->codice_atto} aggiornata correttamente.");
    }

    public function destroy(DichiarazioneNascita $dichiarazione): RedirectResponse
    {
        // updated_by viene valorizzato automaticamente dall'observer (evento "deleting").
        $dichiarazione->delete();

        return redirect()->route('dichiarazioni.index')
            ->with('successo', "Dichiarazione {$dichiarazione->codice_atto} esclusa. Puoi ripristinarla dall'elenco.");
    }

    public function restore(int $id): RedirectResponse
    {
        $dichiarazione = DichiarazioneNascita::withTrashed()->findOrFail($id);
        // updated_by viene valorizzato automaticamente dall'observer (evento "restoring").
        $dichiarazione->restore();

        return redirect()->route('dichiarazioni.index')
            ->with('successo', "Dichiarazione {$dichiarazione->codice_atto} ripristinata.");
    }

    /**
     * Elenco modelli/dichiaranti attivi per le tendine del form, includendo
     * comunque il valore attualmente selezionato anche se nel frattempo e'
     * stato disattivato (altrimenti sparirebbe dalla modifica di un record
     * gia' esistente).
     *
     * @return array{modelli: \Illuminate\Support\Collection, dichiaranti: \Illuminate\Support\Collection}
     */
    private function opzioniForm(DichiarazioneNascita $dichiarazione): array
    {
        return [
            'modelli' => ModelloDichiarazione::where('attivo', true)
                ->orWhere('id', $dichiarazione->modello_dichiarazione_id)
                ->orderBy('ordine')->get(),
            'dichiaranti' => Dichiarante::where('attivo', true)
                ->orWhere('id', $dichiarazione->dichiarante_id)
                ->orderBy('ordine')->get(),
        ];
    }
}
