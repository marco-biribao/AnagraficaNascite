<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReportTemplateRequest;
use App\Models\DichiarazioneNascita;
use App\Models\ReportTemplate;
use App\Models\ReportTemplateRevision;
use App\Services\DichiarazioneEsempioFactory;
use App\Services\DichiarazioneReportData;
use App\Services\ReportTemplateRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ReportTemplateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:gestire-sistema'),
        ];
    }

    public function index(): View
    {
        $templates = ReportTemplate::with('modello')->orderBy('nome')->get();

        return view('report-templates.index', compact('templates'));
    }

    public function edit(ReportTemplate $reportTemplate): View
    {
        $reportTemplate->load(['revisioni.autore']);

        return view('report-templates.edit', [
            'template' => $reportTemplate,
            'segnaposto' => DichiarazioneReportData::elencoSegnaposto(),
        ]);
    }

    public function update(UpdateReportTemplateRequest $request, ReportTemplate $reportTemplate): RedirectResponse
    {
        $dati = $request->validated();

        $reportTemplate->salvaNuovaVersione($dati['contenuto'], $request->user()->id);
        $reportTemplate->update([
            'nome' => $dati['nome'],
            'attivo' => $request->boolean('attivo'),
        ]);

        return redirect()->route('report-templates.edit', $reportTemplate)
            ->with('successo', "Template aggiornato (versione {$reportTemplate->versione}).");
    }

    public function anteprima(ReportTemplate $reportTemplate, ReportTemplateRenderer $renderer): Response
    {
        $dichiarazione = DichiarazioneNascita::where('modello_dichiarazione_id', $reportTemplate->modello_dichiarazione_id)
            ->latest()
            ->first() ?? DichiarazioneEsempioFactory::perTemplate($reportTemplate);

        return response($renderer->html($reportTemplate, $dichiarazione));
    }

    public function ripristinaRevisione(ReportTemplate $reportTemplate, ReportTemplateRevision $revisione): RedirectResponse
    {
        abort_unless($revisione->report_template_id === $reportTemplate->id, 404);

        $reportTemplate->salvaNuovaVersione($revisione->contenuto, request()->user()->id);

        return redirect()->route('report-templates.edit', $reportTemplate)
            ->with('successo', "Ripristinata la versione {$revisione->versione} (ora versione {$reportTemplate->versione}).");
    }
}
