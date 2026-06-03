<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stock_no' => $this->stock_no,
            'item_code' => $this->item_code,
            'description' => $this->description,
            'unit' => $this->unit,
            'category_id' => $this->category_id,
            'quantity_on_hand' => (float) $this->quantity_on_hand,
            'reorder_level' => (float) $this->reorder_level,
            'status' => $this->status,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ]),
        ];
    }
}
