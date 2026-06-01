<?php

namespace App\Exports;

use App\Models\RisHeader;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RisSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return RisHeader::with('division')->latest()->get();
    }

    public function headings(): array
    {
        return ['Date', 'RIS Number', 'Division', 'Office', 'Purpose', 'Status'];
    }

    public function map($ris): array
    {
        return [$ris->created_at->toDateString(), $ris->ris_no, $ris->division->name, $ris->office, $ris->purpose, $ris->status];
    }
}
