<?php

namespace App\Services;

use App\DTOs\RisData;
use App\Events\RisApproved;
use App\Events\RisCreated;
use App\Events\RisIssued;
use App\Models\Approval;
use App\Models\RisHeader;
use App\Notifications\RisStatusChanged;
use App\Repositories\RisRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RisWorkflowService
{
    public function __construct(
        private RisNumberService $numbers,
        private RisRepository $repository,
    ) {
    }

    public function create(RisData $dto, int $userId): RisHeader
    {
        return DB::transaction(function () use ($dto, $userId) {
            $ris = $this->repository->createHeader($dto, [
                'ris_no' => $dto->header['ris_no'] ?? $this->numbers->generate(),
                'requested_by' => $userId,
                'status' => 'draft',
                'qr_token' => Str::uuid()->toString(),
            ]);

            $this->repository->createDetails($ris, $dto->details);
            event(new RisCreated($ris));

            return $ris->load(['division', 'details.item']);
        });
    }

    public function submit(RisHeader $ris, int $userId): RisHeader
    {
        return $this->transition($ris, $userId, 'submitted', 'pending');
    }

    public function approve(RisHeader $ris, int $userId, ?string $remarks = null): RisHeader
    {
        $ris = $this->transition($ris, $userId, 'approved', 'approved', $remarks, ['approved_by' => $userId]);
        event(new RisApproved($ris));
        $ris->requestedBy?->notify(new RisStatusChanged($ris, 'approved'));
        return $ris;
    }

    public function issue(RisHeader $ris, int $userId, array $details): RisHeader
    {
        return DB::transaction(function () use ($ris, $userId, $details) {
            foreach ($details as $row) {
                $detail = $ris->details()->whereKey($row['id'])->firstOrFail();
                $issuedQty = (float) $row['qty_issued'];
                $detail->update(['qty_issued' => $issuedQty, 'remarks' => $row['remarks'] ?? $detail->remarks]);
                $detail->item->deduct($issuedQty);
            }

            $ris = $this->transition($ris, $userId, 'issued', 'issued', null, ['issued_by' => $userId]);
            event(new RisIssued($ris));

            return $ris;
        });
    }

    public function complete(RisHeader $ris, int $userId): RisHeader
    {
        return $this->transition($ris, $userId, 'completed', 'completed', null, ['received_by' => $userId]);
    }

    private function transition(RisHeader $ris, int $userId, string $action, string $status, ?string $remarks = null, array $extra = []): RisHeader
    {
        return DB::transaction(function () use ($ris, $userId, $action, $status, $remarks, $extra) {
            $ris->update(['status' => $status, ...$extra]);
            Approval::create([
                'ris_id' => $ris->id,
                'user_id' => $userId,
                'action' => $action,
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);

            return $ris->fresh(['division', 'details.item', 'approvals']);
        });
    }
}
