<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Exports\MutationsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class ExportController extends Controller
{
    public function invoice(Request $request)
    {
        $filename = 'nota-' . now()->format('ymdHis') . '.xlsx';

        return Excel::download(new InvoicesExport($request->id), $filename);
    }

    public function mutation()
    {
        $filename = 'mutasi-' . now()->format('ymdHis') . '.xlsx';

        return Excel::download(new MutationsExport(), $filename);
    }
}
