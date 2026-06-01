<?php

namespace App\Http\Controllers\Api;

use App\Exports\InventoryExport;
use App\Exports\RisSummaryExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function risSummary()
    {
        return Excel::download(new RisSummaryExport(), 'ris-summary.xlsx');
    }

    public function inventory()
    {
        return Excel::download(new InventoryExport(), 'inventory.xlsx');
    }
}
