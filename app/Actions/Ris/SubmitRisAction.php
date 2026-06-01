<?php

namespace App\Actions\Ris;

use App\Models\RisHeader;
use App\Services\RisWorkflowService;

class SubmitRisAction
{
    public function __construct(private RisWorkflowService $service)
    {
    }

    public function execute(RisHeader $ris, int $userId): RisHeader
    {
        return $this->service->submit($ris, $userId);
    }
}
