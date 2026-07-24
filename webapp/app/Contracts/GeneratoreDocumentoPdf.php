<?php

namespace App\Contracts;

/**
 * Contratto per la generazione di un PDF a partire da HTML. ReportTemplateRenderer
 * dipende solo da questa interfaccia, non dalla libreria PDF concreta
 * (oggi Dompdf): per cambiare motore di rendering basta scrivere una nuova
 * classe e cambiare il binding in AppServiceProvider, senza toccare il
 * resto dell'applicazione.
 */
interface GeneratoreDocumentoPdf
{
    /**
     * @return string I byte del PDF generato.
     */
    public function generaDaHtml(string $html): string;
}
