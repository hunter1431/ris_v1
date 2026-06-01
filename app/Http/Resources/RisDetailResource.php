<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RisDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'stock_no' => $this->stock_no,
            'unit' => $this->unit,
            'description' => $this->description,
            'qty_requested' => (float) $this->qty_requested,
            'qty_issued' => (float) $this->qty_issued,
            'remarks' => $this->remarks,
        ];
    }
}
