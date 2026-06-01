<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        return Item::query()
            ->when($request->search, fn ($query, $search) => $query
                ->where('description', 'like', "%{$search}%")
                ->orWhere('stock_no', 'like', "%{$search}%")
                ->orWhere('item_code', 'like', "%{$search}%"))
            ->orderBy('description')
            ->paginate(20);
    }
}
