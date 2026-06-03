<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        return ItemResource::collection(
            Item::query()
                ->when($request->search, fn ($query, $search) => $query
                    ->where('description', 'like', "%{$search}%")
                    ->orWhere('stock_no', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%"))
                ->orderBy('description')
                ->paginate(20)
        );
    }

    public function show(Item $item)
    {
        return new ItemResource($item->load('category'));
    }

    public function store(StoreItemRequest $request)
    {
        return new ItemResource(Item::create($request->validated()));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update($request->validated());

        return new ItemResource($item->refresh());
    }

    public function destroy(Request $request, Item $item)
    {
        abort_unless($request->user()?->can('manage inventory'), 403);

        $item->delete();

        return response()->noContent();
    }
}
