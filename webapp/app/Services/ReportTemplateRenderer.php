<?php

namespace App\Services;

use App\Contracts\GeneratoreDocumentoPdf;
use App\Models\DichiarazioneNascita;
use App\Models\ReportTemplate;

/**
 * Facade: espone un'unica API semplice (html/pdf) nascondendo la
 * composizione tra il merge dei segnaposto (SegnapostoMerger) e la
 * generazione del PDF (GeneratoreDocumentoPdf, un'interfaccia: non dipende
 * direttamente da Dompdf).
 */
class ReportTemplateRenderer
{
    public function __construct(
        private readonly SegnapostoMerger $merger,
        private readonly GeneratoreDocumentoPdf $generatorePdf,
    ) {}

    public function html(ReportTemplate $template, DichiarazioneNascita $dichiarazione): string
    {
        return $this->merger->unisci($template->contenuto, $dichiarazione);
    }

    public function pdf(ReportTemplate $template, DichiarazioneNascita $dichiarazione): string
    {
        return $this->generatorePdf->generaDaHtml($this->html($template, $dichiarazione));
    }
}
