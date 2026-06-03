<?php

namespace App\Repositories;

use App\DTOs\RisData;
use App\Models\Item;
use App\Models\RisHeader;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RisRepository
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return RisHeader::with(['division', 'details.item'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function createHeader(RisData $dto, array $systemFields): RisHeader
    {
        return RisHeader::create([...$dto->header, ...$systemFields]);
    }

    public function createDetails(RisHeader $ris, array $details): Collection
    {
        foreach ($details as $detail) {
            $item = Item::findOrFail($detail['item_id']);
            $ris->details()->create([
                'item_id' => $item->id,
                'stock_no' => $item->stock_no,
                'unit' => $item->unit,
                'description' => $item->description,
                'qty_requested' => $detail['qty_requested'],
                'remarks' => $detail['remarks'] ?? null,
            ]);
        }

        return $ris->details()->get();
    }

    public function updateHeader(RisHeader $ris, array $data): RisHeader
    {
        $ris->update($data);

        return $ris->fresh();
    }

    public function replaceDetails(RisHeader $ris, array $details)
    {
        $ris->details()->delete();

        return $this->createDetails($ris, $details);
    }
}
