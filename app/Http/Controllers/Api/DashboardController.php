<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RisDetail;
use App\Models\RisHeader;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): array
    {
        $user = request()->user();
        $driver = DB::getDriverName();
        $monthFormat = match ($driver) {
            'sqlite' => "strftime('%Y-%m', created_at)",
            'pgsql' => "to_char(created_at, 'YYYY-MM')",
            default => "DATE_FORMAT(created_at, '%Y-%m')",
        };

        $cards = [
            'total_ris' => RisHeader::count(),
            'pending_ris' => RisHeader::where('status', 'pending')->count(),
            'approved_ris' => RisHeader::where('status', 'approved')->count(),
            'issued_ris' => RisHeader::where('status', 'issued')->count(),
        ];

        $permissions = [
            'can_create_ris' => $user->can('create ris'),
            'can_approve_ris' => $user->can('approve ris'),
            'can_issue_stocks' => $user->can('issue stocks'),
            'can_view_inventory' => $user->can('view inventory'),
            'can_manage_users' => $user->can('manage users'),
            'can_audit' => $user->can('audit trail'),
        ];

        return [
            'cards' => $cards,
            'permissions' => $permissions,
            'monthly_requests' => RisHeader::query()
                ->selectRaw("{$monthFormat} as month, COUNT(*) as total")
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
