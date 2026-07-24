<?php

namespace App\Services\Pdf;

use App\Contracts\GeneratoreDocumentoPdf;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Adapter: traduce il contratto GeneratoreDocumentoPdf nelle chiamate
 * concrete alla libreria Dompdf (tramite il pacchetto barryvdh/laravel-dompdf).
 */
class DompdfGeneratoreDocumento implements GeneratoreDocumentoPdf
{
    public function generaDaHtml(string $html): string
    {
        return Pdf::loadHTML($html)->setPaper('a4')->output();
    }
}
