<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RisDetail;
use App\Models\RisHeader;

class DashboardController extends Controller
{
    public function __invoke(): array
    {
        return [
            'cards' => [
                'total_ris' => RisHeader::count(),
                'pending_ris' => RisHeader::where('status', 'pending')->count(),
                'approved_ris' => RisHeader::where('status', 'approved')->count(),
                'issued_ris' => RisHeader::where('status', 'issued')->count(),
            ],
            'monthly_requests' => RisHeader::query()
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'most_requested_items' => RisDetail::query()
                ->selectRaw('description, SUM(qty_requested) as total')
                ->groupBy('description')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),
        ];
    }
}
