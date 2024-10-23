<?php

namespace App\Http\Controllers;

use App\Models\ExitInvoice;
use App\Models\ExitItem;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class PDFController
{
    public function generatePDF($id)
    {
        $exitInvoice = ExitInvoice::with('exitItems.item')->findOrFail($id);

        $html = View::make('pdf.exit-invoice', compact('exitInvoice'))->render();
        // return $html;

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = now()->format('YmdHis') . '.pdf';

        return $dompdf->stream($filename);
    }
}
