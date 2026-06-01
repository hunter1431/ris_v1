<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RisResource;
use App\Models\RisHeader;

class VerificationController extends Controller
{
    public function show(string $token): RisResource
    {
        $ris = RisHeader::with(['division', 'details.item', 'approvals'])
            ->where('qr_token', $token)
            ->firstOrFail();

        return new RisResource($ris);
    }
}
