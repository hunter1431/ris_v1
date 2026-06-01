<?php

namespace App\Services;

use App\Models\ApprovalMatrixStep;
use App\Models\RisHeader;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ApprovalMatrixService
{
    public function nextStep(RisHeader $ris): ?ApprovalMatrixStep
    {
        return ApprovalMatrixStep::query()
            ->where('module', 'ris')
            ->where('is_active', true)
            ->where('level', $ris->current_approval_level + 1)
            ->first();
    }

    public function assertUserCanApprove(RisHeader $ris, User $user): ApprovalMatrixStep
    {
        $step = $this->nextStep($ris);

        if (! $step) {
            throw ValidationException::withMessages(['approval' => 'No remaining approval step is configured.']);
        }

        if (! $user->hasRole($step->role_name)) {
            throw ValidationException::withMessages([
                'approval' => "This RIS requires approval from role: {$step->role_name}.",
            ]);
        }

        return $step;
    }
}
