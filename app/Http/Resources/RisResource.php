<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RisResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ris_no' => $this->ris_no,
            'entity_name' => $this->entity_name,
            'fund_cluster' => $this->fund_cluster,
            'office' => $this->office,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'division' => $this->whenLoaded('division'),
            'details' => RisDetailResource::collection($this->whenLoaded('details')),
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
