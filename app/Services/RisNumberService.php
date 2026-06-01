<?php

namespace App\Services;

use App\Models\RisHeader;

class RisNumberService
{
    public function generate(): string
    {
        $count = RisHeader::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;

        return now()->format('Y-m').'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
