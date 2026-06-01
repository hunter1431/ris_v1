<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Item::select('stock_no', 'item_code', 'description', 'unit', 'quantity_on_hand', 'reorder_level', 'status')->get();
    }

    public function headings(): array
    {
        return ['Stock No', 'Item Code', 'Description', 'Unit', 'Current Stocks', 'Reorder Level', 'Status'];
    }
}
