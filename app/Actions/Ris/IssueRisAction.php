<?php

namespace App\Actions\Ris;

use App\Models\RisHeader;
use App\Services\RisWorkflowService;

class IssueRisAction
{
    public function __construct(private RisWorkflowService $service)
    {
    }

    public function execute(RisHeader $ris, int $userId, array $details): RisHeader
    {
        return $this->service->issue($ris, $userId, $details);
    }
}
