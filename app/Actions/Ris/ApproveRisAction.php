<?php

namespace App\Actions\Ris;

use App\Models\RisHeader;
use App\Services\RisWorkflowService;

class ApproveRisAction
{
    public function __construct(private RisWorkflowService $service)
    {
    }

    public function execute(RisHeader $ris, int $userId, ?string $remarks = null): RisHeader
    {
        return $this->service->approve($ris, $userId, $remarks);
    }
}
