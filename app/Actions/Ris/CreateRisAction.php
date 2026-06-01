<?php

namespace App\Actions\Ris;

use App\DTOs\RisData;
use App\Models\RisHeader;
use App\Services\RisWorkflowService;

class CreateRisAction
{
    public function __construct(private RisWorkflowService $service)
    {
    }

    public function execute(RisData $dto, int $userId): RisHeader
    {
        return $this->service->create($dto, $userId);
    }
}
