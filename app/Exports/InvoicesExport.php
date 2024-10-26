<?php

namespace App\Exports;

use App\Models\ExitInvoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport implements FromView
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    public function view(): View
    {
        $exitInvoice = ExitInvoice::with('items.item')->findOrFail($this->id);

        return view('exports.invoice', [
            'exitInvoice' => $exitInvoice
        ]);
    }
}
